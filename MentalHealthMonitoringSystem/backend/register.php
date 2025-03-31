<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\register.php

// Include the database connection file
include '../database/db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $role = mysqli_real_escape_string($conn, trim($_POST['role'])); // 'user' or 'professional'

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($role)) {
        die('All fields are required.');
    }

    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Check if the username already exists in either table
    $checkQuery = "SELECT username FROM users WHERE username = ? UNION SELECT username FROM professionals WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die('Error: Username already exists. Please choose a different username.');
    }

    $stmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    if ($role === 'professional') {
        // Insert into the professionals table
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $specialization = mysqli_real_escape_string($conn, trim($_POST['specialization']));
        $location = mysqli_real_escape_string($conn, trim($_POST['location']));

        if (empty($name) || empty($specialization) || empty($location)) {
            die('All professional fields are required.');
        }

        $query = "INSERT INTO professionals (name, specialization, location, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $name, $specialization, $location, $username, $hashedPassword);
    } else {
        // Insert into the users table
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $hashedPassword);
    }

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Redirect to the login page after successful registration
        header('Location: ../public/login.html');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method.';
}
?>

<form action="../backend/register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" name="confirm-password" required>

    <label for="role">Register as:</label>
    <select id="role" name="role" required>
        <option value="user">User</option>
        <option value="professional">Professional</option>
    </select>

    <!-- Fields for professionals -->
    <div id="professional-fields" style="display: none;">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name">

        <label for="specialization">Specialization:</label>
        <input type="text" id="specialization" name="specialization">

        <label for="location">Location:</label>
        <input type="text" id="location" name="location">
    </div>

    <button type="submit">Register</button>
</form>

<script>
    // Show/hide professional fields based on role selection
    document.getElementById('role').addEventListener('change', function () {
        const professionalFields = document.getElementById('professional-fields');
        if (this.value === 'professional') {
            professionalFields.style.display = 'block';
        } else {
            professionalFields.style.display = 'none';
        }
    });
</script>