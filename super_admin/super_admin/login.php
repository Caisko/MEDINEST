<?php
session_start();
require_once "config/db.php"; // Connects to vetclinic_db

$error = "";

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM super_admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        // Direct password comparison (plain text)
        if ($password === $admin['password']) {
            $_SESSION['super_admin_id'] = $admin['id']; // Updated session key
            header("Location: index_superadmin.php"); // Redirect on success
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Super Admin Login | Vet Network</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root { --admin-dark: #1a1a1a; --bg-soft: #f8f9fa; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-soft); height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .auth-card { width: 100%; max-width: 420px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 40px; }
        .auth-icon { width: 60px; height: 60px; background: var(--admin-dark); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .form-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #666; font-weight: 700; }
        .form-control { padding: 12px; border-radius: 8px; border: 1px solid #ddd; }
        .btn-dark-admin { background-color: var(--admin-dark); color: white; padding: 14px; font-weight: 600; border-radius: 8px; width: 100%; border: none; transition: 0.3s; }
        .btn-dark-admin:hover { background-color: #000; }
        .register-footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 0.85rem; }
        .register-footer a { color: var(--admin-dark); font-weight: 700; text-decoration: none; }
        .text-danger { font-size: 0.9rem; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-icon"><i data-feather="shield"></i></div>
        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1">Super Admin Access</h4>
            <p class="text-muted small">Secure Network Console</p>
        </div>

        <?php if ($error != ""): ?>
            <div class="text-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Administrator Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@vet.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Secure Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-check mb-4 mt-3 d-flex justify-content-between align-items-center">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <a href="forgot-password.html" class="forgot-link">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-dark-admin shadow-sm">Login to Dashboard</button>
        </form>
        <div class="register-footer">Don't have an account? <a href="register.html">Register here!</a></div>
    </div>
    <script>feather.replace();</script>
</body>
</html>
