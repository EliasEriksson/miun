<?php

include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/admin.php";

class Manager
{
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli('eliaseriksson.eu', 'eler2006', 'miunstudent', 'miun');
        if ($this->connection->connect_errno !== 0) {
            die("Fel vid anslutning");
        }
    }

    public function installDatabase(): bool
    {
        $sql = "create table admins (username varchar(32) not null , passwordHash char(64), salt char(13));";
        $sql .= "alter table admins add constraint adminsPK primary key (username);";

        
        return $this->connection->multi_query($sql);
    }

    public function addAdmin($username, $password)
    {
        $salt = uniqid();
        $passwordHash = hash("sha256", $password . $salt);
        $sql = "insert into admins values ('$username', '$passwordHash', '$salt');";
        $result = $this->connection->query($sql);
        if ($result) {
            return new Admin($username, $passwordHash, $salt);
        }
        return false;
    }

    public function getAdmin(string $username)
    {
        $sql = "select * from admins where username='$username';";
        $result = $this->connection->query($sql);
        if ($result->num_rows) {
            return Admin::fromAssociativeArray(($result->fetch_assoc()));
        }
        return false;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}

echo "<br><br>";
$manager = new Manager();
//echo $manager->installDatabase()."<br>";
//echo $manager->addAdmin("elias-eriksson", "student")."<br>";
$manager->addAdmin("hello", "hello");
