<?php
session_start();
if ($_SESSION['role'] != 'employee') {
    die("Access denied!");
}
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $employee_id = $_SESSION['user_id'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("INSERT INTO reviews (job_id, employee_id, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $job_id, $employee_id, $feedback);
    if ($stmt->execute()) {
        echo "Review posted!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="employee_dashboard.php">Return</a>
</body>
</html>