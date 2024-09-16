<?php
session_start();
include('config.php');

// Check if the user is logged in as an employer
if (!isset($_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] !== 'employer') {
    die("Access denied.");
}

// Fetch all applications
$applications = $conn->query("SELECT a.*, j.title FROM applications a
                              JOIN jobs j ON a.job_id = j.id
                              ORDER BY a.submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications</title>
</head>
<body>
    <h1>Job Applications</h1>
    <table border="1">
        <tr>
            <th>Job Title</th>
            <th>Name</th>
            <th>Education</th>
            <th>Contact</th>
            <th>Resume</th>
            <th>Submitted At</th>
        </tr>
        <?php while ($application = $applications->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $application['title']; ?></td>
            <td><?php echo $application['name']; ?></td>
            <td><?php echo $application['education']; ?></td>
            <td><?php echo $application['contact']; ?></td>
            <td><a href="uploads/resumes/<?php echo $application['resume']; ?>" target="_blank">View Resume</a></td>
            <td><?php echo $application['submitted_at']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
