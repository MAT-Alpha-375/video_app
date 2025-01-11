<?php
session_start();
include "db_conn.php";

// Fetch the search term if it exists
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Base SQL query
$sqlQry = "SELECT videos.*, users.username 
        FROM videos 
        LEFT JOIN users ON videos.uploaded_by = users.id";

// Add search condition if a search term is provided
if ($search !== "") {
    $sqlQry .= " WHERE videos.title LIKE ? 
              OR videos.hashtags LIKE ? 
              OR users.username LIKE ?";
    $sqlQry .= " ORDER BY videos.id DESC";
    $stmt = $conn->prepare($sqlQry);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sqlQry .= " ORDER BY videos.id DESC";
    $result = $conn->query($sqlQry);
}

// Handle like/unlike
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_video_id'])) {
    if (isset($_SESSION['user_id'])) {
        $videoId = intval($_POST['like_video_id']);
        $userId = intval($_SESSION['user_id']);

        // Check if the user already liked this video
        $checkLikeQry = "SELECT id FROM likes WHERE video_id = ? AND user_id = ?";
        $stmt = $conn->prepare($checkLikeQry);
        $stmt->bind_param("ii", $videoId, $userId);
        $stmt->execute();
        $likeResult = $stmt->get_result();

        if ($likeResult->num_rows > 0) {
            // User already liked the video, so remove the like (unlike)
            $deleteLikeQry = "DELETE FROM likes WHERE video_id = ? AND user_id = ?";
            $stmt = $conn->prepare($deleteLikeQry);
            $stmt->bind_param("ii", $videoId, $userId);
            $stmt->execute();
        } else {
            // User liked the video
            $insertLikeQry = "INSERT INTO likes (video_id, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insertLikeQry);
            $stmt->bind_param("ii", $videoId, $userId);
            $stmt->execute();
        }
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_video_id'])) {
    if (isset($_SESSION['user_id'])) {
        $videoId = intval($_POST['comment_video_id']);
        $userId = intval($_SESSION['user_id']);
        $comment = htmlspecialchars(trim($_POST['comment']));

        if (!empty($comment)) {
            $insertCommentQry = "INSERT INTO comments (video_id, user_id, comment) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertCommentQry);
            $stmt->bind_param("iis", $videoId, $userId, $comment);
            $stmt->execute();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .video-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            scroll-snap-type: y mandatory;
            overflow-y: auto;
            height: calc(100vh - 56px);
            /*---- Adjust height for navbar */
            width: 100%;
            padding: 20px;
            gap: 20px;
            scroll-behavior: smooth;
        }

        /* Scrollbar styling */
        .video-container::-webkit-scrollbar {
            width: 8px;
            /* Width of the scrollbar */
        }

        .video-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Background of the scrollbar track */
            border-radius: 10px;
            /* Rounded corners for the track */
        }

        .video-container::-webkit-scrollbar-thumb {
            background: #007bff;
            /* Color of the scrollbar thumb */
            border-radius: 10px;
            /* Rounded corners for the thumb */
            border: 2px solid #f1f1f1;
            /* Add a border to create spacing */
        }

        .video-container::-webkit-scrollbar-thumb:hover {
            background: #0056b3;
            /* Change thumb color on hover */
        }

        .video-container {
            scrollbar-width: thin;
            /* Thin scrollbar */
            scrollbar-color: #007bff #f1f1f1;
            /* Thumb color and track color */
        }

        .video-card {
            display: flex;
            flex-direction: row;
            /* Aligns video and stats side by side */
            justify-content: space-between;
            align-items: flex-start;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 900px;
            /* Wider to accommodate both sections */
            width: 100%;
            gap: 20px;
        }

        .video-section {
            flex: 2;
            /* Take more space for the video and details */
        }

        .stats-section {
            flex: 1;
            /* Smaller space for stats */
            text-align: center;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-section h5 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .stats-section p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .video-card {
            scroll-snap-align: start;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            height: calc(100vh - 6rem);
        }

        .video-card video {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
            height: inherit;
            max-height: 60%;
        }

        .video-info {
            border-radius: 10px;
            padding: 3px;
            text-align: center;
            box-shadow: 1px 1px 8px black;
        }

        .video-info p {
            margin: 0;
        }

        footer {
            margin-top: auto;
            padding: 10px 0;
            text-align: center;
            background-color: #e9ecef;
        }

        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Search Bar -->
        <form method="GET" action="view.php" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search videos by title, hashtags, or uploader" value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <div class="video-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($video = $result->fetch_assoc()): ?>
                    <div class="video-card row d-flex justify-content-between" style="width: 100%; margin: 20px 0;">
                        <!-- Video Section -->
                        <div class="video-section col-lg-6 col-12">
                            <video src="uploads/<?= htmlspecialchars($video['video_url']) ?>" controls style="width: 100%; border-radius: 10px;"></video>
                            <div class="video-info mt-3">
                                <p><strong>Title:</strong> <?= htmlspecialchars($video['title']) ?></p>
                                <p><strong>Uploaded by:</strong> <?= htmlspecialchars($video['username']) ?></p>
                                <p><strong>Hashtags:</strong> <?= htmlspecialchars($video['hashtags']) ?></p>
                            </div>

                        </div>

                        <!-- Stats and Comment Section -->
                        <div class="stats-section col-lg-6 col-12">
                            <div class="p-3" style="background: #f8f9fa; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <!-- Statistics -->
                                <h5 class="text-center">Statistics</h5>
                                <p><strong>Likes:</strong>
                                    <?php
                                    $likeCountQry = "SELECT COUNT(*) AS total_likes FROM likes WHERE video_id = ?";
                                    $stmt = $conn->prepare($likeCountQry);
                                    $stmt->bind_param("i", $video['id']);
                                    $stmt->execute();
                                    $likeCount = $stmt->get_result()->fetch_assoc();
                                    echo $likeCount['total_likes'];
                                    ?>
                                </p>
                                <p><strong>Comments:</strong>
                                    <?php
                                    $commentCountQry = "SELECT COUNT(*) AS total_comments FROM comments WHERE video_id = ?";
                                    $stmt = $conn->prepare($commentCountQry);
                                    $stmt->bind_param("i", $video['id']);
                                    $stmt->execute();
                                    $commentCount = $stmt->get_result()->fetch_assoc();
                                    echo $commentCount['total_comments'];
                                    ?>
                                </p>

                                <!-- Like Button -->
                                <form method="POST" action="view.php">
                                    <input type="hidden" name="like_video_id" value="<?= htmlspecialchars($video['id']) ?>">
                                    <button class="btn btn-info btn-sm w-100 mt-2" type="submit">
                                        <?php
                                        $likeCheckQry = "SELECT id FROM likes WHERE video_id = ? AND user_id = ?";
                                        $stmt = $conn->prepare($likeCheckQry);
                                        $stmt->bind_param("ii", $video['id'], $_SESSION['user_id']);
                                        $stmt->execute();
                                        $likeCheck = $stmt->get_result();
                                        if ($likeCheck->num_rows > 0) {
                                            echo "Unlike";
                                        } else {
                                            echo "Like";
                                        }
                                        ?>
                                    </button>
                                </form>

                                <!-- Comment Form -->
                                <form method="POST" action="view.php">
                                    <textarea name="comment" class="form-control mt-3" placeholder="Add a comment..." rows="3"></textarea>
                                    <input type="hidden" name="comment_video_id" value="<?= htmlspecialchars($video['id']) ?>">
                                    <button class="btn btn-primary btn-sm w-100 mt-2" type="submit">Post Comment</button>
                                </form>
                            </div>
                            <!-- Delete Button -->
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $video['uploaded_by']): ?>
                                <form method="POST" action="view.php" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                    <input type="hidden" name="delete_video_id" value="<?= htmlspecialchars($video['id']) ?>">
                                    <input type="hidden" name="video_url" value="<?= htmlspecialchars($video['video_url']) ?>">
                                    <button class="btn btn-danger btn-sm w-100 mt-3" type="submit">Delete Video</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>


                <?php endwhile; ?>

            <?php else: ?>
                <p class="text-center text-muted">No videos found matching your search.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> VideoApp. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>