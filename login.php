<?php
require_once 'config/config.php';

$error = '';

if (isLoggedIn()) {
    if (isTeacher()) redirect('teacher/dashboard.php');
    elseif (isStudent()) redirect('student/dashboard.php');
    elseif (isAdmin()) redirect('admin/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            
            // Update last login
            $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();
            
            // Redirect based on role
            if ($user['role'] == 'teacher') {
                redirect('teacher/dashboard.php');
            } elseif ($user['role'] == 'student') {
                redirect('student/dashboard.php');
            } elseif ($user['role'] == 'admin') {
                redirect('admin/dashboard.php');
            }
        } else {
            $error = 'M?t kh?u kh?ng ??ng!';
        }
    } else {
        $error = 'T?i kho?n kh?ng t?n t?i ho?c ?? b? kh?a!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>??ng nh?p - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card fade-in">
            <div class="auth-header">
                <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                <h2>??ng Nh?p</h2>
                <p class="text-muted">Ch?o m?ng tr? l?i!</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> T?n ??ng nh?p</label>
                    <input type="text" class="form-control" name="username" required autofocus>
                </div>
                
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-lock"></i> M?t kh?u</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt"></i> ??ng Nh?p
                </button>
                
                <div class="text-center">
                    <p class="text-muted mb-2">Ch?a c? t?i kho?n? <a href="register.php" class="text-primary">??ng k? ngay</a></p>
                    <a href="index.php" class="text-muted"><i class="fas fa-home"></i> V? trang ch?</a>
                </div>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p class="text-muted mb-2"><small>T?i kho?n demo:</small></p>
                <small class="text-muted">
                    <strong>Gi?o vi?n:</strong> teacher1 / teacher123<br>
                    <strong>H?c sinh:</strong> student1 / student123
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
