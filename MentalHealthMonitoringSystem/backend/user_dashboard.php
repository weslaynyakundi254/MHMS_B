<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\public\user_dashboard.php

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    // Redirect to login page if not logged in or not a user
    header('Location: ../public/login.html?error=unauthorized');
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        // Function to confirm logout
        function confirmLogout() {
            const confirmAction = confirm("Are you sure you want to log out?");
            if (confirmAction) {
                window.location.href = "../backend/logout.php"; // Redirect to logout
            }
        }
    </script>
    <script src="../js/schedule_meetup.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <h2>User Dashboard</h2>
            <ul>
                <li><a href="user_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="save_mood.php"><i class="fas fa-search"></i> Track Your Mood</a></li>
                <li><a href="save_post.php"><i class="fas fa-cog"></i> Share Your Status</a></li>
                <li><a href="schedule_meetup.php"><i class="fas fa-cog"></i>Book an Appointment</a>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <p style="color: green; font-weight: bold;">Login Successful!</p>
            <!-- Other dashboard content -->
            <p>This is your dashboard where you can track your emotional well-being and progress.</p>
            
            <!-- Track Mood Section -->
            <div class="section mood-tracking-container">
                <!-- Track Mood Form -->
                <div class="mood-form">
                    <h2><i class="fas fa-smile"></i> Track Your Mood</h2>
                    <form action="../backend/save_mood.php" method="POST">
                        <label for="mood">Select Mood:</label>
                        <select id="mood" name="mood" required>
                            <option value="happy">Happy</option>
                            <option value="sad">Sad</option>
                            <option value="lonely">Lonely</option>
                            <option value="angry">Angry</option>
                        </select>

                        <label for="note">Add a Note (Optional):</label>
                        <textarea id="note" name="note" rows="3" placeholder="Describe your feelings..."></textarea>

                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>

                <!-- Mood History -->
                <div class="mood-history">
                    <h2><i class="fas fa-chart-line"></i> Mood History</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Mood</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../database/db_connection.php';

                            $user_id = $_SESSION['user_id']; // User's ID from session
                            $query = "SELECT mood, note, created_at FROM mood_tracking WHERE user_id = ? ORDER BY created_at DESC";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                                    echo '<td>' . htmlspecialchars(ucfirst($row['mood'])) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['note']) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No mood entries found.</td></tr>';
                            }

                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Share Your Status Section -->
            <div class="section">
                <h2><i class="fas fa-edit"></i> Share Your Status</h2>
                <form action="../backend/save_post.php" method="POST">
                    <textarea name="content" rows="4" placeholder="What's on your mind?" required></textarea>
                    <button type="submit" class="btn">Post</button>
                </form>
            </div>
            <!-- Book an Appointment Section -->
            <div class="section">
                <h2><i class="fas fa-calendar-alt"></i> Book an Appointment</h2>
                <form action="../backend/book_appointment.php" method="POST">
                    <label for="professional">Select Professional:</label>
                    <select id="professional" name="professional_id" required>
                        <?php
                        include '../database/db_connection.php';
                        $query = "SELECT id, name, specialization FROM professionals";
                        $result = $conn->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . ' - ' . htmlspecialchars($row['specialization']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No professionals available</option>';
                        }
                        ?>
                    </select>

                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date" required>

                    <label for="time">Select Time:</label>
                    <input type="time" id="time" name="time" required>

                    <button type="submit" class="btn">Book Appointment</button>
                </form>
            </div>

            <!-- My Appointments Section -->
            <div class="section">
                <h2><i class="fas fa-calendar-alt"></i> My Appointments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Professional</th>
                            <th>Status</th>
                            <th>Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '../database/db_connection.php';

                        $user_id = $_SESSION['user_id']; // User's ID from session
                        $query = "SELECT meetups.schedule_time, professionals.name AS professional_name, meetups.status, meetups.feedback 
                                  FROM meetups 
                                  JOIN professionals ON meetups.professional_id = professionals.id 
                                  WHERE meetups.user_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['schedule_time']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['professional_name']) . '</td>';
                                echo '<td>' . htmlspecialchars(ucfirst($row['status'])) . '</td>';
                                echo '<td>' . htmlspecialchars($row['feedback']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No appointments found.</td></tr>';
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Display All Posts -->
            <div class="posts">
                <h2>Recent Posts</h2>
                <?php
                include '../database/db_connection.php';
                $query = "SELECT posts.content, posts.created_at, users.username 
                          FROM posts 
                          JOIN users ON posts.user_id = users.id 
                          ORDER BY posts.created_at DESC";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="post-bubble">';
                        echo '<p class="post-header"><strong>' . htmlspecialchars($row['username']) . '</strong></p>';
                        echo '<p class="post-content">' . htmlspecialchars($row['content']) . '</p>';
                        echo '<p class="post-timestamp">' . htmlspecialchars($row['created_at']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No posts to display.</p>';
                }

                $conn->close();
                ?>
            </div>

            <!-- Community Forum Section -->
            <div class="section">
                <h2><i class="fas fa-comments"></i> Community Forum</h2>
                <form action="../backend/post_to_forum.php" method="POST">
                    <textarea name="content" rows="4" placeholder="Share your thoughts or helpful information..." required></textarea>
                    <button type="submit" class="btn">Post</button>
                </form>
            </div>

            <!-- Recent Forum Posts Section -->
            <div class="section">
                <h2><i class="fas fa-stream"></i> Recent Posts</h2>
                <div class="posts">
                    <?php
                    include '../database/db_connection.php';

                    $query = "SELECT forum_posts.content, forum_posts.created_at, users.username 
                              FROM forum_posts 
                              JOIN users ON forum_posts.user_id = users.id 
                              ORDER BY forum_posts.created_at DESC";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="post-bubble">';
                            echo '<p class="post-header"><strong>' . htmlspecialchars($row['username']) . '</strong> <span>' . htmlspecialchars($row['created_at']) . '</span></p>';
                            echo '<p class="post-content">' . htmlspecialchars($row['content']) . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No posts to display. Be the first to share something!</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>

            <!-- Join the Community Forum Section -->
            <div class="section">
                <h2><i class="fas fa-users"></i> Join the Community Forum</h2>
                <form action="../backend/join_forum.php" method="POST">
                    <button type="submit" class="btn">Join Forum</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>