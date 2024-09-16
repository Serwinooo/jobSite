<?php
// Start session and include database config
session_start();
include('config.php');

// Check if required data is provided and session is active
if (!isset($_POST['job_id'], $_POST['rating'], $_POST['comment']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'Invalid data.']);
    exit;
}

$job_id = (int) $_POST['job_id'];
$rating = (int) $_POST['rating'];
$comment = $conn->real_escape_string(trim($_POST['comment']));
$employee_id = $_SESSION['user_id'];

// Check if feedback already exists
$feedback_check_query = "SELECT * FROM feedback WHERE job_id = $job_id AND employee_id = $employee_id";
$feedback_check_result = $conn->query($feedback_check_query);

if ($feedback_check_result->num_rows > 0) {
    // Feedback exists, so update it
    $query = "UPDATE feedback SET rating = $rating, comment = '$comment' WHERE job_id = $job_id AND employee_id = $employee_id";
    $message = 'Feedback updated successfully!';
} else {
    // Insert new feedback
    $query = "INSERT INTO feedback (job_id, employee_id, rating, comment) 
              VALUES ($job_id, $employee_id, $rating, '$comment')";
    $message = 'Feedback submitted successfully!';
}

$conn->query($query);

// Fetch the new average rating
$result = $conn->query("SELECT AVG(rating) AS avg_rating FROM feedback WHERE job_id = $job_id");
$average_rating = $result->fetch_assoc()['avg_rating'];

// Return the updated average rating and message
echo json_encode([
    'message' => $message,
    'average_rating' => round($average_rating, 1)
]);
?>
