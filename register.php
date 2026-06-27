<?php
session_start();
require_once 'includes/db.php';

$successMessage = '';
$errorMessage = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $passwordConfirm = trim($_POST['password_confirm'] ?? '');

    if ($username === '') {
        $errorMessage = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errorMessage = 'Username must be at least 3 characters.';
    } elseif (strlen($username) > 100) {
        $errorMessage = 'Username cannot be longer than 100 characters.';
    } elseif ($password === '') {
        $errorMessage = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errorMessage = 'Password must be at least 6 characters.';
    } elseif ($password !== $passwordConfirm) {
        $errorMessage = 'Passwords do not match.';
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);

            $successMessage = 'User registered successfully!';
            $username = '';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'Username already exists.';
            } else {
                $errorMessage = 'Registration failed. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
include 'includes/nav.php';
?>

<main class="hero">
    <p class="meta">Register</p>
    <h2>Create a new account</h2>
    <p>Sign up to start tracking your scrims.</p>

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

        <label for="password_confirm">Confirm Password</label>
        <input type="password" id="password_confirm" name="password_confirm" required>

        <button type="submit" name="submit" class="btn">Register</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
