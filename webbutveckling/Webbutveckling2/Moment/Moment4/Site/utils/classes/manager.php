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

    public function createUser(string $email, string $password): ?User
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $url = uniqid();
        $sql = "insert into users values (default, '$email', '$passwordHash', '$url');";
        if ($result = $this->connection->query($sql)) {
            return new User($this->connection->insert_id, $email, $passwordHash, $url);
        }
        return null;
    }

    public function getUser(int $id): ?User
    {
        $sql = "select * from users where id=$id;";
        if ($result = $this->connection->query($sql)) {
            return User::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function getUserFromEmail(string $email): ?User
    {
        $sql = "select * from users where email='$email';";
        if (($result = $this->connection->query($sql))->num_rows) {
            return User::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function getUserFromURL(string $url): ?User
    {
        $sql = "select * from users where url = '$url';";
        if ($result = $this->connection->query($sql)) {
            return User::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function createUserProfile(int $id, string $firstName, string $lastName, string $avatar, string $description): ?UserProfile
    {
        $sql = "insert into userProfiles values ($id, '$firstName', '$lastName', '$avatar', '$description');";
        if ($result = $this->connection->query($sql)) {
            return new UserProfile($id, $firstName, $lastName, $avatar, $description);
        }
        return null;
    }

    public function updateUserProfile(int $id, string $firstName, string $lastName, string $avatar, string $description): ?UserProfile
    {
        $sql = "
        update userProfiles set firstName = '$firstName', lastName = '$lastName', avatar = '$avatar', description = '$description'
        where userID=$id;";
        if ($result = $this->connection->query($sql)) {
            return $this->getUserProfile($id);
        }
        return null;
    }

    public function getUserProfile(int $id): ?UserProfile
    {
        $sql = "select * from users join userProfiles on users.id = userProfiles.userID where id=$id;";
        if (($result = $this->connection->query($sql))->num_rows) { //TODO do this num_rows where its needed
            return UserProfile::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function createReply(int $thisCluckID, int $replyCluckID): bool
    {
        $sql = "insert into replies values (default, $thisCluckID, $replyCluckID);";
        if ($result = $this->connection->query($sql)) {
            return true;
        }
        return false;
    }

    public function createCluck(int $userID, string $content, int $replyID = 0): ?Cluck
    {
        $url = uniqid();
        $sql = "insert into clucks values (default, $userID, '$content', '$url', now(), null);";
        if ($result = $this->connection->query($sql)) {
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
        return null;
    }

    public function deleteCluck(int $id): bool
    {
        $sql = "delete from clucks where id=$id;";
        if ($result = $this->connection->query($sql)) {
            return true;
        }
        return false;
    }

    public function updateCluck(int $id, string $content): ?Cluck
    {
        $sql = "update clucks set content = '$content', lastEdited = now() where id=$id;";
        if ($result = $this->connection->query($sql)) {
            return $this->getCluck($id);
        }
        return null;
    }

    public function getCluck(int $id): ?Cluck
    {
        $sql = "select * from clucks where id=$id;";
        if ($result = $this->connection->query($sql)) {
            return Cluck::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function getCluckFromURL(string $url): ?Cluck
    {
        $sql = "select * from clucks where url='$url';";
        if (($result = $this->connection->query($sql))->num_rows) {
            return Cluck::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function isReply(int $id): bool
    {
        $sql = "select count(*) as count from replies where thisCluckID=$id;";
        if ($result = $this->connection->query($sql)) {
            return $result->fetch_array()["count"] > 0;
        }
        return false;
    }

    public function getRepliedCluck(int $id): ?Cluck
    {
        $sql = "
        select *
        from clucks
            where id = (
            select replies.replyCluckID
            from clucks join replies on clucks.id = replies.thisCluckID
            where clucks.id = $id
        );";
        if (($result = $this->connection->query($sql))->num_rows) {
            return Cluck::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function getReplyCount(int $id): int
    {
        $sql = "select count(*) as count from replies where replyCluckID = $id;";
        if ($result = $this->connection->query($sql)) {
            return $result->fetch_assoc()["count"];
        }
        return 0;
    }

    public function getCluckReplies(int $id, int $page): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "select * from clucks join replies on clucks.id = replies.thisCluckID where replies.replyCluckID = $id
                order by clucks.postDate desc limit $this->pageLimit offset $offset;";
        $clucks = [];
        if (($result = $this->connection->query($sql))->num_rows) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
            }
        }
        return $clucks;
    }

    public function getLatestClucks(int $page = 0): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "select * from clucks order by postDate desc limit $this->pageLimit offset $offset;";
        $clucks = [];
        if ($result = $this->connection->query($sql)) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
            }
        }
        return $clucks;
    }

    public function getHotClucks(int $page = 0): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "
        select id, userID, url, content, postDate, lastEdited, countedReplies.cluckReplies, (countedReplies.cluckReplies / ((now() - postDate) / 1000000)) as heat
        from clucks join (select replyCluckID, count(*) as cluckReplies from replies group by replyCluckID) countedReplies
        on clucks.id = countedReplies.replyCluckID
        order by heat desc limit $this->pageLimit offset $offset;";
        $clucks = [];
        if (($result = $this->connection->query($sql)) && ($result->num_rows)) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
            }
        }
        return $clucks;
    }

    public function getTopClucks(int $page): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "select * from clucks join (select replyCluckID, count(*) as count from replies group by replyCluckID) countedReplies
                on clucks.id = countedReplies.replyCluckID
                order by count desc limit $this->pageLimit offset $offset;";
        $clucks = [];
        if (($result = $this->connection->query($sql)) && ($result->num_rows)) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
            }
        }
        return $clucks;
    }

    public function getUserClucks(int $id, int $page): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "select * from clucks where userID = $id 
                order by postDate desc limit $this->pageLimit offset $offset;";
        $clucks = [];
        if (($result = $this->connection->query($sql)) && ($result->num_rows)) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
            }
        }
        return $clucks;
    }

    public function getUsersAssoc(int $page): array
    {
        $offset = $page * $this->pageLimit;
        $sql = "select id, email, url, firstName, lastName, avatar, description from users join userProfiles on users.id = userProfiles.userID
                order by id desc limit 10 offset 0;";
        $usersAssoc = [];
        if (($result = $this->connection->query($sql)) && ($result->num_rows)) {
            while ($userData = $result->fetch_assoc()) {
                array_push($usersAssoc, $userData);
            }
        }
        return $usersAssoc;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
