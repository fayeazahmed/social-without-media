<?php
include_once '../includes/header.php';
include_once '../includes/db.php';
?>

<form action="#" method="get" style="text-align: center; margin-bottom: 30px">
    <label style="font-size: 20px; margin-right: 25px;" for="keyword">Search for users:</label>
    <input type="text" name="keyword" id="keyword" placeholder="Enter a name or email">
    <input type="submit" hidden>
</form>

<?php
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $query = "SELECT id, full_name, email FROM user WHERE email LIKE '%$keyword%' OR full_name LIKE '%$keyword%' ";
    $result = mysqli_query($conn, $query) ?>

    <?php if (mysqli_num_rows($result) === 0) : ?>
        <h3>No user found with matching query</h3>
    <?php else : ?>
        <table style="margin: auto;">
            <tr>
                <td style="font-size: 19px;">Name</td>
                <td style="padding-left: 10px; font-size: 19px;"><i>Email</i></td>
            </tr>
            <?php while ($row = mysqli_fetch_array($result)) : ?>
                <tr>
                    <td style="font-size: 19px;"><a href="./profile.php?id=<?php echo $row['id'] ?>"><b><?php echo $row['full_name'] ?></b></a></td>
                    <td style="padding-left: 10px; font-size: 19px;"><i><?php echo $row['email'] ?></i></td>
                </tr>
            <?php endwhile ?>
        </table>
    <?php endif ?>
<?php

}
include_once '../includes/footer.php';
