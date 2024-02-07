<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch followers of the logged-in user
$sql = "SELECT users.username
        FROM followers
        INNER JOIN users ON followers.follower_id = users.id
        WHERE followers.following_id = (SELECT id FROM users WHERE username = '$username')";
$result = mysqli_query($conn, $sql);
$followers = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Followers</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Your Followers</h1>
    <ul>
        <?php foreach ($followers as $follower): ?>
            <li><?php echo $follower['username']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
