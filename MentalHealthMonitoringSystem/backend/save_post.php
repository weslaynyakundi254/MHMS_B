<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\save_post.php

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to post.');
}

include '../database/db_connection.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));

    // Validate content
    if (empty($content)) {
        die('Post content cannot be empty.');
    }

    // Insert the post into the database using prepared statements
    $query = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        echo 'Post shared successfully!';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    mysqli_close($conn);
} else {
    echo 'Invalid request method.';
}
?>