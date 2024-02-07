<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['username'])) {
    header('Location: index.php');
    exit();
}

$profile_username = $_GET['username'];

$username = $_SESSION['username'];

// Fetch user information
$user_sql = "SELECT * FROM users WHERE username='$profile_username'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Fetch posts by the user
$post_sql = "SELECT posts.*, COUNT(likes.id) AS like_count
             FROM posts
             LEFT JOIN likes ON posts.id = likes.post_id
             WHERE posts.user_id = '{$user['id']}'
             GROUP BY posts.id
             ORDER BY posts.created_at DESC";
$post_result = mysqli_query($conn, $post_sql);
$posts = mysqli_fetch_all($post_result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?php echo $profile_username; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Profile - <?php echo $profile_username; ?></h1>
    <a href="index.php">Home</a> | <a href="logout.php">Logout</a>
    <h2>Posts by <?php echo $profile_username; ?></h2>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <img style="height:auto;width:200px;" src="<?php echo $post['image_url']; ?>" alt="Post Image">
            <p><?php echo $post['caption']; ?></p>
            <p>Likes: <?php echo $post['like_count']; ?></p>
            <div id="comments_<?php echo $post['id']; ?>">
                <?php
                // Fetch comments for the post
                $comment_sql = "SELECT comments.*, users.username AS commenter_username
                                FROM comments
                                INNER JOIN users ON comments.user_id = users.id
                                WHERE comments.post_id = '{$post['id']}'";
                $comment_result = mysqli_query($conn, $comment_sql);
                $comments = mysqli_fetch_all($comment_result, MYSQLI_ASSOC);
                foreach ($comments as $comment) {
                    echo "<p>{$comment['commenter_username']}: {$comment['comment']}</p>";
                }
                ?>
            </div>
            <form action="like.php" method="post">
            <?php 
                $post_id = $post['id'];
                 // Check if the user has already liked the post
                 $check_like_sql = "SELECT * FROM likes WHERE user_id = (SELECT id FROM users WHERE username = '$username') AND post_id = '$post_id'";
                 $check_like_result = mysqli_query($conn, $check_like_sql);
                ?>
                <?php if(mysqli_num_rows($check_like_result) > 0): ?>
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <button type="submit">Unlike</button>
                <?php else : ?>
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <button type="submit">Like</button>
                <?php endif; ?>
            </form>
            <form action="comment.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <textarea name="comment" placeholder="Write a comment..." required></textarea>
                <button type="submit">Comment</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
