<?php
include_once __DIR__ . "/../utils/config.php";
include_once __DIR__ . "/../utils/functions.php";
include_once __DIR__ . "/../utils/classes/userLoginForm.php";

$userLoginForm = new UserLoginForm();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($user = $userLoginForm->validate()) {
        if ($userProfile = $user->getProfile()) {
            $_SESSION["userProfile"] = $userProfile;
            redirect("$rootURL/");
        } else {
            redirect("$rootURL/Profiles/Profile/");
        }
    }
}

if (isset($_SESSION["user"])) {
//    redirect();
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php
    include "../templates/head.php";
    ?>
    <title>Cluck | Login</title>
</head>
<body>
<?php
include "../templates/header.php";

echo $userLoginForm->toHTML();

include "../templates/footer.php";
?>
</body>
</html>