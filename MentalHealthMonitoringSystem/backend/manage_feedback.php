<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'professional') {
    die('Unauthorized access.');
}

include '../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $feedback = mysqli_real_escape_string($conn, trim($_POST['feedback']));

    // Update the feedback for the appointment
    $query = "UPDATE meetups SET feedback = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $feedback, $appointment_id);

    if ($stmt->execute()) {
        header('Location: professionaldashboard.php?success=feedback_added');
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