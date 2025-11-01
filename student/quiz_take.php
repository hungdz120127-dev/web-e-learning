<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student_id = $_SESSION['user_id'];

// Get quiz details
$quiz_sql = "SELECT q.*, c.id as course_id FROM quizzes q JOIN courses c ON q.course_id = c.id WHERE q.id = ?";
$quiz_stmt = $conn->prepare($quiz_sql);
$quiz_stmt->bind_param("i", $quiz_id);
$quiz_stmt->execute();
$quiz = $quiz_stmt->get_result()->fetch_assoc();

if (!$quiz) {
    redirect('dashboard.php');
}

// Check enrollment
$check_sql = "SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $student_id, $quiz['course_id']);
$check_stmt->execute();
if ($check_stmt->get_result()->num_rows == 0) {
    redirect('dashboard.php');
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_quiz'])) {
    $attempt_id = (int)$_POST['attempt_id'];
    
    // Get questions
    $questions_sql = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
    $questions_stmt = $conn->prepare($questions_sql);
    $questions_stmt->bind_param("i", $quiz_id);
    $questions_stmt->execute();
    $questions = $questions_stmt->get_result();
    
    $correct = 0;
    $total = 0;
    
    while ($question = $questions->fetch_assoc()) {
        $total += $question['points'];
        $answer = isset($_POST['answer_' . $question['id']]) ? $_POST['answer_' . $question['id']] : '';
        
        if ($answer == $question['correct_answer']) {
            $correct += $question['points'];
        }
    }
    
    $score = ($correct / $total) * 100;
    
    // Update attempt
    $update_sql = "UPDATE quiz_attempts SET score = ?, total_points = ?, completed_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dii", $score, $total, $attempt_id);
    $update_stmt->execute();
    
    $_SESSION['message'] = "Ho?n th?nh b?i ki?m tra! ?i?m c?a b?n: " . number_format($score, 1) . "%";
    $_SESSION['message_type'] = $score >= $quiz['passing_score'] ? 'success' : 'warning';
    redirect('course_view.php?id=' . $quiz['course_id']);
}

// Create new attempt
$attempt_sql = "INSERT INTO quiz_attempts (student_id, quiz_id) VALUES (?, ?)";
$attempt_stmt = $conn->prepare($attempt_sql);
$attempt_stmt->bind_param("ii", $student_id, $quiz_id);
$attempt_stmt->execute();
$attempt_id = $conn->insert_id;

// Get questions
$questions_sql = "SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY id";
$questions_stmt = $conn->prepare($questions_sql);
$questions_stmt->bind_param("i", $quiz_id);
$questions_stmt->execute();
$questions = $questions_stmt->get_result();

$page_title = $quiz['title'];
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-clipboard-check"></i> <?php echo htmlspecialchars($quiz['title']); ?></h4>
                            <div id="timer" class="badge bg-danger" style="font-size: 1.2rem;">
                                <i class="fas fa-clock"></i> <span id="time"><?php echo $quiz['duration']; ?>:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($quiz['description']): ?>
                            <div class="alert alert-info">
                                <?php echo htmlspecialchars($quiz['description']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" id="quizForm">
                            <input type="hidden" name="attempt_id" value="<?php echo $attempt_id; ?>">
                            
                            <?php $question_num = 1; ?>
                            <?php while($question = $questions->fetch_assoc()): ?>
                                <div class="quiz-question">
                                    <h5>C?u <?php echo $question_num++; ?>: <?php echo htmlspecialchars($question['question']); ?> 
                                        <span class="badge bg-primary"><?php echo $question['points']; ?> ?i?m</span>
                                    </h5>
                                    
                                    <div class="quiz-option">
                                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="A" id="q<?php echo $question['id']; ?>a" required>
                                        <label for="q<?php echo $question['id']; ?>a">A. <?php echo htmlspecialchars($question['option_a']); ?></label>
                                    </div>
                                    
                                    <div class="quiz-option">
                                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="B" id="q<?php echo $question['id']; ?>b">
                                        <label for="q<?php echo $question['id']; ?>b">B. <?php echo htmlspecialchars($question['option_b']); ?></label>
                                    </div>
                                    
                                    <div class="quiz-option">
                                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="C" id="q<?php echo $question['id']; ?>c">
                                        <label for="q<?php echo $question['id']; ?>c">C. <?php echo htmlspecialchars($question['option_c']); ?></label>
                                    </div>
                                    
                                    <div class="quiz-option">
                                        <input type="radio" name="answer_<?php echo $question['id']; ?>" value="D" id="q<?php echo $question['id']; ?>d">
                                        <label for="q<?php echo $question['id']; ?>d">D. <?php echo htmlspecialchars($question['option_d']); ?></label>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <div class="text-center mt-4">
                                <button type="submit" name="submit_quiz" class="btn btn-success btn-lg" onclick="return confirm('B?n c? ch?c mu?n n?p b?i?')">
                                    <i class="fas fa-paper-plane"></i> N?p B?i
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Quiz Timer
let duration = <?php echo $quiz['duration']; ?> * 60; // Convert to seconds
let timer = setInterval(function() {
    let minutes = Math.floor(duration / 60);
    let seconds = duration % 60;
    
    seconds = seconds < 10 ? '0' + seconds : seconds;
    
    document.getElementById('time').textContent = minutes + ':' + seconds;
    
    if (--duration < 0) {
        clearInterval(timer);
        alert('H?t gi?! B?i l?m s? ???c t? ??ng n?p.');
        document.getElementById('quizForm').submit();
    }
}, 1000);
</script>

<?php include '../includes/footer.php'; ?>
