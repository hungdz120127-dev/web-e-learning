<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get course
$course_sql = "SELECT * FROM courses WHERE id = ? AND teacher_id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("ii", $course_id, $teacher_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

if (!$course) {
    redirect('courses.php');
}

// Add lesson
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lesson'])) {
    $title = sanitize($_POST['lesson_title']);
    $content = sanitize($_POST['lesson_content']);
    $video_url = sanitize($_POST['video_url']);
    $duration = (int)$_POST['duration'];
    $order_num = (int)$_POST['order_num'];
    
    $lesson_sql = "INSERT INTO lessons (course_id, title, content, video_url, duration, order_num) VALUES (?, ?, ?, ?, ?, ?)";
    $lesson_stmt = $conn->prepare($lesson_sql);
    $lesson_stmt->bind_param("issiii", $course_id, $title, $content, $video_url, $duration, $order_num);
    $lesson_stmt->execute();
    
    $_SESSION['message'] = "Th?m b?i gi?ng th?nh c?ng!";
    $_SESSION['message_type'] = "success";
    redirect('course_edit.php?id=' . $course_id);
}

// Delete lesson
if (isset($_GET['delete_lesson'])) {
    $lesson_id = (int)$_GET['delete_lesson'];
    $del_sql = "DELETE FROM lessons WHERE id = ? AND course_id = ?";
    $del_stmt = $conn->prepare($del_sql);
    $del_stmt->bind_param("ii", $lesson_id, $course_id);
    $del_stmt->execute();
    redirect('course_edit.php?id=' . $course_id);
}

// Get lessons
$lessons_sql = "SELECT * FROM lessons WHERE course_id = ? ORDER BY order_num ASC";
$lessons_stmt = $conn->prepare($lessons_sql);
$lessons_stmt->bind_param("i", $course_id);
$lessons_stmt->execute();
$lessons = $lessons_stmt->get_result();

$page_title = 'Ch?nh s?a: ' . $course['title'];
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">Ch?nh S?a Kh?a H?c: <?php echo htmlspecialchars($course['title']); ?></h2>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Add Lesson Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-plus"></i> Th?m B?i Gi?ng</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">Ti?u ?? *</label>
                                        <input type="text" class="form-control" name="lesson_title" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">N?i dung</label>
                                        <textarea class="form-control" name="lesson_content" rows="4"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Video URL (YouTube/Vimeo)</label>
                                        <input type="text" class="form-control" name="video_url" placeholder="https://www.youtube.com/embed/...">
                                        <small class="text-muted">S? d?ng link embed</small>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Th?i l??ng (ph?t)</label>
                                            <input type="number" class="form-control" name="duration" value="0">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Th? t?</label>
                                            <input type="number" class="form-control" name="order_num" value="0">
                                        </div>
                                    </div>
                                    
                                    <button type="submit" name="add_lesson" class="btn btn-primary w-100">
                                        <i class="fas fa-plus"></i> Th?m B?i Gi?ng
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Lessons List -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-book-open"></i> B?i Gi?ng</h5>
                                <a href="create_quiz.php?course_id=<?php echo $course_id; ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> Th?m Quiz
                                </a>
                            </div>
                            <div class="card-body">
                                <?php if ($lessons->num_rows > 0): ?>
                                    <?php $lesson_num = 1; ?>
                                    <?php while($lesson = $lessons->fetch_assoc()): ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h5>B?i <?php echo $lesson_num++; ?>: <?php echo htmlspecialchars($lesson['title']); ?></h5>
                                                        <?php if ($lesson['duration']): ?>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock"></i> <?php echo $lesson['duration']; ?> ph?t
                                                            </small>
                                                        <?php endif; ?>
                                                        <?php if ($lesson['content']): ?>
                                                            <p class="mt-2"><?php echo substr(nl2br(htmlspecialchars($lesson['content'])), 0, 200); ?>...</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="?id=<?php echo $course_id; ?>&delete_lesson=<?php echo $lesson['id']; ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('X?a b?i gi?ng n?y?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center">Ch?a c? b?i gi?ng n?o. Th?m b?i gi?ng ??u ti?n!</p>
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
