<?php
session_start();
require_once 'includes/db.php';

$successMessage = '';
$errorMessage = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '') {
        $errorMessage = 'Username is required.';
    } elseif ($password === '') {
        $errorMessage = 'Password is required.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                $successMessage = 'Login successful!';
                $username = '';
            } else {
                $errorMessage = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $errorMessage = 'Login failed. Please try again.';
        }
    }
}

include 'includes/header.php';
include 'includes/nav.php';
?>

<main class="hero">
    <p class="meta">Login</p>
    <h2>Log in to your account</h2>
    <p>Enter your credentials to access your scrim tracker.</p>

    <?php if ($successMessage !== ''): ?>
        <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST" class="list-card">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" name="submit" class="btn">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</main>

<?php include 'includes/footer.php'; ?>
