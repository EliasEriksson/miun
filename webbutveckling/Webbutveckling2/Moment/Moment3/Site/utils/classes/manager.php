<?php

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/admin.php";
include_once __DIR__ . "/news.php";


class Manager
    /**
     * manages all transactions between the sites backend and the database
     */
{
    private $connection;

    public function __construct()
        /**
         * sets up the connection to the database
         */
    {
        $this->connection = new mysqli('eliaseriksson.eu', 'eler2006', 'miunstudent', 'miun');
        if ($this->connection->connect_errno !== 0) {
            die("Fel vid anslutning");
        }
    }

    public function installDatabase(): bool
        /**
         * installs the database but running this method once.
         */
    {
        $sql = "drop table if exists admins;";
        $sql .= "drop table if exists news;";
        $sql .= "create table admins (username varchar(32) not null , passwordHash varchar(256));";
        $sql .= "create table news (id char(13), title varchar(256), preamble text, content text, postDate timestamp default current_timestamp, lastEdited timestamp);";

        $sql .= "alter table admins add constraint adminsPK primary key (username);";
        $sql .= "alter table news add constraint newsPK primary key (id);";
        $sql .= "alter table news modify  lastEdited timestamp null;";
        $result = $this->connection->multi_query($sql);
        $this->connection->commit();
        return $result;
    }

    public function addAdmin($username, $password): ?Admin
        /**
         * adds and admin account to the site with
         *
         * admins are able to create, delete and update news
         * if successful an Admin is created with the data used to insert to the database else null
         */
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

    public function getAdmin(string $username): ?Admin
        /**
         * queries an admin with $username from the database
         *
         * an Admin object is returned if the query was successful otherwise null
         */
    {
        $sql = "select * from admins where username='$username';";
        $result = $this->connection->query($sql);
        if ($result->num_rows) {
            return Admin::fromAssociativeArray(($result->fetch_assoc()));
        }
        return null;
    }

    public function addNews(string $title, string $preamble, string $content): ?News
        /**
         * adds a news object to the database
         *
         * if successful the news object is queried with the generated id and is returned
         */
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

    public function updateNews(string $id, string $title, string $preamble, string $content): bool
        /**
         * updates a news article with the given id with the given data
         */
    {
        $sql = "update news set title='$title', content='$content', preamble='$preamble', lastEdited=now() where id='$id';";
        $result = $this->connection->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

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

    public function getNewsN(int $limit, int $offset = 0): array
        /**
         * queries the $limit amount of news after $offset amount of news (sorted by timeCreated)
         *
         * if limit is 2 and offset is 2 it will skip the first 2 news and query the 3rd and 4rth news
         */
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

    public function getAmountOfNews(): int
        /**
         * counts the amount of news in the database if something went wrong -1 is returned.
         */
    {
        $sql = "select count(*) from news;";
        $result = $this->connection->query($sql);
        if ($result) {
            return $result->fetch_row()[0];
        }
        return -1;

    }

    public function removeNews(string $id): bool
        /**
         * removes a single news article from the database with id $id
         */
    {
        $sql = "delete from news where id='$id';";
        $result = $this->connection->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function __destruct()
        /**
         * closes the database connection when the object falls out of scope
         */
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
