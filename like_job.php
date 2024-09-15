<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Access denied. Please log in.']));
}

include('config.php');

$job_id = $_POST['job_id'];
$employee_id = $_SESSION['user_id'];

// Check if the user has already liked the job
$like_check = $conn->query("SELECT liked FROM likes WHERE job_id = $job_id AND employee_id = $employee_id");
if ($like_check->num_rows > 0) {
    // Toggle like/unlike
    $like_row = $like_check->fetch_assoc();
    if ($like_row['liked'] == 1) {
        // Unlike the job
        $conn->query("UPDATE likes SET liked = 0 WHERE job_id = $job_id AND employee_id = $employee_id");
        $message = "Job disliked!";
    } else {
        // Like the job again
        $conn->query("UPDATE likes SET liked = 1 WHERE job_id = $job_id AND employee_id = $employee_id");
        $message = "Job liked!";
    }
} else {
    // First time like
    $stmt = $conn->prepare("INSERT INTO likes (job_id, employee_id, liked) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $job_id, $employee_id);
    $stmt->execute();
    $message = "Job liked!";
}

// Get the updated total number of likes
$likes_result = $conn->query("SELECT COUNT(*) AS total_likes FROM likes WHERE job_id = $job_id AND liked = 1");
$likes_row = $likes_result->fetch_assoc();
$total_likes = $likes_row['total_likes'];

// Return a JSON response
echo json_encode([
    'message' => $message,
    'total_likes' => $total_likes
]);
?>
