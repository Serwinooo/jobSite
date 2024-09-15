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

    // Fetch the job post to edit
    $result = $conn->query("SELECT * FROM jobs WHERE id = $job_id AND employer_id = $employer_id");
    $job = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];

        // Update the job post
        $stmt = $conn->prepare("UPDATE jobs SET title = ?, description = ? WHERE id = ? AND employer_id = ?");
        $stmt->bind_param("ssii", $title, $description, $job_id, $employer_id);

        if ($stmt->execute()) {
            echo "Job updated!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
</head>
<body>
    <h1>Edit Job Post</h1>
    <form action="edit_job.php?job_id=<?php echo $job_id; ?>" method="POST">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $job['title']; ?>" required><br><br>

        <label for="description">Job Description:</label>
        <textarea id="description" name="description" required><?php echo $job['description']; ?></textarea><br><br>

        <input type="submit" value="Update Job">
    </form>
</body>
</html>
