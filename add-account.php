<?php
require_once 'config.php';

$client_id = (int) ($_GET['client_id'] ?? $_POST['client_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: index.php");
    exit;
}

$errors = [];
$account_name = '';
$account_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_name   = $_POST['account_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';

    if ($account_name === '') {
        $errors[] = 'Account name is required.';
    }
    if ($account_number === '') {
        $errors[] = 'Account number is required.';
    } else {
        $check = $pdo->prepare("SELECT id FROM accounts WHERE account_number = ?");
        $check->execute([$account_number]);
        if ($check->fetch()) {
            $errors[] = 'Account number already exists.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO accounts (client_id, account_name, account_number) VALUES (?, ?, ?)");
        $stmt->execute([$client_id, $account_name, $account_number]);
        header("Location: client.php?id=$client_id&msg=Account added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Account</title>
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
            <div>
                <h2>Add Account</h2>
                <p>For <?= $client['name'] ?></p>
            </div>
        </div>
        <div class="card form-card">
            <form method="POST">
                <input type="hidden" name="client_id" value="<?= $client_id ?>">

                <div class="form-group">
                    <label>Account Name</label>
                    <input type="text" name="account_name" value="<?= $account_name ?>" required>
                </div>
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" value="<?= $account_number ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="client.php?id=<?= $client_id ?>" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>