<?php
session_start();
if ($_SESSION['role'] != 'employee') {
    die("Access denied!");
}
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $employee_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO applications (job_id, employee_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $job_id, $employee_id);
    if ($stmt->execute()) {
        echo "Application submitted!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
