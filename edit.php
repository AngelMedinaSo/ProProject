<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/db.php';

$id = $_GET['id'] ?? null;
$item = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = trim($_POST['title'] ?? '');
    $opponent = trim($_POST['opponent'] ?? '');
    $score = trim($_POST['score'] ?? '');
    $result = trim($_POST['result'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) < 3) {
        $errors[] = 'Title must contain at least 3 characters.';
    } elseif (strlen($title) > 50) {
        $errors[] = 'Title cannot be longer than 50 characters.';
    }

    if ($opponent === '') {
        $errors[] = 'Opponent is required.';
    } elseif (strlen($opponent) < 3) {
        $errors[] = 'Opponent must contain at least 3 characters.';
    } elseif (strlen($opponent) > 50) {
        $errors[] = 'Opponent cannot be longer than 50 characters.';
    }

    if ($score === '') {
        $errors[] = 'Score is required.';
    } elseif (!is_numeric($score)) {
        $errors[] = 'Score must be a numeric value.';
    } elseif ((int)$score < 0 || (int)$score > 100) {
        $errors[] = 'Score must be between 0 and 100.';
    }

    if (!empty($errors)) {
        $_SESSION['error'] = implode(' ', $errors);
    } else {
        $stmt = $pdo->prepare("UPDATE scrims SET title = ?, opponent = ?, result = ?, score = ? WHERE id = ?");
        $stmt->execute([$title, $opponent, $result, (int)$score, $id]);

        $_SESSION['success'] = 'Item updated successfully!';
        header('Location: pages/home.php');
        exit;
    }
}

if ($id !== null && $id !== '') {
    $stmt = $pdo->prepare("SELECT id, title, opponent, result, score, played_on FROM scrims WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
}

include 'includes/header.php';
include 'includes/nav.php';

if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
} else {
    $errorMessage = '';
}
?>

<main class="hero">
    <p class="meta">Edit scrim</p>
    <h2>Edit an existing scrim</h2>

    <?php if ($errorMessage !== ''): ?>
        <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <?php if ($item): ?>
        <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST" class="list-card">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">

            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>">

            <label for="opponent">Opponent</label>
            <input type="text" id="opponent" name="opponent" value="<?php echo htmlspecialchars($item['opponent']); ?>">

            <label for="score">Score</label>
            <input type="number" id="score" name="score" min="0" max="100" value="<?php echo htmlspecialchars($item['score']); ?>">

            <label for="result">Result</label>
            <select id="result" name="result">
                <option value="Win" <?php echo ($item['result'] === 'Win') ? 'selected' : ''; ?>>Win</option>
                <option value="Loss" <?php echo ($item['result'] === 'Loss') ? 'selected' : ''; ?>>Loss</option>
                <option value="Draw" <?php echo ($item['result'] === 'Draw') ? 'selected' : ''; ?>>Draw</option>
            </select>

            <button type="submit" class="btn">Save changes</button>
        </form>
    <?php else: ?>
        <p>No scrim found for this ID.</p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
