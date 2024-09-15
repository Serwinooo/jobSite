<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on user role
        if ($_SESSION['role'] == 'employee') {
            header("Location: employee_dashboard.php");
        } elseif ($_SESSION['role'] == 'employer') {
            header("Location: employer_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid username or password!";
    }
}
?>
