<?php

session_start();
//echo "<script>alert('Are you sure your want to logout?');</script>";
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>