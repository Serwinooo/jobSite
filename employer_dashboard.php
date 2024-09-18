<?php
session_start();
if ($_SESSION['role'] != 'employer') {
    header("Location: index.html");
    exit();
}
include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
</head>
<body>
    <h1>Employer Dashboard</h1>
    <nav>
        <ul>
            <li><a href="post_job.php">Post a Job</a></li>
            <li><a href="employer_application.php">View Applicants</a></li>
            <li><a href="view_own_jobs.php">View My Job Posts</a></li>
            <li><a href="view_jobs.php">View Available Jobs</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
