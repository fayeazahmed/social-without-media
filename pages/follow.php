<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

if (!isset($_SESSION['userId'])) {
    header('location: ../index.php?m=Login to continue');
    exit();
}

$followed_user = $_GET['u'];
$follower_user = $_SESSION['userId'];

// Checking if it is a request to follow or unfollow
$query = ($_GET['action'] === 'follow' ?
    "INSERT INTO follow(`follower_user`, `followed_user`) VALUES ('$follower_user','$followed_user');" :
    "DELETE FROM follow WHERE follower_user = '$follower_user' AND followed_user = '$followed_user';");
mysqli_query($conn, $query);
header('location: ' . $_SERVER['HTTP_REFERER']);
exit();
