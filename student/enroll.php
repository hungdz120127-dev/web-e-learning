<?php
require_once '../config/config.php';

if (!isLoggedIn() || !isStudent()) {
    redirect('../login.php');
}

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$student_id = $_SESSION['user_id'];

if ($course_id > 0) {
    // Check if already enrolled
    $check_sql = "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $student_id, $course_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        $_SESSION['message'] = "B?n ?? ??ng k? kh?a h?c n?y r?i!";
        $_SESSION['message_type'] = "warning";
    } else {
        // Enroll student
        $enroll_sql = "INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)";
        $enroll_stmt = $conn->prepare($enroll_sql);
        $enroll_stmt->bind_param("ii", $student_id, $course_id);
        
        if ($enroll_stmt->execute()) {
            $_SESSION['message'] = "??ng k? kh?a h?c th?nh c?ng!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "C? l?i x?y ra, vui l?ng th? l?i!";
            $_SESSION['message_type'] = "danger";
        }
    }
}

redirect('courses.php');
?>
