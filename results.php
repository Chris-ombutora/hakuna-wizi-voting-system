<?php require 'config.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$positions = $pdo->query("SELECT * FROM positions")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results - Hakuna Wizi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> /* same green theme */ </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white" href="dashboard.php">Hakuna Wizi</a>
            <a href="logout.php" class="btn btn-outline-light ms-auto">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Election Results</h2>

        <?php foreach ($positions as $pos): 
            $stmt = $pdo->prepare("
                SELECT c.name, COUNT(v.id) AS votes 
                FROM candidates c 
                LEFT JOIN votes v ON c.id = v.candidate_id 
                WHERE c.position_id = ? 
                GROUP BY c.id 
                ORDER BY votes DESC
            ");
            $stmt->execute([$pos['id']]);
            $results = $stmt->fetchAll();
            $total = array_sum(array_column($results, 'votes'));
        ?>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><?= htmlspecialchars($pos['name']) ?> (Total votes: <?= $total ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($results)): ?>
                        <p class="text-muted">No votes yet.</p>
                    <?php else: ?>
                        <?php foreach ($results as $r): 
                            $percent = $total > 0 ? round(($r['votes'] / $total) * 100, 1) : 0;
                        ?>
                            <div class="mb-3">
                                <strong><?= htmlspecialchars($r['name']) ?> — <?= $r['votes'] ?> votes (<?= $percent ?>%)</strong>
                                <div class="progress mt-1" style="height: 24px;">
                                    <div class="progress-bar bg-success" style="width: <?= $percent ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
