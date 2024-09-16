<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: index.html");
    exit();
}
include('config.php');
$employer_id = $_SESSION['user_id'];

// Fetch all job posts from this employer
$result = $conn->query("SELECT * FROM jobs WHERE employer_id = $employer_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Posts</title>
</head>
<body>
<a href="employer_dashboard.php">Return</a>
    <h1>My Job Posts</h1>
    <div id="job-list">
        <?php
        while ($job = $result->fetch_assoc()) {
            $job_id = $job['id'];

            // Fetch feedback and ratings for this job post
            $feedback_result = $conn->query("SELECT * FROM feedback WHERE job_id = $job_id");
            $rating_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback WHERE job_id = $job_id");
            $average_rating = $rating_result->fetch_assoc()['avg_rating'] ?? 0;

            echo "<div class='job'>";
            echo "<h2>" . $job['title'] . "</h2>";
            echo "<p>" . $job['description'] . "</p>";
            echo "<p>Average Rating: " . round($average_rating, 1) . "</p>";

            echo "<h4>Reviews/Feedback:</h4>";
            if ($feedback_result->num_rows > 0) {
                while ($feedback = $feedback_result->fetch_assoc()) {
                    echo "<p>Rating: " . $feedback['rating'] . " stars</p>";
                    echo "<p>Comment: " . $feedback['comment'] . "</p><hr>";
                }
            } else {
                echo "<p>No feedback yet.</p>";
            }

            // Edit and Delete buttons
            echo "<a href='edit_job.php?job_id=" . $job_id . "'>Edit</a> | ";
            echo "<a href='delete_job.php?job_id=" . $job_id . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>";

            echo "</div><hr>";
        }
        ?>
    </div>
</body>
</html>
