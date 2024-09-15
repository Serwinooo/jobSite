<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: index.html");
    exit();
}
include('config.php');

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $employer_id = $_SESSION['user_id'];

    // Delete the job post
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ? AND employer_id = ?");
    $stmt->bind_param("ii", $job_id, $employer_id);

    if ($stmt->execute()) {
        echo "Job deleted!";
        header("Location: view_own_jobs.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
