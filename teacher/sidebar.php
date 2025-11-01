<?php
// Get pending assignments count if not already set
if (!isset($pending_count)) {
    $pending_sql = "SELECT COUNT(*) as total 
                    FROM assignment_submissions asub 
                    JOIN assignments a ON asub.assignment_id = a.id 
                    JOIN courses c ON a.course_id = c.id 
                    WHERE c.teacher_id = ? AND asub.grade IS NULL";
    $pending_stmt = $conn->prepare($pending_sql);
    $pending_stmt->bind_param("i", $_SESSION['user_id']);
    $pending_stmt->execute();
    $pending_count = $pending_stmt->get_result()->fetch_assoc()['total'];
}

// Get unread messages count if not already set
if (!isset($unread_count)) {
    $unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND read_status = 0";
    $unread_stmt = $conn->prepare($unread_sql);
    $unread_stmt->bind_param("i", $_SESSION['user_id']);
    $unread_stmt->execute();
    $unread_count = $unread_stmt->get_result()->fetch_assoc()['total'];
}
?>
<div class="col-md-2 p-0 sidebar">
    <div class="nav flex-column">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
            <i class="fas fa-home"></i> Trang ch?
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>" href="courses.php">
            <i class="fas fa-book"></i> Kh?a h?c c?a t?i
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_course.php' ? 'active' : ''; ?>" href="create_course.php">
            <i class="fas fa-plus-circle"></i> T?o kh?a h?c
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>" href="students.php">
            <i class="fas fa-users"></i> H?c sinh
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'assignments.php' ? 'active' : ''; ?>" href="assignments.php">
            <i class="fas fa-tasks"></i> B?i t?p
            <?php if ($pending_count > 0): ?>
                <span class="badge bg-warning"><?php echo $pending_count; ?></span>
            <?php endif; ?>
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'quizzes.php' ? 'active' : ''; ?>" href="quizzes.php">
            <i class="fas fa-clipboard-check"></i> Quiz
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
            <i class="fas fa-envelope"></i> Tin nh?n
            <?php if ($unread_count > 0): ?>
                <span class="badge bg-danger"><?php echo $unread_count; ?></span>
            <?php endif; ?>
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" href="reports.php">
            <i class="fas fa-chart-bar"></i> B?o c?o
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
            <i class="fas fa-user"></i> H? s?
        </a>
    </div>
</div>
