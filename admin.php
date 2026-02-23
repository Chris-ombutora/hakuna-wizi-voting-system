<?php
require 'database_connection.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: dashboard.php");
    exit;
}

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = trim($_POST['name']);
        $position_id = $_POST['position_id'];
        $stmt = $pdo->prepare("INSERT INTO candidates (name, position_id) VALUES (?, ?)");
        $stmt->execute([$name, $position_id]);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $stmt = $pdo->prepare("UPDATE candidates SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = ?");
        $stmt->execute([$id]);
    }
}

$positions = $pdo->query("SELECT * FROM positions")->fetchAll();
$candidates = [];
foreach ($positions as $pos) {
    $stmt = $pdo->prepare("SELECT * FROM candidates WHERE position_id = ?");
    $stmt->execute([$pos['id']]);
    $candidates[$pos['id']] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Same head -->
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Hakuna Wizi Admin</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="results.php" class="nav-link">Results</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Manage Candidates</h2>
        <?php foreach ($positions as $pos): ?>
            <h4><?php echo $pos['name']; ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($candidates[$pos['id']] as $cand): ?>
                        <tr>
                            <td><?php echo $cand['id']; ?></td>
                            <td><?php echo $cand['name']; ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $cand['id']; ?>">
                                    <input type="text" name="name" value="<?php echo $cand['name']; ?>" required>
                                    <button type="submit" name="update" class="btn btn-sm btn-warning">Update</button>
                                </form>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?php echo $cand['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="POST">
                <input type="hidden" name="position_id" value="<?php echo $pos['id']; ?>">
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="New Candidate Name" required>
                    <button type="submit" name="add" class="btn btn-primary">Add</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>
    <!-- Bootstrap JS -->
</body>

</html>
