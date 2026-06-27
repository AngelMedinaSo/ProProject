<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id !== null && $id !== '') {
    $stmt = $pdo->prepare("DELETE FROM scrims WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['success'] = 'Item deleted successfully!';
} else {
    $_SESSION['error'] = 'No item ID was provided.';
}

header('Location: pages/home.php');
exit;
