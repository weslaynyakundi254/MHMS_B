<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\manage_appointment.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professional') {
    die('Unauthorized access.');
}

include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        die('Invalid action.');
    }

    // Update the appointment status
    $query = "UPDATE meetups SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $appointment_id);

    if ($stmt->execute()) {
        echo 'Appointment updated successfully!';
        header('Location: ../backend/professionaldashboard.php?success=appointment_updated');
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