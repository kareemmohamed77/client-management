<?php
require_once 'config.php';

$clients = $pdo->query("SELECT * FROM clients")->fetchAll();
foreach ($clients as &$c) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE client_id = ?");
    $stmt->execute([$c['id']]);
    $c['accounts_count'] = $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <h1>Client Management</h1>
            <nav>
                <a href="index.php" class="active">Clients</a>
                <a href="add-client.php">Add Client</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= $_GET['msg'] ?></div>
        <?php endif; ?>

        <div class="page-header">
            <div>
                <h2>Clients</h2>
                <p>Manage clients and their accounts.</p>
            </div>
            <a href="add-client.php" class="btn btn-primary"> Add Client</a>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Accounts</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">No clients</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $client) : ?>
                            <tr>
                                <td><?= $client['id'] ?></td>
                                <td><?= $client['name'] ?></td>
                                <td><?= $client['email'] ?></td>
                                <td><?= $client['phone'] ?: '-' ?></td>
                                <td><span class="badge"><?= $client['accounts_count'] ?> Accounts</span></td>
                                <td class="actions">
                                    <a href="client.php?id=<?= $client['id'] ?>" class="btn btn-small">View</a>
                                    <a href="edit-client.php?id=<?= $client['id'] ?>" class="btn btn-small btn-edit">Edit</a>
                                    <a href="delete-client.php?id=<?= $client['id'] ?>" class="btn btn-small btn-danger"
                                        onclick="return confirm('Delete this client?')">Delete</a>
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