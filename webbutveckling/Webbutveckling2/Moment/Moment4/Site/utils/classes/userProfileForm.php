<?php
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../functions.php";
include_once __DIR__ . "/form.php";


/**
 * Class UserProfileForm
 *
 * abstract class intended to be subclassed from forms dealing with userProfiles.
 */
abstract class UserProfileForm extends Form {

    /**
     * validation for avatars for forms dealing with userProfiles
     *
     * if an avatar is uploaded a directory for the file and a filename is generated.
     * the file is then moved to the newly created directory.
     *
     * @param User $user
     * @return string
     */
    protected function validateAvatar(User $user): string
    {
        if (isset($_FILES["avatar"]) && $extension = getExtensionFromMIME($_FILES["avatar"]["type"])) {
            $url = $user->getUrl();

            $file = "avatar$extension";
            $webDirectory = "/writeable/web2mom4/media/avatars/$url/";
            $fileDirectory = $GLOBALS["writeDirectory"] . "$webDirectory";
            $webPath = "$webDirectory$file";
            $filePath = "$fileDirectory$file";

            if (!is_dir($fileDirectory)) {
                mkdir($fileDirectory, 0777, true);
            }
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $filePath);
        } else {
            $webPath = "/writeable/web2mom4/media/avatars/defaultAvatars/default.svg";
        }
        return $webPath;
    }
}