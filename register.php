<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $student_id = trim($_POST['student_id']);

    // Server-side validation
    if (empty($username) || empty($_POST['password']) || empty($student_id)) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, student_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $student_id])) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed. Username or Student ID may exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Same head as index.php -->
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2>Register</h2>
                        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                        <form method="POST" onsubmit="return validateForm()">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label>Student ID</label>
                                <input type="text" name="student_id" class="form-control" required pattern="[A-Z0-9]+">
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            const password = document.querySelector('[name="password"]').value;
            if (password.length < 6) {
                alert("Password must be at least 6 characters.");
                return false;
            }
            return true;
        }
    </script>
    <!-- Bootstrap JS -->
</body>
</html>