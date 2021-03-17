<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/cluck.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";


/**
 * Class Manager
 *
 * manages all the traffic between the web app and the database
 */
class Manager
{
    private $connection;
    private $pageLimit;

    public function __construct(int $pageLimit = 10)
    {
        $this->connection = new mysqli(
            "eliaseriksson.eu",
            "web2mom4",
            "password",
            "web2mom4"
        );
        if ($this->connection->connect_errno !== 0) {
            die("Could not connect. Error: " . $this->connection->connect_errno . " " . $this->connection->connect_error);
        }
        $this->pageLimit = $pageLimit;
    }

    /**
     * strips all tags but the allowed tags on the site
     *
     * as of writing this only <a> are allowed
     * on some inputs no tags are allowed. those fields will have the built in strip_tags called on them instead.
     *
     * @param string $string
     * @return string
     */
    private function stripTags(string $string): string
    {
        return strip_tags($string, "<a>");
    }

    /**
     * searches the error after a message stating that the given key resulted in errno 1062.
     *
     * if the specified key threw the error return true else false.
     *
     * @param string $key
     * @return bool
     */
    private function checkDuplicateKey(string $key): bool
    {
        if ($this->connection->errno === 1062) {
            if (preg_match("/^Duplicate\sentry\s'[^ ]+'\sfor\skey\s'$key'$/", $this->connection->error)) {
                return true;
            }
        }
        return false;
    }

    /**
     * creates a new user from the given email and password.
     *
     * if a user is successfully created a user object is returned else null.
     *
     * if errno 1062 occurs on the url key it was probably because hte same URL have been generated previously
     * and a new attempt to create the user is made. this happens recursively. if 5 attempts are made with the
     * same error the null will be returned.
     *
     * @param string $email
     * @param string $password
     * @param int $failedRetries
     * @return User|null
     */
    public function createUser(string $email, string $password, int $failedRetries = 0): ?User
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $url = uniqid();
        $query = $this->connection->prepare("insert into users values (default, ?, '$passwordHash', '$url');");
        $email = strip_tags($email);

