<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $bio = sanitize($_POST['bio']);
    
    $update_sql = "UPDATE users SET full_name = ?, email = ?, bio = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $full_name, $email, $bio, $teacher_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $email;
        $success = "C?p nh?t th?ng tin th?nh c?ng!";
    } else {
        $error = "C? l?i x?y ra!";
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $check_sql = "SELECT password FROM users WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $teacher_id);
    $check_stmt->execute();
    $user = $check_stmt->get_result()->fetch_assoc();
    
    if (!password_verify($current_password, $user['password'])) {
        $error = "M?t kh?u hi?n t?i kh?ng ??ng!";
    } elseif ($new_password !== $confirm_password) {
        $error = "M?t kh?u m?i kh?ng kh?p!";
    } elseif (strlen($new_password) < 6) {
        $error = "M?t kh?u ph?i c? ?t nh?t 6 k? t?!";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed, $teacher_id);
        
        if ($update_stmt->execute()) {
            $success = "??i m?t kh?u th?nh c?ng!";
        } else {
            $error = "C? l?i x?y ra!";
        }
    }
}

// Get user info
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $teacher_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get stats
$courses_sql = "SELECT COUNT(*) as total FROM courses WHERE teacher_id = ?";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->bind_param("i", $teacher_id);
$courses_stmt->execute();
$courses_count = $courses_stmt->get_result()->fetch_assoc()['total'];

$students_sql = "SELECT COUNT(DISTINCT e.student_id) as total FROM enrollments e JOIN courses c ON e.course_id = c.id WHERE c.teacher_id = ?";
$students_stmt = $conn->prepare($students_sql);
$students_stmt->bind_param("i", $teacher_id);
$students_stmt->execute();
$students_count = $students_stmt->get_result()->fetch_assoc()['total'];

$lessons_sql = "SELECT COUNT(*) as total FROM lessons l JOIN courses c ON l.course_id = c.id WHERE c.teacher_id = ?";
$lessons_stmt = $conn->prepare($lessons_sql);
$lessons_stmt->bind_param("i", $teacher_id);
$lessons_stmt->execute();
$lessons_count = $lessons_stmt->get_result()->fetch_assoc()['total'];

$page_title = 'H? s? c? nh?n';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">H? S? C? Nh?n</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Profile Info -->
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Th?ng Tin C? Nh?n</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">T?n ??ng nh?p</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">H? v? t?n</label>
                                        <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gi?i thi?u b?n th?n</label>
                                        <textarea class="form-control" name="bio" rows="4" placeholder="Chia s? v? kinh nghi?m, chuy?n m?n c?a b?n..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                    </div>

                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="fas fa-save"></i> C?p nh?t th?ng tin
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-lock"></i> ??i M?t Kh?u</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">M?t kh?u hi?n t?i</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">M?t kh?u m?i</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                        <small class="text-muted">T?i thi?u 6 k? t?</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">X?c nh?n m?t kh?u m?i</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>

                                    <button type="submit" name="change_password" class="btn btn-warning">
                                        <i class="fas fa-key"></i> ??i m?t kh?u
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Th?ng K?</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 pb-3 border-bottom">
                                    <h4 class="mb-1"><?php echo $courses_count; ?></h4>
                                    <small class="text-muted">Kh?a h?c ?? t?o</small>
                                </div>

                                <div class="mb-3 pb-3 border-bottom">
                                    <h4 class="mb-1"><?php echo $students_count; ?></h4>
                                    <small class="text-muted">H?c sinh</small>
                                </div>

                                <div class="mb-3 pb-3 border-bottom">
                                    <h4 class="mb-1"><?php echo $lessons_count; ?></h4>
                                    <small class="text-muted">B?i gi?ng</small>
                                </div>

                                <div>
                                    <h4 class="mb-1"><?php echo formatDate($user['created_at']); ?></h4>
                                    <small class="text-muted">Ng?y tham gia</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
