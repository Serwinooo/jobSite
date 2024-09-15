<?php
// Start the session at the very beginning of the script
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("
        <a href='index.html'>Return</a>
        <a href='register.html'>Register</a>
        <div>
        <h3>Access denied. Please log in to continue.</h3>
        </div>
        ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        .star-rating {
            display: flex;
            direction: row-reverse;
            justify-content: center;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            font-size: 2em;
            color: #ddd;
            cursor: pointer;
        }
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f5c518;
        }
    </style>
    <script>
        function likeJob(jobId) {
            fetch('like_job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `job_id=${jobId}`
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`likes-count-${jobId}`).innerText = `Likes: ${data.total_likes}`;
                const button = document.getElementById(`like-btn-${jobId}`);
                button.innerText = (data.message === 'Job liked!') ? 'Unlike' : 'Like';
            });
        }

        function submitFeedback(jobId) {
            const rating = document.querySelector(`input[name="rating-${jobId}"]:checked`).value;
            const comment = document.getElementById(`comment-${jobId}`).value;

            fetch('submit_feedback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `job_id=${jobId}&rating=${rating}&comment=${encodeURIComponent(comment)}`
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`average-rating-${jobId}`).innerText = `Average Rating: ${data.average_rating}`;
                alert(data.message);
            });
        }
    </script>
</head>
<body>
    <a href="employee_dashboard.php">Return</a>
    <h1>Available Jobs</h1>
    <div id="job-list">
        <?php
        // Include the config file for database connection
        include('config.php');

        // Fetch all job listings
        $result = $conn->query("SELECT * FROM jobs");

        while ($job = $result->fetch_assoc()) {
            $job_id = $job['id'];
            $employee_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            if ($employee_id) {
                // Fetch if the user has already liked the job
                $like_result = $conn->query("SELECT liked FROM likes WHERE job_id = $job_id AND employee_id = $employee_id");
                $liked = ($like_result && $like_result->num_rows > 0) ? $like_result->fetch_assoc()['liked'] : 0;

                // Fetch total number of likes
                $likes_result = $conn->query("SELECT COUNT(*) AS total_likes FROM likes WHERE job_id = $job_id AND liked = 1");
                $total_likes = $likes_result->fetch_assoc()['total_likes'];

                // Fetch average rating
                $rating_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback WHERE job_id = $job_id");
                $average_rating = $rating_result->fetch_assoc()['avg_rating'] ?? 0;

                echo "<div class='job'>";
                echo "<h2>" . $job['title'] . "</h2>";
                echo "<p>" . $job['description'] . "</p>";

                // Like/Unlike button
                $button_text = ($liked) ? "Unlike" : "Like";
                echo "<button id='like-btn-" . $job['id'] . "' onclick='likeJob(" . $job['id'] . ")'>$button_text</button>";
                echo "<span id='likes-count-" . $job['id'] . "'>Likes: " . $total_likes . "</span>";

                // Star Rating system and Comment form
                echo "<div class='star-rating'>";
                for ($i = 5; $i >= 1; $i--) {
                    echo "<input type='radio' id='star-$i-{$job['id']}' name='rating-{$job['id']}' value='$i' />";
                    echo "<label for='star-$i-{$job['id']}'>★</label>";
                }
                echo "</div>";

                // Comment text area
                echo "<textarea id='comment-{$job['id']}' placeholder='Leave a comment'></textarea>";

                // Submit feedback button
                echo "<button onclick='submitFeedback(" . $job['id'] . ")'>Submit Feedback</button>";

                // Display the average rating
                echo "<p id='average-rating-{$job['id']}'>Average Rating: " . round($average_rating, 1) . "</p>";
                echo "</div>";
            } else {
                echo "Error: User ID is missing.";
            }
        }
        ?>
    </div>
</body>
</html>
