<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\schedule_meetup.php

session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to schedule a meetup.']);
    exit();
}

// Include the database connection file
include '../database/db_connection.php';

// Check if the required fields are provided
if (isset($_POST['professional_id'], $_POST['schedule_time']) && !empty($_POST['professional_id']) && !empty($_POST['schedule_time'])) {
    $user = $_SESSION['username'];
    $professionalId = $_POST['professional_id'];
    $scheduleTime = $_POST['schedule_time'];

    // Debugging: Log the received data
    file_put_contents('debug.log', "User: $user, Professional ID: $professionalId, Schedule Time: $scheduleTime\n", FILE_APPEND);

    // Insert meetup request into the database
    $query = "INSERT INTO meetups (user, professional_id, schedule_time, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $user, $professionalId, $scheduleTime);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Meetup scheduled successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error scheduling meetup: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. Please provide all required fields.']);
}

// Close the database connection
$conn->close();
?>