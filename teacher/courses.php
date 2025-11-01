<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];

// Get teacher's courses
$courses_sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
                (SELECT COUNT(*) FROM quizzes WHERE course_id = c.id) as quiz_count
                FROM courses c 
                WHERE c.teacher_id = ? 
                ORDER BY c.created_at DESC";
$stmt = $conn->prepare($courses_sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$courses = $stmt->get_result();

// Handle delete
if (isset($_GET['delete'])) {
    $course_id = (int)$_GET['delete'];
    $del_sql = "DELETE FROM courses WHERE id = ? AND teacher_id = ?";
    $del_stmt = $conn->prepare($del_sql);
    $del_stmt->bind_param("ii", $course_id, $teacher_id);
    $del_stmt->execute();
    redirect('courses.php');
}

$page_title = 'Kh?a h?c c?a t?i';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Kh?a H?c C?a T?i</h2>
                    <a href="create_course.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> T?o Kh?a H?c M?i
                    </a>
                </div>

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
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="badge bg-<?php echo $course['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                <?php echo $course['status'] == 'published' ? '?? xu?t b?n' : 'Nh?p'; ?>
                                            </span>
                                            <span class="badge bg-primary"><?php echo htmlspecialchars($course['category']); ?></span>
                                        </div>
                                        
                                        <h5 class="course-card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="mb-3"><?php echo substr(htmlspecialchars($course['description']), 0, 100); ?>...</p>
                                        
                                        <div class="course-meta mb-3">
                                            <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> h?c sinh</span>
                                            <span><i class="fas fa-file-alt"></i> <?php echo $course['lesson_count']; ?> b?i</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-clipboard-check"></i> <?php echo $course['quiz_count']; ?> quiz
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="course_edit.php?id=<?php echo $course['id']; ?>" class="btn btn-primary flex-grow-1">
                                                <i class="fas fa-edit"></i> Ch?nh s?a
                                            </a>
                                            <a href="course_view.php?id=<?php echo $course['id']; ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                            <a href="?delete=<?php echo $course['id']; ?>" class="btn btn-danger" onclick="return confirm('X?a kh?a h?c n?y? T?t c? d? li?u li?n quan s? b? x?a!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">B?n ch?a t?o kh?a h?c n?o</h4>
                        <p class="text-muted">H?y t?o kh?a h?c ??u ti?n v? chia s? ki?n th?c c?a b?n</p>
                        <a href="create_course.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus"></i> T?o Kh?a H?c ??u Ti?n
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
