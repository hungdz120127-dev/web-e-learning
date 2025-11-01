<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Build query
$sql = "SELECT c.*, u.full_name as teacher_name,
        (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
        (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
        (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND student_id = ?) as is_enrolled
        FROM courses c 
        JOIN users u ON c.teacher_id = u.id 
        WHERE c.status = 'published'";

$params = [$student_id];
$types = "i";

if ($search) {
    $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($category) {
    $sql .= " AND c.category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$courses = $stmt->get_result();

$page_title = 'T?m kh?a h?c';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">T?m Kh?a H?c</h2>

                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="T?m ki?m kh?a h?c..." 
                                           value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" name="category">
                                        <option value="">T?t c? danh m?c</option>
                                        <option value="To?n h?c" <?php echo $category == 'To?n h?c' ? 'selected' : ''; ?>>To?n h?c</option>
                                        <option value="L? h?c" <?php echo $category == 'L? h?c' ? 'selected' : ''; ?>>L? h?c</option>
                                        <option value="H?a h?c" <?php echo $category == 'H?a h?c' ? 'selected' : ''; ?>>H?a h?c</option>
                                        <option value="Sinh h?c" <?php echo $category == 'Sinh h?c' ? 'selected' : ''; ?>>Sinh h?c</option>
                                        <option value="V?n h?c" <?php echo $category == 'V?n h?c' ? 'selected' : ''; ?>>V?n h?c</option>
                                        <option value="Ti?ng Anh" <?php echo $category == 'Ti?ng Anh' ? 'selected' : ''; ?>>Ti?ng Anh</option>
                                        <option value="L?p tr?nh" <?php echo $category == 'L?p tr?nh' ? 'selected' : ''; ?>>L?p tr?nh</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> T?m
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Courses -->
                <div class="row">
                    <?php if ($courses->num_rows > 0): ?>
                        <?php while($course = $courses->fetch_assoc()): ?>
                            <div class="col-md-4">
                                <div class="course-card">
                                    <?php if ($course['thumbnail']): ?>
                                        <img src="../uploads/courses/<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book fa-4x text-white" style="opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="course-card-body">
                                        <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($course['category']); ?></span>
                                        <h5 class="course-card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($course['teacher_name']); ?>
                                        </p>
                                        <p class="mb-3"><?php echo substr(htmlspecialchars($course['description']), 0, 100); ?>...</p>
                                        <div class="course-meta mb-3">
                                            <span><i class="fas fa-users"></i> <?php echo $course['student_count']; ?></span>
                                            <span><i class="fas fa-file-alt"></i> <?php echo $course['lesson_count']; ?> b?i</span>
                                        </div>
                                        <?php if ($course['is_enrolled'] > 0): ?>
                                            <a href="course_view.php?id=<?php echo $course['id']; ?>" class="btn btn-success w-100">
                                                <i class="fas fa-check"></i> ?? ??ng k? - V?o h?c
                                            </a>
                                        <?php else: ?>
                                            <a href="enroll.php?id=<?php echo $course['id']; ?>" class="btn btn-primary w-100">
                                                <i class="fas fa-plus"></i> ??ng k? ngay
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Kh?ng t?m th?y kh?a h?c n?o</h4>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
