<?php
require 'config.php';
session_destroy();
setcookie('user_id', '', time() - 3600, "/");
header("Location: index.php");
exit;
?>