        if ($query->bind_param("s", $email) && $query->execute()) {
            return new User($this->connection->insert_id, $email, $passwordHash, $url);
        }
        if ($failedRetries >= 4 && $this->checkDuplicateKey("url")) {
            $failedRetries++;
            return $this->createUser($email, $password, $failedRetries);
        }
        return null;
    }

    /**
     * queries the database for the user with id $id
     *
     * if query is successful a User object is returned else null
     *
     * @param int $id
     * @return User|null
     */
    public function getUser(int $id): ?User
    {
        $query = $this->connection->prepare("select * from users where id = ?;");

        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return User::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * queries the database for a "page" of latest users
     *
     * a page contains $this->pageLimit amount of users
     *
     * the resulting query tuples are converted to user
     * objects and returned as an array of user objects.
     *
     * @param int $page
     * @return array
     */
    public function getLatestUsers(int $page): array
    {
        $offset = $this->pageLimit * $page;
        $users = [];

        $query = $this->connection->prepare("select * from users order by users.id desc limit $this->pageLimit offset ?");
        if ($query->bind_param("i", $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($userData = $result->fetch_assoc()) {
                    array_push($users, User::fromAssoc($userData));
                }
            }
        }
        return $users;
    }

    /**
     * queries the database for the user with the given url
     *
     * if successful the user is returned as an object else null
     *
     * @param string $email
     * @return User|null
     */
    public function getUserFromEmail(string $email): ?User
    {
        $query = $this->connection->prepare("select * from users where email = ?;");
        $email = strip_tags($email);
        if ($query->bind_param("s", $email) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return User::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * queries the database for a user from its unique URL
     *
     * returns a user object if successful else null
     *
     * @param string $url
     * @return User|null
     */
    public function getUserFromURL(string $url): ?User
    {
        $query = $this->connection->prepare("select * from users where url = ?;");
        $url = strip_tags($url);
        if ($query->bind_param("s", $url) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return User::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * queries the database for the amount of replies on a post
     *
     * @param int $id
     * @return int
     */
    public function getUserReplyCount(int $id): int
    {
        $query = $this->connection->prepare("select count(*) as replyCount
                from (select clucks.id from users join clucks on users.id = clucks.userID where users.id = ?) joined
                join replies on joined.id = replies.replyCluckID;");
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return $result->fetch_assoc()["replyCount"];
            }
        }
        return 0;
    }

    /**
     * queries the amount of posts a user have made
     *
     * @param int $id
     * @return int
     */
    public function getUserPostCount(int $id): int
    {
        $query = $this->connection->prepare("select count(*) as postCount from users join clucks on users.id = clucks.userID where users.id = ?;");
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return $result->fetch_assoc()["postCount"];
            }
        }
        return 0;
    }

    /**
     * creates a new userProfile in the database
     *
     * if successful a userProfile object is returned else null
     *
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $avatar
     * @param string $description
     * @return UserProfile|null
     */
    public function createUserProfile(int $id, string $firstName, string $lastName, string $avatar, string $description): ?UserProfile
    {
        $query = $this->connection->prepare(
            "insert into userProfiles values (?, ?, ?, ?, ?);"
        );
        $firstName = strip_tags($firstName);
        $lastName = strip_tags($lastName);
        $description = $this->stripTags($description);
        if ($query->bind_param("issss", $id, $firstName, $lastName, $avatar, $description) && $query->execute()) {
            return new UserProfile($id, $firstName, $lastName, $avatar, $description);
        }
        return null;
    }

    /**
     * updates a userProfile in the database
     *
     * if successful a new userProfile object is returned else null
     *
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $avatar
     * @param string $description
     * @return UserProfile|null
     */
    public function updateUserProfile(int $id, string $firstName, string $lastName, string $avatar, string $description): ?UserProfile
    {
        $query = $this->connection->prepare(
            "update userProfiles set firstName = ?, lastName = ?, avatar = ?, description = ? where userID = ?;"
        );
        $firstName = strip_tags($firstName);
        $lastName = strip_tags($lastName);
        $description = $this->stripTags($description);
        if ($query->bind_param("ssssi", $firstName, $lastName, $avatar, $description, $id) && $query->execute()) {
            return new UserProfile($id, $firstName, $lastName, $avatar, $description);
        }
        return null;
    }

    /**
     * queries a userProfile from the userID
     *
     * if successful a new userProfile object is returned else null
     *
     * @param int $id
     * @return UserProfile|null
     */
    public function getUserProfile(int $id): ?UserProfile
    {
        $query = $this->connection->prepare(
            "select * from users join userProfiles on users.id = userProfiles.userID where id=?;"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return UserProfile::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * connects one post to another as a reply in the database
     *
     * if successful true is returned else false
     *
     * @param int $thisCluckID
     * @param int $replyCluckID
     * @return bool
     */
    public function createReply(int $thisCluckID, int $replyCluckID): bool
    {
        $query = $this->connection->prepare(
            "insert into replies values (default, ?, ?);"
        );
        if ($query->bind_param("ii", $thisCluckID, $replyCluckID) && $query->execute()) {
            return true;
        }
        return false;
    }

    /**
     * create a new post in the database
     *
     * if the post is a reply an id to the post is replying to is required.
     * returns a new CLuck object if successful else null
     *
     * @param int $userID
     * @param string $title
     * @param string $content
     * @param int $replyID
     * @return Cluck|null
     */
    public function createCluck(int $userID, string $title, string $content, int $replyID = 0): ?Cluck
    {
        $url = uniqid();
        $query = $this->connection->prepare(
            "insert into clucks values (default, ?, ?, ?, ?, now(), null);"
        );
        $title = $this->stripTags($title);
        $content = $this->stripTags($content);
        if ($query->bind_param("isss", $userID, $title, $content, $url) && $query->execute()) {
            $id = $this->connection->insert_id;
            if ($replyID) {
                if ($result = $this->createReply($id, $replyID)) {
                    return $this->getCluck($id);
                } else {
                    $this->deleteCluck($id);
                }
            } else {
                return $this->getCluck($id);
            }
        }
        if ($this->checkDuplicateKey("url")) {
            return $this->createCluck($userID, $content, $replyID);
        }
        return null;
    }

    /**
     * deletes a post based on its id
     *
     * if successful true is returned else false
     *
     * @param int $id
     * @return bool
     */
    public function deleteCluck(int $id): bool
    {
        $query = $this->connection->prepare(
            "delete from clucks where id = ?;"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            return true;
        }
        return false;
    }

    /**
     * updates a post
     *
     * if successful a new CLuck object is returned else null
     *
     * @param int $id
     * @param string $title
     * @param string $content
     * @return Cluck|null
     */
    public function updateCluck(int $id, string $title, string $content): ?Cluck
    {
        $query = $this->connection->prepare(
            "update clucks set title = ?, content = ?, lastEdited = now() where id = ?;"
        );
        $title = $this->stripTags($title);
        $content = $this->stripTags($content);
        if ($query->bind_param("ssi", $title, $content, $id) && $query->execute()) {
            return $this->getCluck($id);
        }
        return null;
    }

    /**
     * queries the database for a post based on its ID
     *
     * if successful a new Cluck object is returned else null
     *
     * @param int $id
     * @return Cluck|null
     */
    public function getCluck(int $id): ?Cluck
    {
        $query = $this->connection->prepare(
            "select * from clucks where id = ?;"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return Cluck::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * queries the database for a post from the posts unique URL
     *
     * if successful a new Cluck object is returned else null
     *
     * @param string $url
     * @return Cluck|null
     */
    public function getCluckFromURL(string $url): ?Cluck
    {
        $query = $this->connection->prepare(
            "select * from clucks where url = ?;"
        );
        if ($query->bind_param("s", $url) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return Cluck::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * checks if a post is replying to another post
     *
     * if it is a reply true is returned else false
     *
     * @param int $id
     * @return bool
     */
    public function isReply(int $id): bool
    {
        $query = $this->connection->prepare(
            "select count(*) as count from replies where thisCluckID = ?;"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return $result->fetch_assoc()["count"] > 0;
            }
        }
        return false;
    }

    /**
     * queries the database for the post this post is replying to
     *
     * if successful the replied post is returned as a new cluck object else null
     *
     * @param int $id
     * @return Cluck|null
     */
    public function getRepliedCluck(int $id): ?Cluck
    {
        $query = $this->connection->prepare(
            "select * from clucks where id = (
                        select replies.replyCluckID
                        from clucks join replies on clucks.id = replies.thisCluckID
                        where clucks.id = ?
                   );"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return Cluck::fromAssoc($result->fetch_assoc());
            }
        }
        return null;
    }

    /**
     * counts the amount of replies on a post
     *
     * @param int $id
     * @return int
     */
    public function getReplyCount(int $id): int
    {
        $query = $this->connection->prepare(
            "select count(*) as count from replies where replyCluckID = ?;"
        );
        if ($query->bind_param("i", $id) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                return $result->fetch_assoc()["count"];
            }
        }
        return 0;
    }

    /**
     * get a page of replies from a post
     *
     * a page is $this->pageLimit amount of replies offset by the amount * page
     *
     * if successful an array of new CLuck objects is returned
     * if the post have no replies an empty array is returned
     *
     * @param int $id
     * @param int $page
     * @return array
     */
    public function getCluckReplies(int $id, int $page): array
    {
        $offset = $page * $this->pageLimit;
        $clucks = [];
        $query = $this->connection->prepare(
            "select clucks.id as id, userID, title, content, url, postDate, lastEdited 
                   from clucks join replies on clucks.id = replies.thisCluckID where replies.replyCluckID = ?
                   order by clucks.postDate desc limit $this->pageLimit offset ?;"
        );
        if ($query->bind_param("ii", $id, $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($cluckData = $result->fetch_assoc()) {
                    array_push($clucks, Cluck::fromAssoc($cluckData));
                }
            }
        }
        return $clucks;
    }

    /**
     * get a page of the latest posts
     *
     * a page is $this->pageLimit amount of replies offset by the amount * page
     *
     * if successful an array of new Cluck objects is returned
     * if there is no posts on the page an empty array is returned
     *
     * @param int $page
     * @return array
     */
    public function getLatestClucks(int $page = 0): array
    {
        $offset = $page * $this->pageLimit;
        $clucks = [];
        $query = $this->connection->prepare(
            "select * from clucks order by postDate desc limit $this->pageLimit offset ?;"
        );
        if ($query->bind_param("i", $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($cluckData = $result->fetch_assoc()) {
                    array_push($clucks, Cluck::fromAssoc($cluckData));
                }
            }
        }
        return $clucks;
    }

    /**
     * get a page of hot posts
     *
     * a hot post is based on how many replies the post have divided by how long time have passed multiplied with a factor of 1_000_000
     *
     * a page is $this->pageLimit amount of replies offset by the amount * page
     *
     * if successful an array of new Cluck objects is returned
     * if there is no posts on the page an empty array is returned
     *
     * @param int $page
     * @return array
     */
    public function getHotClucks(int $page = 0): array
    {
        $offset = $page * $this->pageLimit;
        $clucks = [];
        $query = $this->connection->prepare(
            "select id, userID, title, url, content, postDate, lastEdited, countedReplies.cluckReplies, (countedReplies.cluckReplies / ((now() - postDate) / 1000000)) as heat
                   from clucks join (select replyCluckID, count(*) as cluckReplies from replies group by replyCluckID) countedReplies
                   on clucks.id = countedReplies.replyCluckID
                   order by heat desc limit $this->pageLimit offset ?;"
        );
        if ($query->bind_param("i", $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($cluckData = $result->fetch_assoc()) {
                    array_push($clucks, Cluck::fromAssoc($cluckData));
                }
            }
        }
        return $clucks;
    }

    /**
     * get a page of top posts
     *
     * a top post is purely on how many replies a post have gotten
     *
     * a page is $this->pageLimit amount of replies offset by the amount * page
     *
     * if successful an array of new Cluck objects is returned
     * if there is no posts on the page an empty array is returned
     *
     * @param int $page
     * @return array
     */
    public function getTopClucks(int $page): array
    {
        $offset = $page * $this->pageLimit;
        $clucks = [];
        $query = $this->connection->prepare(
            "select * from clucks join (select replyCluckID, count(*) as count from replies group by replyCluckID) countedReplies
                   on clucks.id = countedReplies.replyCluckID
                   order by count desc limit $this->pageLimit offset ?;"
        );
        if ($query->bind_param("i", $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($cluckData = $result->fetch_assoc()) {
                    array_push($clucks, Cluck::fromAssoc($cluckData));
                }
            }
        }
        return $clucks;
    }

    /**
     * get a page of the users posts (sorted by date)
     *
     * a page is $this->pageLimit amount of replies offset by the amount * page
     *
     * if successful an array of new CLuck objects is returned
     * if there is no posts on the page an empty array is returned
     *
     * @param int $id
     * @param int $page
     * @return array
     */
    public function getUserClucks(int $id, int $page): array
    {
        $offset = $page * $this->pageLimit;
        $clucks = [];
        $query = $this->connection->prepare(
            "select * from clucks where userID = ? 
                order by postDate desc limit $this->pageLimit offset ?;"
        );
        if ($query->bind_param("ii", $id, $offset) && $query->execute()) {
            if (($result = $query->get_result()) && $result->num_rows) {
                while ($cluckData = $result->fetch_assoc()) {
                    array_push($clucks, Cluck::fromAssoc($cluckData));
                }
            }
        }
        return $clucks;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
