<?php
require_once 'config/config.php';

$error = '';
$success = '';

if (isLoggedIn()) {
    if (isTeacher()) redirect('teacher/dashboard.php');
    elseif (isStudent()) redirect('student/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);
    $role = $_POST['role'];
    
    // Validation
    if ($password !== $confirm_password) {
        $error = 'M?t kh?u x?c nh?n kh?ng kh?p!';
    } elseif (strlen($password) < 6) {
        $error = 'M?t kh?u ph?i c? ?t nh?t 6 k? t?!';
    } else {
        // Check if username exists
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = 'T?n ??ng nh?p ho?c email ?? t?n t?i!';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $insert_sql = "INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $role);
            
            if ($insert_stmt->execute()) {
                $success = '??ng k? th?nh c?ng! B?n c? th? ??ng nh?p ngay.';
            } else {
                $error = 'C? l?i x?y ra, vui l?ng th? l?i!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>??ng k? - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card fade-in">
            <div class="auth-header">
                <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                <h2>??ng K?</h2>
                <p class="text-muted">T?o t?i kho?n m?i</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-id-card"></i> H? v? t?n</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> T?n ??ng nh?p</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-users"></i> Vai tr?</label>
                    <select class="form-control" name="role" required>
                        <option value="">Ch?n vai tr?</option>
                        <option value="student">H?c sinh</option>
                        <option value="teacher">Gi?o vi?n</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> M?t kh?u</label>
                    <input type="password" class="form-control" name="password" required>
                    <small class="text-muted">T?i thi?u 6 k? t?</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label"><i class="fas fa-lock"></i> X?c nh?n m?t kh?u</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-user-plus"></i> ??ng K?
                </button>
                
                <div class="text-center">
                    <p class="text-muted mb-2">?? c? t?i kho?n? <a href="login.php" class="text-primary">??ng nh?p</a></p>
                    <a href="index.php" class="text-muted"><i class="fas fa-home"></i> V? trang ch?</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
