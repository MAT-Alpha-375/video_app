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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Videos</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">

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
            width: fit-content;
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
            max-height: 80%;
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
    <div class="page-container">
        <div class="container mt-4">
            <!-- Search Bar -->
            <form method="GET" action="view.php" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search videos by title, hashtags, or uploader"
                        value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="video-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($video = $result->fetch_assoc()): ?>
                    <div class="video-card">
                        <video src="uploads/<?= htmlspecialchars($video['video_url']) ?>" controls></video>
                        <div class="video-info">
                            <p><strong>Title:</strong> <?= htmlspecialchars($video['title']) ?></p>
                            <p><strong>Uploaded by:</strong> <?= htmlspecialchars($video['username']) ?></p>
                            <p><strong>Hashtags:</strong> <?= htmlspecialchars($video['hashtags']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">No videos found matching your search.</p>
            <?php endif; ?>
        </div>
        <footer>
            <p>&copy; <?= date('Y') ?> VideoApp. All Rights Reserved.</p>
        </footer>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</html>