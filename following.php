<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Fetch users followed by the logged-in user
$sql = "SELECT users.username
        FROM followers
        INNER JOIN users ON followers.following_id = users.id
        WHERE followers.follower_id = (SELECT id FROM users WHERE username = '$username')";
$result = mysqli_query($conn, $sql);
$following = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Following</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Users You're Following</h1>
    <ul>
        <?php foreach ($following as $user): ?>
            <li><?php echo $user['username']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
