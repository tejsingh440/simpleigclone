<?php
session_start();
include('db.php');


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "select id from users where username = '$username'";
$result = $conn->query($sql);
$result = $result->fetch_assoc();
$user_id = $result['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search_query'];

    $sql = "SELECT * FROM users WHERE username LIKE '%$search_query%' AND username != '$username'";
    $result = mysqli_query($conn, $sql);

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['follow'])) {
    $follow_id = $_GET['follow'];
    $sql = "INSERT INTO followers (follower_id, following_id) VALUES ('$user_id', '$follow_id')";
    mysqli_query($conn, $sql);
    // Redirect back to search page after following
    header('Location: search.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['unfollow'])) {
    $unfollow_id = $_GET['unfollow'];
    $sql = "DELETE FROM followers WHERE follower_id='$user_id' AND following_id='$unfollow_id'";
    mysqli_query($conn, $sql);
    // Redirect back to search page after unfollowing
    header('Location: search.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Users - Instagram Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Search Users</h1>
    <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
    <form action="search.php" method="post">
        <input type="text" name="search_query" placeholder="Search for users..." required>
        <button type="submit">Search</button>
    </form>
    <h2>Search Results</h2>
    <?php if (isset($users) && !empty($users)): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="profile.php?username=<?php echo $user['username']; ?>"><?php echo $user['username']; ?></a>
                    <?php
                    $follow_check_sql = "SELECT * FROM followers WHERE follower_id='$user_id' AND following_id='{$user['id']}'";
                    $follow_check_result = mysqli_query($conn, $follow_check_sql);
                    if (mysqli_num_rows($follow_check_result) > 0) {
                        echo " - Following ";
                        echo "<a href='?unfollow={$user['id']}'>Unfollow</a>";
                    } else {
                        echo " - <a href='?follow={$user['id']}'>Follow</a>";
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found</p>
    <?php endif; ?>
</body>
</html>
