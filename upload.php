<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db_conn.php";

if (isset($_POST['submit']) && isset($_FILES['my_video'])) {
    $title = trim($_POST['title']);
    $hashtags = trim($_POST['hashtags']);
    $uploaded_by = $_SESSION['user_id'];

    $video_name = $_FILES['my_video']['name'];
    $tmp_name = $_FILES['my_video']['tmp_name'];
    $error = $_FILES['my_video']['error'];

    if ($error === 0) {
        $video_ex = strtolower(pathinfo($video_name, PATHINFO_EXTENSION));
        $allowed_exs = ["mp4", "webm", "avi", "flv"];

        if (in_array($video_ex, $allowed_exs)) {
            $new_video_name = uniqid("video-", true) . '.' . $video_ex;
            $video_upload_path = 'uploads/' . $new_video_name;
            move_uploaded_file($tmp_name, $video_upload_path);

            $sql = "INSERT INTO videos (video_url, title, hashtags, uploaded_by) VALUES ('$new_video_name', '$title', '$hashtags', '$uploaded_by')";
            $conn->query($sql);

            header("Location: view.php");
            exit();
        } else {
            $error = "Invalid file type";
        }
    } else {
        $error = "An error occurred during upload";
    }
}
header("Location: index.php?error=" . urlencode($error));
