<?php
include_once '../includes/header.php';
include_once '../includes/db.php';

if (isset($_SESSION['userId'])) {
    header('location: ./pages/home.php');
    exit();
}

if (isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $bio = $_POST['bio'];
    $query = "SELECT * FROM user WHERE email = '$email' ";
    $result = mysqli_query($conn, $query);

    // Validation
    if (mysqli_num_rows($result) !== 0) {
        header('location: ./register.php?m=Email already in use');
        exit;
    }
    if (strpos($email, '@') === false) {
        header('location: ./register.php?m=Invalid email');
        exit;
    }
    if (strlen($password) < 6) {
        header('location: ./register.php?m=Password must be at least 6 characters');
        exit;
    }
    if ($password !== $password2) {
        header('location: ./register.php?m=Passwords do not match');
        exit;
    }

    $hashedPw = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (`email`, `full_name`, `password`, `bio`) VALUES ('$email', '$name', '$hashedPw', '$bio')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION['userId'] = mysqli_insert_id($conn);
        header('location: ./home.php');
        exit;
    } else
        header('location: ./register.php?m=Something went wrong');
}

?>

<form action="#" method="post" style="text-align: center;">
    <input required placeholder="Full name" type="text" name="name"><br>
    <input required placeholder="Your email" type="text" name="email"><br>
    <input required placeholder="Your password" type="password" name="password"><br>
    <input required placeholder="Password (again)" type="password" name="password2"><br>
    <input placeholder="Bio" type="text" name="bio"><br>
    <a href="../">Already have an account?</a>
    <input type="submit" value="Register" name="submit">
    <?php if (isset($_GET['m'])) : ?>
        <p style="color: red; font-size: 20px;"><?php echo ($_GET['m']) ?></p>
    <?php endif; ?>
</form>

<?php
include_once '../includes/footer.php';
