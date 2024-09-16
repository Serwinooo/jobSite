<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: index.html");
    exit();
}
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $employer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO jobs (title, description, employer_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $employer_id);
    if ($stmt->execute()) {
        echo "Job posted!";
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
    <title>Post a Job</title>
</head>
<body>
<a href="employer_dashboard.php">Return</a>
    <h1>Post a Job</h1>
    <form action="post_job.php" method="POST">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Job Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <input type="submit" value="Post Job">
    </form>
</body>
</html>
