<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];

// Get teacher's courses count
$courses_sql = "SELECT COUNT(*) as total FROM courses WHERE teacher_id = ?";
$courses_stmt = $conn->prepare($courses_sql);
$courses_stmt->bind_param("i", $teacher_id);
$courses_stmt->execute();
$courses_count = $courses_stmt->get_result()->fetch_assoc()['total'];

// Get total students enrolled
$students_sql = "SELECT COUNT(DISTINCT e.student_id) as total 
                 FROM enrollments e 
                 JOIN courses c ON e.course_id = c.id 
                 WHERE c.teacher_id = ?";
$students_stmt = $conn->prepare($students_sql);
$students_stmt->bind_param("i", $teacher_id);
$students_stmt->execute();
$students_count = $students_stmt->get_result()->fetch_assoc()['total'];

// Get pending assignments
$pending_sql = "SELECT COUNT(*) as total 
                FROM assignment_submissions asub 
                JOIN assignments a ON asub.assignment_id = a.id 
                JOIN courses c ON a.course_id = c.id 
                WHERE c.teacher_id = ? AND asub.grade IS NULL";
$pending_stmt = $conn->prepare($pending_sql);
$pending_stmt->bind_param("i", $teacher_id);
$pending_stmt->execute();
$pending_count = $pending_stmt->get_result()->fetch_assoc()['total'];

// Get unread messages
$unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND read_status = 0";
$unread_stmt = $conn->prepare($unread_sql);
$unread_stmt->bind_param("i", $teacher_id);
$unread_stmt->execute();
$unread_count = $unread_stmt->get_result()->fetch_assoc()['total'];

// Get teacher's courses
$my_courses_sql = "SELECT c.*, 
                   (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                   (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
                   FROM courses c 
                   WHERE c.teacher_id = ? 
                   ORDER BY c.created_at DESC LIMIT 6";
$my_courses_stmt = $conn->prepare($my_courses_sql);
$my_courses_stmt->bind_param("i", $teacher_id);
$my_courses_stmt->execute();
$my_courses = $my_courses_stmt->get_result();

// Get recent enrollments
$recent_enroll_sql = "SELECT e.*, c.title as course_title, u.full_name as student_name 
                      FROM enrollments e 
                      JOIN courses c ON e.course_id = c.id 
                      JOIN users u ON e.student_id = u.id 
                      WHERE c.teacher_id = ? 
                      ORDER BY e.enrolled_at DESC LIMIT 5";
$recent_stmt = $conn->prepare($recent_enroll_sql);
$recent_stmt->bind_param("i", $teacher_id);
$recent_stmt->execute();
$recent_enrollments = $recent_stmt->get_result();

$page_title = 'Dashboard - Gi?o vi?n';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

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
                                    <h3 class="mb-0"><?php echo $courses_count; ?></h3>
                                    <p class="text-muted mb-0">Kh?a h?c</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card success">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo $students_count; ?></h3>
                                    <p class="text-muted mb-0">H?c sinh</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card warning">
                            <div class="d-flex align-items-center">
                                <div class="icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0"><?php echo $pending_count; ?></h3>
                                    <p class="text-muted mb-0">B?i ch? ch?m</p>
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
                                <a href="create_course.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> T?o kh?a h?c
                                </a>
                            </div>
                            <div class="card-body">
                                <?php if ($my_courses->num_rows > 0): ?>
                                    <div class="row">
                                        <?php while($course = $my_courses->fetch_assoc()): ?>
                                            <div class="col-md-6">
                                                <div class="course-card">
                                                    <?php if ($course['thumbnail']): ?>
                                                        <img src="../uploads/courses/<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                                    <?php else: ?>
                                                        <div style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                                                    <?php endif; ?>
                                                    <div class="course-card-body">
                                                        <h5 class="course-card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                                        <div class="course-meta">
                                                            <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?> h?c sinh</span>
                                                            <span><i class="fas fa-file-alt"></i> <?php echo $course['lesson_count']; ?> b?i</span>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="course_edit.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-edit"></i> Ch?nh s?a
                                                            </a>
                                                            <a href="course_view.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Xem
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">B?n ch?a t?o kh?a h?c n?o</p>
                                        <a href="create_course.php" class="btn btn-primary">T?o kh?a h?c ??u ti?n</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Enrollments -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user-plus"></i> ??ng k? g?n ??y</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($recent_enrollments->num_rows > 0): ?>
                                    <?php while($enroll = $recent_enrollments->fetch_assoc()): ?>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($enroll['student_name']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($enroll['course_title']); ?>
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> <?php echo timeAgo($enroll['enrolled_at']); ?>
                                            </small>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Ch?a c? ??ng k? m?i</p>
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
