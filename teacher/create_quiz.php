<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Verify course ownership
$course_sql = "SELECT * FROM courses WHERE id = ? AND teacher_id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("ii", $course_id, $teacher_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

if (!$course) {
    redirect('courses.php');
}

$error = '';
$success = '';

// Create quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_quiz'])) {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $duration = (int)$_POST['duration'];
    $passing_score = (int)$_POST['passing_score'];
    
    $quiz_sql = "INSERT INTO quizzes (course_id, title, description, duration, passing_score) VALUES (?, ?, ?, ?, ?)";
    $quiz_stmt = $conn->prepare($quiz_sql);
    $quiz_stmt->bind_param("issii", $course_id, $title, $description, $duration, $passing_score);
    
    if ($quiz_stmt->execute()) {
        $quiz_id = $conn->insert_id;
        
        // Add questions
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
            foreach ($_POST['questions'] as $q) {
                if (!empty($q['question'])) {
                    $question_sql = "INSERT INTO quiz_questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_answer, points) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $q_stmt = $conn->prepare($question_sql);
                    $q_stmt->bind_param("issssssi", $quiz_id, $q['question'], $q['option_a'], $q['option_b'], $q['option_c'], $q['option_d'], $q['correct'], $q['points']);
                    $q_stmt->execute();
                }
            }
        }
        
        $_SESSION['message'] = "T?o quiz th?nh c?ng!";
        $_SESSION['message_type'] = "success";
        redirect('course_edit.php?id=' . $course_id);
    } else {
        $error = "C? l?i x?y ra!";
    }
}

$page_title = 'T?o Quiz';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="mb-4">T?o Quiz M?i - <?php echo htmlspecialchars($course['title']); ?></h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="" id="quizForm">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Th?ng Tin Quiz</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Ti?u ?? *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">M? t?</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Th?i gian (ph?t) *</label>
                                    <input type="number" class="form-control" name="duration" value="30" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">?i?m ??t (%) *</label>
                                    <input type="number" class="form-control" name="passing_score" value="70" min="0" max="100" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">C?u H?i</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion()">
                                <i class="fas fa-plus"></i> Th?m C?u H?i
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="questionsContainer">
                                <!-- Questions will be added here -->
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="create_quiz" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> T?o Quiz
                        </button>
                        <a href="course_edit.php?id=<?php echo $course_id; ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> H?y
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    const questionDiv = document.createElement('div');
    questionDiv.className = 'card mb-3';
    questionDiv.id = 'question_' + questionCount;
    questionDiv.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h6>C?u h?i ${questionCount}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(${questionCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="mb-3">
                <label class="form-label">C?u h?i *</label>
                <input type="text" class="form-control" name="questions[${questionCount}][question]" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">??p ?n A *</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][option_a]" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">??p ?n B *</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][option_b]" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">??p ?n C *</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][option_c]" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">??p ?n D *</label>
                    <input type="text" class="form-control" name="questions[${questionCount}][option_d]" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">??p ?n ??ng *</label>
                    <select class="form-control" name="questions[${questionCount}][correct]" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">?i?m *</label>
                    <input type="number" class="form-control" name="questions[${questionCount}][points]" value="1" min="1" required>
                </div>
            </div>
        </div>
    `;
    container.appendChild(questionDiv);
}

function removeQuestion(id) {
    if (confirm('X?a c?u h?i n?y?')) {
        document.getElementById('question_' + id).remove();
    }
}

// Add first question on load
window.onload = function() {
    addQuestion();
};
</script>

<?php include '../includes/footer.php'; ?>
