<?php
session_start();
if ($_SESSION['role'] != 'employee') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>
    <h1>Employee Dashboard</h1>
    <nav>
        <ul>
            <li><a href="jobs.php">View Jobs</a></li>
            <li><a href="leave_review.php">Leave a Review</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
