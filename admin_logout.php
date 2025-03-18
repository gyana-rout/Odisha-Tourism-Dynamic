<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    session_unset();
    session_destroy();
}
header("Location: admin_login.php");
exit();
