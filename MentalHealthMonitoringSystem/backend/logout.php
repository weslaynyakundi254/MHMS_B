<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\backend\logout.php

// Start the session
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page
header('Location: ../public/login.html');
exit();
?>