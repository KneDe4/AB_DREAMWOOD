<?php
session_start();
require '../conf.php';
$uid = $_SESSION['user_id'];
$user = $mysqli->query("SELECT * FROM users WHERE id = '$uid'")->fetch_assoc();
$ac = $_GET['d'];
if ($ac == 'chand') {
    $pa = $_GET['pas'];
    $mysqli->query("UPDATE users SET password = '$pa' WHERE id = $uid");
}