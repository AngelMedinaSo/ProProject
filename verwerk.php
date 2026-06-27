<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/db.php';

$errors = [];
$title = '';
$opponent = '';
$score = '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
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
        header('Location: pages/toevoegen.php');
        exit;
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO scrims (title, opponent, result, score, played_on) VALUES (:title, :opponent, :result, :score, :played_on)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':opponent' => $opponent,
                ':result' => $result,
                ':score' => (int)$score,
                ':played_on' => date('Y-m-d')
            ]);

            $_SESSION['success'] = 'Item added successfully!';
            header('Location: pages/home.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'The item could not be saved.';
            header('Location: pages/home.php');
            exit;
        }
    }
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/nav.php"; ?>

<main class="hero">
    <p class="meta">Scrim tracker</p>
    <h2>Scrim submission</h2>

    <?php if (!empty($errors)): ?>
        <div class="list-card">
            <h3>Validation errors</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])): ?>
        <p>Your scrim details were validated successfully.</p>
        <ul class="list-card">
            <li><strong>Title:</strong> <?php echo htmlspecialchars($title); ?></li>
            <li><strong>Opponent:</strong> <?php echo htmlspecialchars($opponent); ?></li>
            <li><strong>Score:</strong> <?php echo htmlspecialchars($score); ?></li>
            <li><strong>Result:</strong> <?php echo htmlspecialchars($result); ?></li>
        </ul>
    <?php else: ?>
        <p>No data was submitted.</p>
    <?php endif; ?>

    <p><a href="pages/toevoegen.php" class="btn">Back to add page</a></p>
</main>

<?php include "includes/footer.php"; ?>
