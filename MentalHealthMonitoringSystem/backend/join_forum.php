<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\join_forum.php

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to join the forum.');
}

include '../database/db_connection.php';

$user_id = $_SESSION['user_id'];

// Check if the user is already a member
$query = "SELECT id FROM forum_memberships WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header('Location: ../backend/user_dashboard.php?error=already_member');
    exit();
}

$stmt->close();

// Add the user to the forum_memberships table
$query = "INSERT INTO forum_memberships (user_id) VALUES (?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header('Location: ../backend/user_dashboard.php?success=joined_forum');
    exit();
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>