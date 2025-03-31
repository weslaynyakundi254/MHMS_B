<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\book_appointment.php

session_start();
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to book an appointment.');
}

include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $professional_id = intval($_POST['professional_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Combine date and time into a single DATETIME value
    $schedule_time = $date . ' ' . $time;

    // Insert the appointment into the database
    $query = "INSERT INTO meetups (user_id, professional_id, schedule_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $user_id, $professional_id, $schedule_time);

    if ($stmt->execute()) {
        echo 'Appointment booked successfully!';
        header('Location: ../backend/user_dashboard.php?success=appointment_booked');
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