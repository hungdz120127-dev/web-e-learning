<?php
// Site configuration
define('SITE_NAME', 'E-Learning Platform');
define('SITE_URL', 'http://localhost/workspace');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once __DIR__ . '/database.php';

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isTeacher() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'teacher';
}

function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

function showAlert($message, $type = 'info') {
    return "<div class='alert alert-$type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
}

function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;
    
    if ($difference < 60) return 'v?a xong';
    if ($difference < 3600) return floor($difference / 60) . ' ph?t tr??c';
    if ($difference < 86400) return floor($difference / 3600) . ' gi? tr??c';
    if ($difference < 604800) return floor($difference / 86400) . ' ng?y tr??c';
    
    return date('d/m/Y', $timestamp);
}
?>
