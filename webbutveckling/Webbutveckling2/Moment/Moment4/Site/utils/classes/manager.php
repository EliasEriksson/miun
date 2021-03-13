<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/cluck.php";
include_once __DIR__ . "/user.php";
include_once __DIR__ . "/userProfile.php";


class Manager
{
    private $connection;
    private $pageLimit;

    public function __construct(int $pageLimit = 10)
    {
        // this password is no longer considered secure anyway
        $this->connection = new mysqli(
            "eliaseriksson.eu",
            "web2mom4",
            "7gc4tf7gf",
            "web2mom4"
        );
        if ($this->connection->connect_errno !== 0) {
            die("Could not connect. Error: " . $this->connection->connect_errno . " " . $this->connection->connect_error);
        }
        $this->pageLimit = $pageLimit;
    }

    private function stripTags(string $string): string
    {
        return strip_tags($string, "<a>");
    }

    private function checkDuplicateKey(string $key): bool
    {
        if ($this->connection->errno === 1062) {
            if (preg_match("/^Duplicate\sentry\s'[^ ]+'\sfor\skey\s'$key'$/", $this->connection->error)) {
                return true;
            }
        }
        return false;
    }

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

//$manager = new Manager();
//echo "starting query<br><br>";
//$cluck = $manager->getRepliedCluck(2);
//echo $cluck->getID();
//echo "ended query<br><br>";
//var_dump($cluck);