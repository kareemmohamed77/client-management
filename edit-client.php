<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);
$errors = [];
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: index.php");
    exit;
}
$name  = $client['name'];
$email = $client['email'];
$phone = $client['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE clients SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $id]);
        header("Location: client.php?id=$id&msg=Client updated");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Client</title>
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
                <h2>Edit Client</h2>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="client.php?id=<?= $id ?>" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>