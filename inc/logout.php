<?php
// logout by destroying the session and unsetting all variables associated with the previously logged in user
session_start();
session_unset();
session_destroy();
header('Location: ../index.php');