<?php
session_start();
include "db_conn.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoApp - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Welcome to VideoApp</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Show upload form if the user is logged in -->
            <h4>Upload a video</h4>
            <form action="upload.php" method="post" enctype="multipart/form-data" class="mt-4">
                <div class="mb-3">
                    <label for="title" class="form-label">Video Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter video title" required>
                </div>
                <div class="mb-3">
                    <label for="hashtags" class="form-label">Hashtags</label>
                    <input type="text" name="hashtags" id="hashtags" class="form-control" placeholder="Enter hashtags (comma-separated)">
                </div>
                <div class="mb-3">
                    <label for="my_video" class="form-label">Upload Video</label>
                    <input type="file" name="my_video" id="my_video" class="form-control" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Upload</button>
            </form>
        <?php else: ?>
            <!-- Show guest message if the user is not logged in -->
            <p class="text-center">
                You can <a href="view.php">view videos</a> without logging in.
                Please <a href="login.php">log in</a> or <a href="signup.php">sign up</a> to upload videos.
            </p>
        <?php endif; ?>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</html>