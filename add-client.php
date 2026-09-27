<?php
require_once 'config.php';

$errors = [];
$name = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO clients (name, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $phone]);
        header("Location: index.php?msg=Client added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Client</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <h1>Client Management</h1>
            <nav>
                <a href="index.php">Clients</a>
                <a href="add-client.php" class="active">Add Client</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <div>
                <h2>Add Client</h2>
                <p>Create a new client.</p>
            </div>
        </div>
        <div class="card form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?= $name ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $email ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= $phone ?>">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="index.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>