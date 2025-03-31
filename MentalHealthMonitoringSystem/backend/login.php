<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\login.php

session_start();
include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        die('Username and password are required.');
    }

    // Check if the user exists in the users table
    $query = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found in the users table
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables for user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'user'; // Set role as 'user'

            // Redirect to user dashboard
            header('Location: user_dashboard.php');
            exit();
        } else {
            die('Invalid password.');
        }
    }

    // Check if the user exists in the professionals table
    $query = "SELECT id, username, password FROM professionals WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found in the professionals table
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables for professional
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'professional'; // Set role as 'professional'

            // Redirect to professional dashboard
            header('Location: professionaldashboard.php');
            exit();
        } else {
            die('Invalid password.');
        }
    } else {
        die('User not found.');
    }
} else {
    echo 'Invalid request method.';
}
?>

<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\professional_dashboard.php

session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'professional') {
    header('Location: ../public/login.html?error=unauthorized');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>This is the professional dashboard.</p>
</body>
</html>