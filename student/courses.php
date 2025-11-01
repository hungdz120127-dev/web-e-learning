<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];

// Get enrolled courses
$courses_sql = "SELECT c.*, e.progress, e.enrolled_at, e.status, u.full_name as teacher_name,
                (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
                (SELECT COUNT(*) FROM lesson_progress lp 
                 JOIN lessons l ON lp.lesson_id = l.id 
                 WHERE l.course_id = c.id AND lp.student_id = ? AND lp.completed = 1) as completed_lessons
                FROM courses c 
                JOIN enrollments e ON c.id = e.course_id 
                JOIN users u ON c.teacher_id = u.id 
                WHERE e.student_id = ?
                ORDER BY e.enrolled_at DESC";
$stmt = $conn->prepare($courses_sql);
$stmt->bind_param("ii", $student_id, $student_id);
$stmt->execute();
$courses = $stmt->get_result();

$page_title = 'Kh?a h?c c?a t?i';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">Kh?a H?c C?a T?i</h2>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($courses->num_rows > 0): ?>
                    <div class="row">
                        <?php while($course = $courses->fetch_assoc()): ?>
                            <div class="col-md-6">
                                <div class="course-card">
                                    <?php if ($course['thumbnail']): ?>
                                        <img src="../uploads/courses/<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book fa-4x text-white" style="opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="course-card-body">
                                        <span class="badge bg-<?php echo $course['status'] == 'completed' ? 'success' : 'primary'; ?> mb-2">
                                            <?php echo $course['status'] == 'completed' ? 'Ho?n th?nh' : '?ang h?c'; ?>
                                        </span>
                                        <h5 class="course-card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($course['teacher_name']); ?>
                                        </p>
                                        <p class="mb-3"><?php echo substr(htmlspecialchars($course['description']), 0, 100); ?>...</p>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small>Ti?n ??: <?php echo $course['progress']; ?>%</small>
                                                <small><?php echo $course['completed_lessons']; ?>/<?php echo $course['lesson_count']; ?> b?i</small>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="course-meta mb-3">
                                            <span><i class="fas fa-clock"></i> ??ng k?: <?php echo formatDate($course['enrolled_at']); ?></span>
                                        </div>
                                        
                                        <a href="course_view.php?id=<?php echo $course['id']; ?>" class="btn btn-primary w-100">
                                            <i class="fas fa-play"></i> Ti?p t?c h?c
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">B?n ch?a ??ng k? kh?a h?c n?o</h4>
                        <p class="text-muted">H?y t?m v? ??ng k? kh?a h?c ph? h?p v?i b?n</p>
                        <a href="browse.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> T?m Kh?a H?c
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
