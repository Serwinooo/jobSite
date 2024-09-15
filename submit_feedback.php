<?php
// Start session and include database config
session_start();
include('config.php');

if (!isset($_POST['job_id'], $_POST['rating'], $_POST['comment']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Invalid data.']);
    exit;
}

$job_id = (int) $_POST['job_id'];
$rating = (int) $_POST['rating'];
$comment = $conn->real_escape_string(trim($_POST['comment']));
$employee_id = $_SESSION['user_id'];

// Insert or update the feedback in the database
$query = "INSERT INTO feedback (job_id, employee_id, rating, comment) 
          VALUES ($job_id, $employee_id, $rating, '$comment') 
          ON DUPLICATE KEY UPDATE rating = $rating, comment = '$comment'";
$conn->query($query);

// Fetch the new average rating
$result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback WHERE job_id = $job_id");
$average_rating = $result->fetch_assoc()['avg_rating'];

// Return the updated average rating
echo json_encode([
    'message' => 'Feedback submitted successfully!',
    'average_rating' => round($average_rating, 1)
]);
?>
