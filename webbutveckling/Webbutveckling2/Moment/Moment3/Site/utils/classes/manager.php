<?php

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/news.php";


/**
 * Class Manager
 * manages all transactions between the sites backend and the database
 *
 * @property mysqli $connection mysqli connection object
 */
class Manager

{
    private $connection;

    /**
     * Manager constructor.
     */
    public function __construct()

    {
        $this->connection = new mysqli('eliaseriksson.eu', 'eler2006', 'miunstudent', 'miun');
        if ($this->connection->connect_errno !== 0) {
            die("Fel vid anslutning");
        }
    }

    /**
     * installs the database but running this method once
     *
     * @return bool true if successful
     */
    public function installDatabase(): bool
    {
        $sql = "drop table if exists admins;";
        $sql .= "drop table if exists news;";
        $sql .= "create table admins (username varchar(32) not null , passwordHash varchar(256));";
        $sql .= "create table news (id char(13), title varchar(256), preamble text, content text, postDate timestamp default current_timestamp, lastEdited timestamp);";

        $sql .= "alter table admins add constraint adminsPK primary key (username);";
        $sql .= "alter table news add constraint newsPK primary key (id);";
        $sql .= "alter table news modify lastEdited timestamp null;";
        $result = $this->connection->multi_query($sql);
        $this->connection->commit();
        return $result;
    }

    /**
     * adds an admin user to the database
     *
     * @param string $username the admins username
     * @param string $password the admins password submitted by a user
     * @return Admin|null Admin if it was added, null if it failed
     */
    public function addAdmin(string $username, string $password): ?Admin
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "insert into admins values ('$username', '$passwordHash');";
        $result = $this->connection->query($sql);
        echo var_dump($result)."<br>";
        if ($result) {
            return new Admin($username, $passwordHash);
        }
        return null;
    }

    /**
     * gets an admin user from the database
     *
     * @param string $username the admins username
     * @return Admin|null Admin if it existed, null if it didnt exist
     */
    public function getAdmin(string $username): ?Admin
    {
        $sql = "select * from admins where username='$username';";
        $result = $this->connection->query($sql);
        if ($result->num_rows) {
            return Admin::fromAssociativeArray(($result->fetch_assoc()));
        }
        return null;
    }

    /**
     * attempts to add a new news article to the database
     * the news article is given a unique id
     * if the unique id exists another attempt is made with another unique id
     *
     * @param string $title the news title
     * @param string $preamble the news preamble
     * @param string $content the news articles main body content
     * @return News|null News if successful, null if it failed
     */
    public function addNews(string $title, string $preamble, string $content): ?News
    {
        $id = uniqid();
        $sql = "insert into news values ('$id', '$title','$preamble', '$content', now(), null);";
        $result = $this->connection->query($sql);
        if ($result) {
            return $this->getNews($id);
        } elseif ($this->connection->errno === 1062) {
            return $this->addNews($title, $preamble, $content);
        }
        return null;
    }

    /**
     * updates a news article with a specific id with given data
     *
     * @param string $id the news articles id
     * @param string $title the news articles title
     * @param string $preamble the news articles preamble
     * @param string $content the news articles main body content
     * @return bool true if it was updated
     */
    public function updateNews(string $id, string $title, string $preamble, string $content): bool
    {
        $sql = "update news set title='$title', content='$content', preamble='$preamble', lastEdited=now() where id='$id';";
        $result = $this->connection->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * queries a single news article from the database
     *
     * @param string $id the news articles id
     * @return News|null News if successful, null if it didnt exist
     */
    public function getNews(string $id): ?News
        /**
         * queries a single news article from the database and constructs a news object
         */
    {
        $sql = "select * from news where id='$id';";
        $result = $this->connection->query($sql);
        if ($result) {
            return new News($result->fetch_assoc());
        }
        return null;
    }

    /**
     * queries the database for N amount of news after skipping the offset first amount of news
     *
     * @param int $limit the amount of news to get
     * @param int $offset the amount of news to skip before getting news
     * @return array array[News] the queried news
     */
    public function getNewsN(int $limit, int $offset = 0): array
    {
        $sql = "select * from news order by postDate desc limit $limit offset $offset";
        $result = $this->connection->query($sql);
        $news = [];
        if ($result) {
            while ($newsData = $result->fetch_assoc()) {
                array_push($news, new News($newsData));
            }
            return $news;
        }
        return $news;
    }

    /**
     * counts the amount of news in the database, if something went wrong -1 is returned.
     *
     * @return int the amount of news
     */
    public function getAmountOfNews(): int
    {
        $sql = "select count(*) from news;";
        $result = $this->connection->query($sql);
        if ($result) {
            return $result->fetch_row()[0];
        }
        return -1;

    }

    /**
     * removes a single news article from the database with id $id
     *
     * @param string $id the news articles id
     * @return bool true if the news article was removed
     */
    public function removeNews(string $id): bool
    {
        $sql = "delete from news where id='$id';";
        $result = $this->connection->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * closes the database connection when the object falls out of scope
     */
    public function __destruct()
    {
        $this->connection->close();
    }
}

/**
 * code for testing things and stuff
 */
//echo "<br><br>";
//$manager = new Manager();
//echo $manager->installDatabase();

//$manager->addAdmin("elias-eriksson", "student");
//$manager->addAdmin("hello", "hello");
//var_dump($manager->addNews("hello","world", "hello world!"));
//$manager->addNews("hi", "hi world!");

//echo var_dump($manager->getNews(1))."<br><br>";
//echo var_dump($manager->getNewsN(5))."<br><br>";
//echo var_dump($manager->updateNews(1, "hello world!", "hello big world!"))."<br><br>";
//echo var_dump($manager->getNewsN(1))."<br><br>";
//$manager->removeNews(1);

//$result = $manager->getNewsN(3);
//
//foreach ($result as $news) {
//    echo $news->getId()."<br>";
//}
//echo var_dump($manager->getAmountOfNews())."<br><br>";
//echo var_dump()."<br><br>";
//echo var_dump()."<br><br>";
//$manager->getNewsN(1);
