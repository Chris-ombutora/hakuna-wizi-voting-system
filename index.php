<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakuna Wizi System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f9f4; color: #333; }
        .navbar, .btn-primary { background-color: #28a745 !important; border-color: #198754; }
        .card { border-color: #28a745; }
        .alert { margin-top: 1rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white" href="#">Hakuna Wizi</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <h2 class="mb-4">University Online Voting</h2>
                        <p class="lead mb-4">Transparent • Secure • Simple</p>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
                            <a href="logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                        <?php else: ?>
                            <a href="auth.php?mode=login" class="btn btn-primary btn-lg">Login</a>
                            <a href="auth.php?mode=register" class="btn btn-outline-success ms-2">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
