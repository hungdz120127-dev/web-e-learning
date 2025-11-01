<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];

// Get enrolled courses count
$enrolled_sql = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = ?";
$enrolled_stmt = $conn->prepare($enrolled_sql);
$enrolled_stmt->bind_param("i", $student_id);
$enrolled_stmt->execute();
$enrolled_count = $enrolled_stmt->get_result()->fetch_assoc()['total'];

// Get completed courses count
$completed_sql = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = ? AND status = 'completed'";
$completed_stmt = $conn->prepare($completed_sql);
$completed_stmt->bind_param("i", $student_id);
$completed_stmt->execute();
$completed_count = $completed_stmt->get_result()->fetch_assoc()['total'];

// Get average quiz score
$avg_score_sql = "SELECT AVG(score) as avg_score FROM quiz_attempts WHERE student_id = ? AND completed_at IS NOT NULL";
$avg_stmt = $conn->prepare($avg_score_sql);
$avg_stmt->bind_param("i", $student_id);
$avg_stmt->execute();
$avg_score = $avg_stmt->get_result()->fetch_assoc()['avg_score'] ?? 0;

// Get unread messages count
$unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND read_status = 0";
$unread_stmt = $conn->prepare($unread_sql);
$unread_stmt->bind_param("i", $student_id);
$unread_stmt->execute();
$unread_count = $unread_stmt->get_result()->fetch_assoc()['total'];

// Get enrolled courses
$courses_sql = "SELECT c.*, e.progress, e.enrolled_at, u.full_name as teacher_name 
                FROM courses c 
                JOIN enrollments e ON c.id = e.course_id 
                JOIN users u ON c.teacher_id = u.id 
                WHERE e.student_id = ? AND e.status = 'active'
                ORDER BY e.enrolled_at DESC LIMIT 6";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->bind_param("i", $student_id);
$courses_stmt->execute();
$courses = $courses_stmt->get_result();

// Get recent announcements
$announcements_sql = "SELECT a.*, c.title as course_title, u.full_name as teacher_name 
                      FROM announcements a 
                      JOIN courses c ON a.course_id = c.id 
                      JOIN users u ON a.teacher_id = u.id 
                      JOIN enrollments e ON c.id = e.course_id 
                      WHERE e.student_id = ? 
                      ORDER BY a.created_at DESC LIMIT 5";
$ann_stmt = $conn->prepare($announcements_sql);
$ann_stmt->bind_param("i", $student_id);
$ann_stmt->execute();
$announcements = $ann_stmt->get_result();

$page_title = 'Dashboard - H?c sinh';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-home"></i> Trang ch?
                    </a>
                    <a class="nav-link" href="courses.php">
                        <i class="fas fa-book"></i> Kh?a h?c c?a t?i
                    </a>
                    <a class="nav-link" href="browse.php">
                        <i class="fas fa-search"></i> T?m kh?a h?c
                    </a>
                    <a class="nav-link" href="quizzes.php">
                        <i class="fas fa-clipboard-check"></i> B?i ki?m tra
                    </a>
                    <a class="nav-link" href="assignments.php">
                        <i class="fas fa-tasks"></i> B?i t?p
                    </a>
                    <a class="nav-link" href="messages.php">
                        <i class="fas fa-envelope"></i> Tin nh?n
                        <?php if ($unread_count > 0): ?>
                            <span class="badge bg-danger"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" href="progress.php">
                        <i class="fas fa-chart-line"></i> Ti?n ?? h?c t?p
                    </a>
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user"></i> H? s?
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="mb-4">Xin ch?o, <?php echo $_SESSION['full_name']; ?>! ??</h2>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card primary">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo $enrolled_count; ?></h3>
                                    <p class="text-muted mb-0">Kh?a h?c ?ang h?c</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card success">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo $completed_count; ?></h3>
                                    <p class="text-muted mb-0">?? ho?n th?nh</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo number_format($avg_score, 1); ?>%</h3>
                                    <p class="text-muted mb-0">?i?m trung b?nh</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card danger">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo $unread_count; ?></h3>
                                    <p class="text-muted mb-0">Tin nh?n m?i</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-book"></i> Kh?a h?c c?a t?i</h5>
                                <a href="courses.php" class="btn btn-sm btn-light">Xem t?t c?</a>
                            </div>
                            <div class="card-body">
                                <?php if ($courses->num_rows > 0): ?>
                                    <?php while($course = $courses->fetch_assoc()): ?>
                                        <div class="course-card">
                                            <div class="course-card-body">
                                                <h5 class="course-card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                                <p class="text-muted mb-2">
                                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($course['teacher_name']); ?>
                                                </p>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted"><?php echo $course['progress']; ?>% ho?n th?nh</small>
                                                    <a href="course_view.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">Ti?p t?c h?c</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">B?n ch?a ??ng k? kh?a h?c n?o</p>
                                        <a href="browse.php" class="btn btn-primary">T?m kh?a h?c</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Th?ng b?o</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($announcements->num_rows > 0): ?>
                                    <?php while($ann = $announcements->fetch_assoc()): ?>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($ann['title']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($ann['course_title']); ?> - 
                                                <?php echo timeAgo($ann['created_at']); ?>
                                            </small>
                                            <p class="mb-0 mt-2" style="font-size: 0.9rem;">
                                                <?php echo substr(htmlspecialchars($ann['content']), 0, 100); ?>...
                                            </p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Ch?a c? th?ng b?o m?i</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
