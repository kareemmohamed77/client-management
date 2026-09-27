<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT client_id FROM accounts WHERE id = ?");
$stmt->execute([$id]);
$account = $stmt->fetch();

if ($account) {
    $stmt = $pdo->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: client.php?id=" . $account['client_id'] . "&msg=Account deleted");
    exit;
}

header("Location: index.php");
exit;