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

    public function deleteUser(int $id): bool
    {
        $sql = "delete from users where id=$id;";
        if ($result = $this->connection->query($sql)) {
            return true;
        }
        return false;
    }

    public function updateUser(string $email, string $password): ?User
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "update users set passwordHash = '$passwordHash' where email = '$email';";
        if ($result = $this->connection->query($sql)) {
            return $this->getUser($this->connection->insert_id);
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
        $sql = "";
        if ($result = $this->connection->query($sql)) {
            return true;
        }
        return false;
    }

    public function updateCluck(int $id, string $content)
    {
        $sql = "update clucks set content = '$content' where id=$id;";
    }

    public function getLatestCluck(): ?Cluck
    {
        $sql = "select * from clucks order by postDate desc limit 1;";
        if ($result = $this->connection->query($sql)) {
            return Cluck::fromAssoc($result->fetch_assoc());
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
        if ($result = $this->connection->query($sql)) {
            return Cluck::fromAssoc($result->fetch_assoc());
        }
        return null;
    }

    public function getRepliedCluck(int $id): ?Cluck
    {
        $sql = "
        select *
        from clucks
            where id = (
            select replies.id
            from clucks join replies on clucks.id = replies.thisCluckID
            where clucks.id = $id
        );";

        if ($result = $this->connection->query($sql)) {
            return Cluck::fromAssoc($result->fetch_assoc());
        }
        return null;
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

    public function getHotClucks(int $offset = 0): array
    {
        $sql = "
        select id, userID, content, postDate, lastEdited, countedReplies.cluckReplies, (countedReplies.cluckReplies / ((now() - postDate) / 1000000)) as heat
        from clucks join (select replyCluckID, count(*) as cluckReplies from replies group by replyCluckID) countedReplies
        on clucks.id = countedReplies.replyCluckID
        order by heat desc limit $this->pageLimit offset $offset;";

        $this->connection->query($sql);
        $clucks = [];
        if ($result = $this->connection->query($sql)) {
            while ($cluckData = $result->fetch_assoc()) {
                array_push($clucks, Cluck::fromAssoc($cluckData));
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
//var_dump($manager->getHotClucks());
