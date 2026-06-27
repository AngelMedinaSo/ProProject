<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<nav>
    <h1><a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../index.php' : 'index.php'; ?>" style="text-decoration: none; color: inherit;">ProProject</a></h1>
    <ul>
        <li><a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../pages/home.php' : 'pages/home.php'; ?>">Home</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../pages/toevoegen.php' : 'pages/toevoegen.php'; ?>">Add Scrim</a></li>
        <?php endif; ?>
    </ul>
    <div class="nav-right">
        <button id="darkModeBtn" class="btn secondary">Dark Mode</button>
        <?php if (isset($_SESSION['user'])): ?>
            <span class="user-info">Logged in as <?php echo htmlspecialchars($_SESSION['user']); ?></span>
            <a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../logout.php' : 'logout.php'; ?>" class="btn secondary">Logout</a>
        <?php else: ?>
            <a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../login.php' : 'login.php'; ?>" class="btn">Login</a>
            <a href="<?php echo (strpos($_SERVER['REQUEST_URI'], '/pages/') !== false) ? '../register.php' : 'register.php'; ?>" class="btn secondary">Register</a>
        <?php endif; ?>
    </div>
</nav>
