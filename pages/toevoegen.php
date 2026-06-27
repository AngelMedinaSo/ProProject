<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

include "../includes/header.php";
include "../includes/nav.php";

if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
} else {
    $successMessage = '';
}

if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $errorMessage = '';
}
?>

<main class="hero">
    <p class="meta">Add scrim</p>
    <h2>Add a new scrim</h2>
    <p>Fill in the details below to send a new scrim entry to the processing page.</p>

    <?php if ($successMessage !== ''): ?>
        <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form action="../verwerk.php" method="POST" class="list-card">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="opponent">Opponent</label>
        <input type="text" id="opponent" name="opponent" required>

        <label for="score">Score</label>
        <input type="number" id="score" name="score" min="0" max="100" required>

        <label for="result">Result</label>
        <select id="result" name="result">
            <option value="Win">Win</option>
            <option value="Loss">Loss</option>
            <option value="Draw">Draw</option>
        </select>

        <button type="submit" name="submit" class="btn">Send</button>
    </form>
</main>

<?php include "../includes/footer.php"; ?>
