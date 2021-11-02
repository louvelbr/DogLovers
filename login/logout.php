<?php 
session_start();
$_SESSION["logout"] = "success";
header('Location: ./login.php');
?>