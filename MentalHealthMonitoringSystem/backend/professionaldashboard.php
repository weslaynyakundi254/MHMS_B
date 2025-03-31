<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\public\professional_dashboard.php

// Start the session
session_start();
include '../database/db_connection.php';

// Check if the professional is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'professional') {
    // Redirect to login page if not logged in or not a professional
    header('Location: ../public/login.html');
    exit();
}

// Get the professional's name from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Dashboard - Mental Health Monitoring System</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Function to confirm logout
        function confirmLogout() {
            const confirmAction = confirm("Are you sure you want to log out?");
            if (confirmAction) {
                window.location.href = "../backend/logout.php"; // Redirect to logout
            }
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>Professional Dashboard</h2>
            <ul>
                <li><a href="../backend/professionaldashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../backend/appointments.php"><i class="fas fa-calendar-alt"></i> Appointments</a></li>
                <li><a href="../backend/reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="../backend/settings.php"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <p style="color: green; font-weight: bold;">Login Successful!</p>
            <button class="btn" onclick="confirmLogout()">Logout</button>
            <!-- Other dashboard content -->
            <p>This is your dashboard where you can view and manage user data.</p>
            
            <!-- Add professional-specific content here -->
            <div class="dashboard-content">
                <h2>Users' Mood Reports</h2>
                <p>Here you can view the mood reports submitted by users.</p>
                <!-- Example placeholder for future functionality -->
                <ul>
                    <li>View user progress</li>
                    <li>Analyze mood trends</li>
                    <li>Provide feedback to users</li>
                </ul>
            </div>
            <div class="professional">
                <p>Professional Name: John Doe</p>
                <p>Specialization: Therapist</p>
                <button class="schedule-meetup-btn" data-professional-id="1">Schedule Meetup</button>
            </div>
            <!-- Bookings Section -->
            <div class="section">
                <h2><i class="fas fa-calendar-check"></i> Appointment Calendar</h2>
                <p>The appointment calendar is generated automatically for each patient based on the initial appointment date.</p>
                <div class="appointment-actions">
                    <button class="btn download-btn"><i class="fas fa-file-download"></i> Download PDF</button>
                    <button class="btn add-btn"><i class="fas fa-plus"></i> Add Appointment</button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Username</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch bookings for the logged-in professional
                        $query = "
                            SELECT meetups.id AS appointment_id, meetups.schedule_time, meetups.status, users.username, meetups.feedback
                            FROM meetups
                            JOIN users ON meetups.user_id = users.id
                            JOIN professionals ON meetups.professional_id = professionals.id
                            WHERE professionals.username = ?
                            ORDER BY meetups.schedule_time ASC
                        ";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['schedule_time']) . '</td>';
                                echo '<td>Regular Appointment</td>';
                                echo '<td>' . htmlspecialchars(ucfirst($row['status'])) . '</td>';
                                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                                echo '<td>';
                                echo '<form action="manage_appointment.php" method="POST" style="display:inline;">';
                                echo '<input type="hidden" name="appointment_id" value="' . htmlspecialchars($row['appointment_id']) . '">';
                                echo '<button type="submit" name="action" value="approve" class="btn approve-btn">Approve</button>';
                                echo '<button type="submit" name="action" value="reject" class="btn reject-btn">Reject</button>';
                                echo '</form>';
                                echo '<form action="manage_feedback.php" method="POST" style="display:inline;">';
                                echo '<input type="hidden" name="appointment_id" value="' . htmlspecialchars($row['appointment_id']) . '">';
                                echo '<input type="text" name="feedback" placeholder="Add feedback" required>';
                                echo '<button type="submit" class="btn feedback-btn">Submit</button>';
                                echo '</form>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No appointments found.</td></tr>';
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>