<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $caption = $_POST['caption'];
    $user_id = $_SESSION['user_id'];
    $image_path = 'images/' . $_FILES['image']['name'];

    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        $sql = "INSERT INTO posts (user_id, image_url, caption) VALUES ('$user_id', '$image_path', '$caption')";
        mysqli_query($conn, $sql);
        header('Location: index.php');
        exit();
    } else {
        $error = "Error uploading image";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload - Instagram Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Upload Image</h1>
    <?php if (isset($error)): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <textarea name="caption" placeholder="Write a caption..." required></textarea>
        <button type="submit">Upload</button>
    </form>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
