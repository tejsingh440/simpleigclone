<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $post_id = $_POST['post_id'];

    // Check if the user has already liked the post
    $check_like_sql = "SELECT * FROM likes WHERE user_id = (SELECT id FROM users WHERE username = '$username') AND post_id = '$post_id'";
    $check_like_result = mysqli_query($conn, $check_like_sql);

    if (mysqli_num_rows($check_like_result) > 0) {
        // User has already liked the post, unlike it
        $delete_like_sql = "DELETE FROM likes WHERE user_id = (SELECT id FROM users WHERE username = '$username') AND post_id = '$post_id'";
        mysqli_query($conn, $delete_like_sql);
    } else {
        // User has not liked the post, like it
        $insert_like_sql = "INSERT INTO likes (user_id, post_id) VALUES ((SELECT id FROM users WHERE username = '$username'), '$post_id')";
        mysqli_query($conn, $insert_like_sql);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<body>
    <script>
        window.history.back();
    </script>
</body>
</html>
