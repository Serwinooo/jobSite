<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: index.html");
    exit();
}
include('config.php');

// Fetch all job posts
$result = $conn->query("SELECT * FROM jobs");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
</head>
<body>
<a href="employer_dashboard.php">Return</a>
    <h1>Available Jobs</h1>
    <div id="job-list">
        <?php
        while ($job = $result->fetch_assoc()) {
            $job_id = $job['id'];

            // Fetch average rating for the job
            $rating_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback WHERE job_id = $job_id");
            $average_rating = $rating_result->fetch_assoc()['avg_rating'] ?? 0;

            echo "<div class='job'>";
            echo "<h2>" . $job['title'] . "</h2>";
            echo "<p>" . $job['description'] . "</p>";
            echo "<p>Average Rating: " . round($average_rating, 1) . "</p>";
            echo "</div><hr>";
        }
        ?>
    </div>
</body>
</html>
