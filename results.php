<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$positions = $pdo->query("SELECT * FROM positions")->fetchAll();
$results = [];
foreach ($positions as $pos) {
    $stmt = $pdo->prepare("SELECT c.name, COUNT(v.id) as votes FROM candidates c LEFT JOIN votes v ON c.id = v.candidate_id WHERE c.position_id = ? GROUP BY c.id ORDER BY votes DESC");
    $stmt->execute([$pos['id']]);
    $results[$pos['id']] = $stmt->fetchAll();
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
            <a class="navbar-brand" href="dashboard.php">Hakuna Wizi</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Election Results</h2>
        <?php foreach ($positions as $pos): ?>
            <h4><?php echo $pos['name']; ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>Candidate</th><th>Votes</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($results[$pos['id']] as $res): ?>
                        <tr><td><?php echo $res['name']; ?></td><td><?php echo $res['votes']; ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
    <!-- Bootstrap JS -->
</body>
</html>