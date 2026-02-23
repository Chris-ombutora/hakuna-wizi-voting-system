<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    header("Location: admin.php");
    exit;
}

// Fetch positions and candidates
$positions = $pdo->query("SELECT * FROM positions")->fetchAll();
$candidates = [];
foreach ($positions as $pos) {
    $stmt = $pdo->prepare("SELECT * FROM candidates WHERE position_id = ?");
    $stmt->execute([$pos['id']]);
    $candidates[$pos['id']] = $stmt->fetchAll();
}

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$_SESSION['user_id']]);
$userData = $user->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Same head -->
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Hakuna Wizi</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="results.php" class="nav-link">Results</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Vote for Positions</h2>
        <form action="vote.php" method="POST">
            <?php foreach ($positions as $pos): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5><?php echo $pos['name']; ?></h5>
                        <?php if ($userData['has_voted_' . strtolower($pos['name'])]): ?>
                            <p class="text-success">You have already voted for this position.</p>
                        <?php else: ?>
                            <?php foreach ($candidates[$pos['id']] as $cand): ?>
                                <div class="form-check">
                                    <input type="radio" name="vote_<?php echo $pos['id']; ?>" value="<?php echo $cand['id']; ?>" class="form-check-input" required>
                                    <label><?php echo $cand['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Submit Votes</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
</body>
</html>