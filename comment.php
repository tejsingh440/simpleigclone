<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    $sql = "INSERT INTO comments (user_id, post_id, comment) VALUES ('$user_id', '$post_id', '$comment')";
    mysqli_query($conn, $sql);
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