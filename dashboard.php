<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$_SESSION['user_id']]);
$user = $userStmt->fetch();

$positions = $pdo->query("SELECT * FROM positions")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hakuna Wizi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> /* same green theme */ </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white" href="#">Hakuna Wizi</a>
            <div class="ms-auto">
                <a href="results.php" class="btn btn-outline-light me-2">View Results</a>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4 text-center">Cast Your Vote</h2>

        <div id="voteMessage" class="alert d-none"></div>

        <form id="votingForm">
            <?php foreach ($positions as $pos): 
                $posKey = strtolower(str_replace(' ', '_', $pos['name']));
                $votedField = 'voted_' . $posKey;
                $hasVoted = $user[$votedField];

                $candStmt = $pdo->prepare("SELECT * FROM candidates WHERE position_id = ?");
                $candStmt->execute([$pos['id']]);
                $candidates = $candStmt->fetchAll();
            ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><?= htmlspecialchars($pos['name']) ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if ($hasVoted): ?>
                            <p class="text-success fw-bold">You have already voted for this position.</p>
                        <?php else: ?>
                            <?php foreach ($candidates as $cand): ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" 
                                           name="vote_<?= $pos['id'] ?>" 
                                           value="<?= $cand['id'] ?>" 
                                           id="cand_<?= $cand['id'] ?>" required>
                                    <label class="form-check-label" for="cand_<?= $cand['id'] ?>">
                                        <?= htmlspecialchars($cand['name']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$user['voted_president'] || !$user['voted_vice']): ?>
                <button type="submit" class="btn btn-primary btn-lg w-100" id="submitVoteBtn">Submit Votes</button>
            <?php endif; ?>
        </form>
    </div>

    <script>
        document.getElementById('votingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('voteMessage');
            const btn = document.getElementById('submitVoteBtn');

            btn.disabled = true;
            btn.innerText = "Submitting...";

            fetch('vote_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
                messageDiv.classList.add(data.success ? 'alert-success' : 'alert-danger');
                messageDiv.innerHTML = data.message;

                if (data.success) {
                    setTimeout(() => location.reload(), 1800); // refresh to show "already voted"
                }
            })
            .catch(() => {
                messageDiv.classList.remove('d-none');
                messageDiv.classList.add('alert-danger');
                messageDiv.innerHTML = "An error occurred. Please try again.";
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerText = "Submit Votes";
            });
        });
    </script>
</body>
</html>
