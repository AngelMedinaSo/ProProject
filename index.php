<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: pages/home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProProject - Scrim Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <main class="hero">
        <p class="meta">Scrim Tracker</p>
        <h2>Track, analyze, and improve your scrims.</h2>
        <p>Keep detailed records of your competitive matches. Review performance, track trends, and dominate the competition.</p>
        <div class="actions">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn secondary">Get Started</a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
