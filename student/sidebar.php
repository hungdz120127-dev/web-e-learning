<?php
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
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'browse.php' ? 'active' : ''; ?>" href="browse.php">
            <i class="fas fa-search"></i> T?m kh?a h?c
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'quizzes.php' ? 'active' : ''; ?>" href="quizzes.php">
            <i class="fas fa-clipboard-check"></i> B?i ki?m tra
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'assignments.php' ? 'active' : ''; ?>" href="assignments.php">
            <i class="fas fa-tasks"></i> B?i t?p
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>" href="messages.php">
            <i class="fas fa-envelope"></i> Tin nh?n
            <?php if ($unread_count > 0): ?>
                <span class="badge bg-danger"><?php echo $unread_count; ?></span>
            <?php endif; ?>
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'progress.php' ? 'active' : ''; ?>" href="progress.php">
            <i class="fas fa-chart-line"></i> Ti?n ?? h?c t?p
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
            <i class="fas fa-user"></i> H? s?
        </a>
    </div>
</div>
