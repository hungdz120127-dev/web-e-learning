<?php
require_once 'config/config.php';

// If already logged in, redirect to appropriate dashboard
if (isLoggedIn()) {
    if (isTeacher()) {
        redirect('teacher/dashboard.php');
    } elseif (isStudent()) {
        redirect('student/dashboard.php');
    } elseif (isAdmin()) {
        redirect('admin/dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Trang ch?</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap"></i> <?php echo SITE_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang ch?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">??ng nh?p</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">??ng k?</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 fade-in">
                    <h1 class="display-4 fw-bold mb-4">H?c T?p Tr?c Tuy?n Hi?u Qu?</h1>
                    <p class="lead mb-4">N?n t?ng e-learning hi?n ??i gi?p h?c sinh t? h?c v? gi?o vi?n d? d?ng qu?n l? b?i gi?ng, quiz v? giao ti?p v?i h?c sinh.</p>
                    <div class="d-flex gap-3">
                        <a href="register.php" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus"></i> ??ng k? ngay
                        </a>
                        <a href="login.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt"></i> ??ng nh?p
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-laptop-code" style="font-size: 300px; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">T?nh N?ng N?i B?t</h2>
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-book-reader fa-4x text-primary"></i>
                        </div>
                        <h4>Kh?a H?c ?a D?ng</h4>
                        <p class="text-muted">H?ng tr?m kh?a h?c ch?t l??ng cao t? c?c gi?o vi?n gi?i</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in" style="animation-delay: 0.1s;">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clipboard-check fa-4x text-success"></i>
                        </div>
                        <h4>Quiz & B?i T?p</h4>
                        <p class="text-muted">H? th?ng b?i ki?m tra v? b?i t?p t? ??ng ch?m ?i?m</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in" style="animation-delay: 0.2s;">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-comments fa-4x text-warning"></i>
                        </div>
                        <h4>Giao Ti?p Tr?c Ti?p</h4>
                        <p class="text-muted">Chat v? nh?n tin gi?a gi?o vi?n v? h?c sinh</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in" style="animation-delay: 0.3s;">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-chart-line fa-4x text-info"></i>
                        </div>
                        <h4>Theo D?i Ti?n ??</h4>
                        <p class="text-muted">B?o c?o chi ti?t v? ti?n ?? h?c t?p c?a h?c sinh</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in" style="animation-delay: 0.4s;">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-video fa-4x text-danger"></i>
                        </div>
                        <h4>Video B?i Gi?ng</h4>
                        <p class="text-muted">H?c qua video ch?t l??ng cao, c? th? xem l?i nhi?u l?n</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in" style="animation-delay: 0.5s;">
                    <div class="card h-100 text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-certificate fa-4x text-secondary"></i>
                        </div>
                        <h4>Ch?ng Ch?</h4>
                        <p class="text-muted">Nh?n ch?ng ch? ho?n th?nh kh?a h?c c? gi? tr?</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold">1000+</h2>
                    <p class="lead">H?c Sinh</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold">150+</h2>
                    <p class="lead">Gi?o Vi?n</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold">500+</h2>
                    <p class="lead">Kh?a H?c</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h2 class="display-4 fw-bold">98%</h2>
                    <p class="lead">H?i L?ng</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 <?php echo SITE_NAME; ?>. All rights reserved.</p>
            <p class="mb-0 mt-2">
                <a href="#" class="text-white text-decoration-none me-3"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white text-decoration-none me-3"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white text-decoration-none"><i class="fab fa-youtube"></i></a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
