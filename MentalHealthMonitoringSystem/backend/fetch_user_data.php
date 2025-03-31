<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\fetch_user_data.php

// Connect to the database
include '../database/db_connection.php'; // Replace with your database connection script

// Fetch user mood data with a join to get the username
$query = "
    SELECT users.username, moods.mood, moods.timestamp, moods.location
    FROM moods
    JOIN users ON moods.user_id = users.id
";
$result = $conn->query($query);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>