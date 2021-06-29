<?php
include_once 'includes/header.php';
include_once 'includes/db.php';

if (isset($_SESSION['userId'])) {
    header('location: ./pages/home.php');
    exit();
}

// Validate and login
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "SELECT * FROM user WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        header('location: ./index.php?m=Email does not exist');
        exit;
    }

    $row = mysqli_fetch_array($result);
    if (password_verify($password, $row['password'])) {
        $_SESSION['userId'] = $row['id'];
        header('location: ./pages/home.php');
        exit;
    } else {
        header('location: ./index.php?m=Incorrect password');
        exit;
    }
}

?>
<h3>This is a social media platform without any media. Post content and follow others to see theirs. Login to continue - </h3>

<!-- Login form -->
<form action="#" method="post" style="text-align: center;">
    <input required placeholder="Your email" type="text" name="email"><br>
    <input required placeholder="Your password" type="password" name="password"><br>
    <a href="pages/register.php">Create an account</a>
    <input style="margin-left: 77px;" type="submit" value="Login" name="submit">
    <?php if (isset($_GET['m'])) : ?>
        <p style="color: red; font-size: 20px;"><?php echo ($_GET['m']) ?></p>
    <?php endif; ?>
</form>
<?php
include_once 'includes/header.php';
