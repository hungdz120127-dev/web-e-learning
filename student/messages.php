<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$student_id = $_SESSION['user_id'];

// Send message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $receiver_id = (int)$_POST['receiver_id'];
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    $course_id = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
    
    $send_sql = "INSERT INTO messages (sender_id, receiver_id, course_id, subject, message) VALUES (?, ?, ?, ?, ?)";
    $send_stmt = $conn->prepare($send_sql);
    $send_stmt->bind_param("iiiss", $student_id, $receiver_id, $course_id, $subject, $message);
    
    if ($send_stmt->execute()) {
        $_SESSION['message'] = "G?i tin nh?n th?nh c?ng!";
        $_SESSION['message_type'] = "success";
    }
    redirect('messages.php');
}

// Get messages
$messages_sql = "SELECT m.*, 
                 u.full_name as sender_name,
                 c.title as course_title
                 FROM messages m 
                 JOIN users u ON m.sender_id = u.id 
                 LEFT JOIN courses c ON m.course_id = c.id
                 WHERE m.receiver_id = ? OR m.sender_id = ?
                 ORDER BY m.sent_at DESC";
$messages_stmt = $conn->prepare($messages_sql);
$messages_stmt->bind_param("ii", $student_id, $student_id);
$messages_stmt->execute();
$messages = $messages_stmt->get_result();

// Get teachers
$teachers_sql = "SELECT DISTINCT u.id, u.full_name, u.email 
                 FROM users u 
                 JOIN courses c ON u.id = c.teacher_id 
                 JOIN enrollments e ON c.id = e.course_id 
                 WHERE e.student_id = ? AND u.role = 'teacher'";
$teachers_stmt = $conn->prepare($teachers_sql);
$teachers_stmt->bind_param("i", $student_id);
$teachers_stmt->execute();
$teachers = $teachers_stmt->get_result();

// Mark messages as read
$mark_read_sql = "UPDATE messages SET read_status = 1 WHERE receiver_id = ?";
$mark_stmt = $conn->prepare($mark_read_sql);
$mark_stmt->bind_param("i", $student_id);
$mark_stmt->execute();

$page_title = 'Tin nh?n';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">Tin Nh?n</h2>

                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Compose Message -->
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-pen"></i> So?n Tin Nh?n</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="mb-3">
                                        <label class="form-label">G?i ??n</label>
                                        <select class="form-control" name="receiver_id" required>
                                            <option value="">Ch?n gi?o vi?n</option>
                                            <?php while($teacher = $teachers->fetch_assoc()): ?>
                                                <option value="<?php echo $teacher['id']; ?>">
                                                    <?php echo htmlspecialchars($teacher['full_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Ti?u ??</label>
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">N?i dung</label>
                                        <textarea class="form-control" name="message" rows="4" required></textarea>
                                    </div>
                                    
                                    <button type="submit" name="send_message" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane"></i> G?i
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Messages List -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-inbox"></i> H?p Th?</h5>
                            </div>
                            <div class="card-body">
                                <div class="message-list">
                                    <?php if ($messages->num_rows > 0): ?>
                                        <?php while($msg = $messages->fetch_assoc()): ?>
                                            <div class="message-item <?php echo (!$msg['read_status'] && $msg['receiver_id'] == $student_id) ? 'unread' : ''; ?>">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">
                                                            <?php if ($msg['sender_id'] == $student_id): ?>
                                                                <span class="badge bg-success">?? g?i</span>
                                                            <?php else: ?>
                                                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($msg['sender_name']); ?>
                                                            <?php endif; ?>
                                                        </h6>
                                                        <h6><?php echo htmlspecialchars($msg['subject']); ?></h6>
                                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                                                        <?php if ($msg['course_title']): ?>
                                                            <small class="text-muted">
                                                                <i class="fas fa-book"></i> <?php echo htmlspecialchars($msg['course_title']); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <small class="text-muted"><?php echo timeAgo($msg['sent_at']); ?></small>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Ch?a c? tin nh?n n?o</p>
                                        </div>
                                    <?php endif; ?>
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
