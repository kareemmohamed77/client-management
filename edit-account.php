<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);
$errors = [];
$stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = ?");
$stmt->execute([$id]);
$account = $stmt->fetch();

if (!$account) {
    header("Location: index.php");
    exit;
}

$client_id      = $account['client_id'];
$account_name   = $account['account_name'];
$account_number = $account['account_number'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_name   = $_POST['account_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';

    if ($account_name === '') {
        $errors[] = 'Account name is required.';
    }
    if ($account_number === '') {
        $errors[] = 'Account number is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE accounts SET account_name = ?, account_number = ? WHERE id = ?");
        $stmt->execute([$account_name, $account_number, $id]);
        header("Location: client.php?id=$client_id&msg=Account updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
    <div class="container header-content">
        <h1>Client Management</h1>
        <nav>
            <a href="index.php">Clients</a>
            <a href="add-client.php">Add Client</a>
        </nav>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <div><h2>Edit Account</h2></div>
    </div>
    <div class="card form-card">
        <form method="POST">
            <div class="form-group">
                <label>Account Name</label>
                <input type="text" name="account_name" value="<?= $account_name ?>" required>
            </div>
            <div class="form-group">
                <label>Account Number</label>
                <input type="text" name="account_number" value="<?= $account_number ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="client.php?id=<?= $client_id ?>" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>