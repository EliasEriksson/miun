<?php
/**
 * logs out a logged in user and redirects to home
 */
session_start();
session_destroy();
header("location: ../../");
