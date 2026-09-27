<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM accounts WHERE client_id = ?");
$stmt->execute([$id]);
$accounts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Details</title>
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

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= $_GET['msg'] ?></div>
    <?php endif; ?>

    <div class="page-header">
        <h2>Client Details</h2>
        <a href="index.php" class="btn">Back</a>
    </div>
    <div class="card">
        <h3>Client Information</h3>
        <p><strong>Name:</strong> <?= $client['name'] ?></p>
        <p><strong>Email:</strong> <?= $client['email'] ?></p>
        <p><strong>Phone:</strong> <?= $client['phone'] ?: '-' ?></p>
        <a href="edit-client.php?id=<?= $client['id'] ?>" class="btn btn-small">Edit</a>
    </div>

    <div class="page-header">
        <h2>Accounts</h2>
        <a href="add-account.php?client_id=<?= $client['id'] ?>" class="btn btn-primary">+ Add Account</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)): ?>
                    <tr><td colspan="4">No accounts yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($accounts as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>
                            <td><?= $a['account_name'] ?></td>
                            <td><?= $a['account_number'] ?></td>
                            <td>
                                <a href="edit-account.php?id=<?= $a['id'] ?>" class="btn btn-small">Edit</a>
                                <a href="delete-account.php?id=<?= $a['id'] ?>" class="btn btn-small"
                                   onclick="return confirm('Delete this account?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>
</body>
</html>