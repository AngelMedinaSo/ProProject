<?php
session_start();
include "../includes/header.php";
include "../includes/nav.php";
include "../includes/db.php";

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

$stmt = $pdo->prepare("SELECT id, title, opponent, result, score, played_on FROM scrims ORDER BY played_on DESC");
$stmt->execute();
$scrims = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="hero">
    <p class="meta">Scrim tracker</p>
    <h2>Your recent matches</h2>
    <p>Track and manage all your scrim results in one place.</p>

    <?php if ($successMessage !== ''): ?>
        <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <div class="list-card">
        <h3>Recent scrims</h3>
        <?php if (empty($scrims)): ?>
            <p>No scrims recorded yet. <a href="toevoegen.php">Add your first scrim</a></p>
        <?php else: ?>
            <ul>
                <?php foreach ($scrims as $scrim): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($scrim["title"]); ?></strong>
                        vs <?php echo htmlspecialchars($scrim["opponent"]); ?>
                        <span>(<?php echo htmlspecialchars($scrim["result"]); ?> - Score: <?php echo htmlspecialchars($scrim["score"]); ?> - <?php echo htmlspecialchars($scrim["played_on"]); ?>)</span>
                        <br>
                        <a href="../edit.php?id=<?php echo htmlspecialchars($scrim["id"]); ?>" class="btn">Edit</a>
                        <a href="../delete.php?id=<?php echo htmlspecialchars($scrim["id"]); ?>" class="btn">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>

<?php include "../includes/footer.php"; ?>
