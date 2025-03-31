<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\fetch_professionals.php

// Connect to the database
include '../database/db_connection.php'; // Replace with your database connection script

// Fetch professionals
$query = "SELECT id, name, specialization, location FROM professionals";
$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>