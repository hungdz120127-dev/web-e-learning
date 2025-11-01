<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student_id = $_SESSION['user_id'];

// Check enrollment
$check_sql = "SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $student_id, $course_id);
$check_stmt->execute();
$enrollment = $check_stmt->get_result()->fetch_assoc();

if (!$enrollment) {
    redirect('browse.php');
}

// Get course details
$course_sql = "SELECT c.*, u.full_name as teacher_name, u.email as teacher_email 
               FROM courses c 
               JOIN users u ON c.teacher_id = u.id 
               WHERE c.id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

// Get lessons
$lessons_sql = "SELECT l.*, 
                (SELECT completed FROM lesson_progress WHERE lesson_id = l.id AND student_id = ?) as completed
                FROM lessons l 
                WHERE l.course_id = ? 
                ORDER BY l.order_num ASC";
$lessons_stmt = $conn->prepare($lessons_sql);
$lessons_stmt->bind_param("ii", $student_id, $course_id);
$lessons_stmt->execute();
$lessons = $lessons_stmt->get_result();

// Get quizzes
$quizzes_sql = "SELECT q.*, 
                (SELECT MAX(score) FROM quiz_attempts WHERE quiz_id = q.id AND student_id = ?) as best_score
                FROM quizzes q 
                WHERE q.course_id = ?";
$quizzes_stmt = $conn->prepare($quizzes_sql);
$quizzes_stmt->bind_param("ii", $student_id, $course_id);
$quizzes_stmt->execute();
$quizzes = $quizzes_stmt->get_result();

// Mark lesson as completed
if (isset($_POST['mark_complete'])) {
    $lesson_id = (int)$_POST['lesson_id'];
    
    $progress_sql = "INSERT INTO lesson_progress (student_id, lesson_id, completed, completed_at) 
                     VALUES (?, ?, 1, NOW()) 
                     ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()";
    $progress_stmt = $conn->prepare($progress_sql);
    $progress_stmt->bind_param("ii", $student_id, $lesson_id);
    $progress_stmt->execute();
    
    // Update course progress
    $update_progress_sql = "UPDATE enrollments e 
                           SET progress = (
                               SELECT ROUND((COUNT(CASE WHEN lp.completed = 1 THEN 1 END) * 100.0 / COUNT(*)), 0)
                               FROM lessons l 
                               LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.student_id = ?
                               WHERE l.course_id = ?
                           )
                           WHERE e.student_id = ? AND e.course_id = ?";
    $update_stmt = $conn->prepare($update_progress_sql);
    $update_stmt->bind_param("iiii", $student_id, $course_id, $student_id, $course_id);
    $update_stmt->execute();
    
    redirect('course_view.php?id=' . $course_id);
}

$page_title = $course['title'];
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <!-- Course Header -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-user"></i> Gi?o vi?n: <?php echo htmlspecialchars($course['teacher_name']); ?>
                                </p>
                                <p class="mb-3"><?php echo htmlspecialchars($course['description']); ?></p>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($course['category']); ?></span>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <h4>Ti?n ?? h?c t?p</h4>
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar" style="width: <?php echo $enrollment['progress']; ?>%; font-size: 1.1rem;">
                                                <?php echo $enrollment['progress']; ?>%
                                            </div>
                                        </div>
                                    </div>
                                    <a href="messages.php?teacher_id=<?php echo $course['teacher_id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-envelope"></i> Nh?n tin cho gi?o vi?n
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Lessons -->
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-book-open"></i> B?i Gi?ng</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($lessons->num_rows > 0): ?>
                                    <?php $lesson_num = 1; ?>
                                    <?php while($lesson = $lessons->fetch_assoc()): ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h5>
                                                            <?php if ($lesson['completed']): ?>
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            <?php else: ?>
                                                                <i class="far fa-circle text-muted"></i>
                                                            <?php endif; ?>
                                                            B?i <?php echo $lesson_num++; ?>: <?php echo htmlspecialchars($lesson['title']); ?>
                                                        </h5>
                                                        <?php if ($lesson['duration']): ?>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock"></i> <?php echo $lesson['duration']; ?> ph?t
                                                            </small>
                                                        <?php endif; ?>
                                                        
                                                        <div class="mt-3">
                                                            <?php if ($lesson['video_url']): ?>
                                                                <div class="mb-3">
                                                                    <div class="ratio ratio-16x9">
                                                                        <iframe src="<?php echo htmlspecialchars($lesson['video_url']); ?>" allowfullscreen></iframe>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <div><?php echo nl2br(htmlspecialchars($lesson['content'])); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <?php if (!$lesson['completed']): ?>
                                                    <form method="POST" class="mt-3">
                                                        <input type="hidden" name="lesson_id" value="<?php echo $lesson['id']; ?>">
                                                        <button type="submit" name="mark_complete" class="btn btn-success">
                                                            <i class="fas fa-check"></i> ??nh d?u ho?n th?nh
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="alert alert-success mt-3 mb-0">
                                                        <i class="fas fa-check-circle"></i> B?n ?? ho?n th?nh b?i h?c n?y
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Ch?a c? b?i gi?ng n?o</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quizzes & Info -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> B?i Ki?m Tra</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($quizzes->num_rows > 0): ?>
                                    <?php while($quiz = $quizzes->fetch_assoc()): ?>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <h6><?php echo htmlspecialchars($quiz['title']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> <?php echo $quiz['duration']; ?> ph?t | 
                                                <i class="fas fa-star"></i> ?i?m ??t: <?php echo $quiz['passing_score']; ?>%
                                            </small>
                                            <?php if ($quiz['best_score'] !== null): ?>
                                                <p class="mb-2 mt-2">
                                                    <span class="badge bg-<?php echo $quiz['best_score'] >= $quiz['passing_score'] ? 'success' : 'warning'; ?>">
                                                        ?i?m cao nh?t: <?php echo $quiz['best_score']; ?>%
                                                    </span>
                                                </p>
                                            <?php endif; ?>
                                            <a href="quiz_take.php?id=<?php echo $quiz['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-play"></i> <?php echo $quiz['best_score'] !== null ? 'L?m l?i' : 'B?t ??u'; ?>
                                            </a>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Ch?a c? b?i ki?m tra</p>
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
