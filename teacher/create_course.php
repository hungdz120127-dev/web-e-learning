<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isTeacher()) {
    redirect('../login.php');
}

$teacher_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $status = $_POST['status'];
    
    $thumbnail = '';
    
    // Handle file upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['thumbnail']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $thumbnail = uniqid() . '.' . $ext;
            $upload_path = '../uploads/courses/' . $thumbnail;
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_path);
        }
    }
    
    $sql = "INSERT INTO courses (teacher_id, title, description, category, thumbnail, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $teacher_id, $title, $description, $category, $thumbnail, $status);
    
    if ($stmt->execute()) {
        $course_id = $conn->insert_id;
        $_SESSION['message'] = "T?o kh?a h?c th?nh c?ng!";
        $_SESSION['message_type'] = "success";
        redirect('course_edit.php?id=' . $course_id);
    } else {
        $error = "C? l?i x?y ra, vui l?ng th? l?i!";
    }
}

$page_title = 'T?o kh?a h?c m?i';
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-10 main-content">
                <h2 class="mb-4">T?o Kh?a H?c M?i</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Ti?u ?? kh?a h?c *</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">M? t?</label>
                                <textarea class="form-control" name="description" rows="5"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Danh m?c</label>
                                    <select class="form-control" name="category">
                                        <option value="To?n h?c">To?n h?c</option>
                                        <option value="L? h?c">L? h?c</option>
                                        <option value="H?a h?c">H?a h?c</option>
                                        <option value="Sinh h?c">Sinh h?c</option>
                                        <option value="V?n h?c">V?n h?c</option>
                                        <option value="Ti?ng Anh">Ti?ng Anh</option>
                                        <option value="L?p tr?nh">L?p tr?nh</option>
                                        <option value="Kh?c">Kh?c</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tr?ng th?i</label>
                                    <select class="form-control" name="status">
                                        <option value="draft">Nh?p</option>
                                        <option value="published">Xu?t b?n</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">?nh ??i di?n</label>
                                <input type="file" class="form-control" name="thumbnail" accept="image/*">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> T?o kh?a h?c
                                </button>
                                <a href="courses.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> H?y
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
