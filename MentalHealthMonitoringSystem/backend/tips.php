<?php
// filepath: e:\xampp\htdocs\MentalHealthMonitoringSystem\public\tips.php

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Get the mood from the query parameter
$mood = isset($_GET['mood']) ? htmlspecialchars($_GET['mood']) : '';

// Define tips for each mood
$tips = [
    'happy' => 'Keep spreading positivity and enjoy the moment!',
    'sad' => 'Take deep breaths, talk to a friend, or write down your feelings.',
    'lonely' => 'Reach out to loved ones or join a community activity.',
    'angry' => 'Try calming techniques like meditation or a short walk.'
];

// Get the tip for the selected mood
$tip = isset($tips[$mood]) ? $tips[$mood] : 'No tips available for this mood.';
?>

<?php
echo "Current file path: " . __FILE__;
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tips for <?php echo htmlspecialchars(ucfirst($mood)); ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Tips for <?php echo htmlspecialchars(ucfirst($mood)); ?></h1>
        <p><?php echo htmlspecialchars($tip); ?></p>
        <a href="user_dashboard.php" class="btn">Back to Dashboard</a>
        <a href="../backend/tips.php?mood=happy" class="btn">View Tips</a>
    </div>
</body>
</html>