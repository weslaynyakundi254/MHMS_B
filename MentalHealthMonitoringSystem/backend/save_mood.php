<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\log_mood.php

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to log your mood.');
}

include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $mood = mysqli_real_escape_string($conn, trim($_POST['mood']));
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, trim($_POST['note'])) : null;

    // Validate mood
    if (empty($mood)) {
        die('Mood is required.');
    }

    // Insert the mood entry into the database
    $query = "INSERT INTO mood_tracking (user_id, mood, note) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $mood, $note);

    if ($stmt->execute()) {
        echo 'Mood logged successfully!';
        header('Location: ../backend/user_dashboard.php?success=mood_logged');
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