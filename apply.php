<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in to continue.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = (int) $_POST['job_id'];
    $name = $conn->real_escape_string(trim($_POST['name']));
    $education = $conn->real_escape_string(trim($_POST['education']));
    $contact = $conn->real_escape_string(trim($_POST['contact']));
    $employee_id = $_SESSION['user_id'];
    
    // Handling file upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed = ['pdf', 'docx'];
        $file_name = $_FILES['resume']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array($file_ext, $allowed)) {
            $file_tmp = $_FILES['resume']['tmp_name'];
            $new_file_name = uniqid() . '.' . $file_ext;
            move_uploaded_file($file_tmp, "uploads/resumes/$new_file_name");
        } else {
            echo "Invalid file type. Only PDF and DOCX allowed.";
            exit;
        }
    } else {
        echo "Error uploading file.";
        exit;
    }

    // Insert the application data into the database
    $query = "INSERT INTO applications (job_id, employee_id, name, education, contact, resume) 
              VALUES ($job_id, $employee_id, '$name', '$education', '$contact', '$new_file_name')";
    if ($conn->query($query)) {
        echo "Application submitted successfully!";
        echo "<a href='jobs.php'>Done</a>";
    } else {
        echo "Error submitting application: " . $conn->error;
    }
} else {
    $job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
    if ($job_id == 0) {
        die("Invalid job.");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
</head>
<body>
<a href="employee_dashboard.php">Return</a>
    <h1>Apply for Job</h1>
    <form action="apply.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="education">Education Level:</label>
        <input type="text" id="education" name="education" required><br><br>

        <label for="contact">Contact (Phone or Email):</label>
        <input type="text" id="contact" name="contact" required><br><br>

        <label for="resume">Upload Resume (PDF or DOCX):</label>
        <input type="file" id="resume" name="resume" accept=".pdf,.docx" required><br><br>

        <button type="submit">Submit Application</button>
    </form>
</body>
</html>
<?php
}
?>
