<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (!isset($_SESSION['userId'])) {
    header('location: ../index.php?m=Login to continue');
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['userId'];
$query = "SELECT * FROM post WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 0) {
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$post = mysqli_fetch_array($result);
// Redirecting back if post doesn't belong to user
if ($post['user_id'] !== $user_id) {
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if (isset($_POST['content'])) {
    $content = $_POST['content'];
    $query = "UPDATE post SET `content` = '$content' WHERE id = '$id'";
    mysqli_query($conn, $query);
    header('location: ./profile.php');
    exit();
}

if (isset($_POST['delete'])) {
    $query = "DELETE FROM post WHERE id = '$id'";
    mysqli_query($conn, $query);
    header('location: ./profile.php');
    exit();
}
?>

<h3>Post id #<?php echo $post['id'] ?></h3>
<form action="#" method="post">
    <textarea required placeholder="Update post..." style="resize: none; padding: 7px;" name="content" id="content" cols="52" rows="5"><?php echo $post['content'] ?></textarea>
    <button name="submit" style="font-size: 18px;vertical-align: top;margin-left: 15px;" type="submit">Update</button>
</form>
<form action="#" method="post">
    <input style="color: red;" type="submit" name="delete" value="Confirm delete">
</form>