<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

// Redirecting if not authenticated
if (!isset($_SESSION['userId'])) {
    header('location: ../index.php?m=Login to continue');
    exit();
}
$id = $_SESSION['userId'];
$query = "SELECT full_name, bio FROM user WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_array($result);

// Insert post if form submitted 
if (isset($_POST['submit'])) {
    $content = $_POST['content'];
    $query = "INSERT INTO `post`(`user_id`, `content`) VALUES ('$id', '$content')";
    $result = mysqli_query($conn, $query);
}
?>

<!-- User info and post form -->
<table style="margin: auto; margin-bottom: 30px;">
    <tr>
        <td style="vertical-align: top;">
            <h2 style="margin: 0;"><?php echo $user['full_name'] ?></h2>
            <h4><?php echo $user['bio'] ?></h4>
        </td>
        <td style="padding-left: 15px;">
            <form action="#" method="post">
                <textarea required placeholder="Write something and post..." style="resize: none; padding: 7px;" name="content" id="content" cols="52" rows="5"></textarea>
                <button name="submit" style="font-size: 18px;vertical-align: top;margin-left: 15px;" type="submit">POST</button>
            </form>
        </td>
    </tr>
</table>

<?php
// Fetch posts
$query = "SELECT post.id, content, full_name, user.id as user, created_at, COUNT(likes.post_id) as likes
        FROM post 
        JOIN user 
        ON user.id = post.user_id 
        LEFT JOIN likes ON likes.post_id = post.id
        WHERE post.user_id 
        IN (SELECT followed_user FROM follow WHERE follower_user = $id)
        OR post.user_id = $id
        GROUP BY post.id
        ORDER BY post.id DESC; ";
$result = mysqli_query($conn, $query);
?>

<!-- Render posts -->
<div>
    <?php if (mysqli_num_rows($result) === 0) : ?>
        <h3 style="text-align: center;">Follow some users to see their posts</h3>
    <?php else : ?>
        <?php while ($row = mysqli_fetch_array($result)) : ?>
            <table style="border-bottom: 1px solid darkolivegreen; margin: auto; margin-bottom: 20px; ">
                <thead>
                    <th style="text-align: left;"><a href="./profile.php?id=<?php echo $row['user'] ?>"><u><?php echo $row['full_name'] ?></u></a></th>
                    <th><?php echo $row['created_at'] ?></th>
                </thead>
                <tr>
                    <td style="width: 420px;">
                        <p><?php echo $row['content'] ?></p>
                        <?php
                        // Check if post is liked by user
                        $qry = "SELECT * FROM likes WHERE post_id = '" . $row['id'] . "' AND user_id = '$id' LIMIT 1";
                        $res = mysqli_query($conn, $qry);
                        ?>
                        <?php
                        if (mysqli_num_rows($res) === 0)
                            echo "<a style='font-size: 23px;' href='./like.php?post=" . $row['id'] . "&action=like'>LIKE " . $row['likes'] . "</a>";
                        else
                            echo "<a style='font-size: 23px; color: red' href='./like.php?post=" . $row['id'] . "&action=dislike'>LIKE " . $row['likes'] . "</a>" ?>
                    </td>
                </tr>
            </table>
        <?php endwhile; ?>
    <?php endif ?>
</div>

<?php
include_once '../includes/footer.php';
