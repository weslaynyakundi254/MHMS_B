<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\post_to_forum.php

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to post in the forum.');
}

include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));

    // Validate content
    if (empty($content)) {
        die('Post content cannot be empty.');
    }

    // Insert the post into the forum_posts table
    $query = "INSERT INTO forum_posts (user_id, content) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $content);

    if ($stmt->execute()) {
        header('Location: ../backend/user_dashboard.php?success=post_shared');
        exit();
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method.';
}
?>