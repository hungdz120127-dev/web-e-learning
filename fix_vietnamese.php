<?php
/**
 * Script t? ??ng fix ti?ng Vi?t trong t?t c? file PHP
 * Ch?y file n?y: http://localhost/workspace/fix_vietnamese.php
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Fix Ti?ng Vi?t T? ??ng</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        h1 { color: #4361ee; }
        .btn { display: inline-block; padding: 15px 30px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; cursor: pointer; border: none; font-size: 16px; }
        .btn:hover { background: #3651de; }
        .result { background: #f0f8ff; padding: 20px; border-left: 4px solid #4361ee; margin: 20px 0; }
        .success { background: #d4edda; border-left-color: #28a745; color: #155724; }
        .error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>?? Fix Ti?ng Vi?t T? ??ng</h1>
        
        <?php
        if (isset($_POST['fix_now'])) {
            echo '<div class="result success">';
            echo '<h3>? ?ang x? l?...</h3>';
            
            $replacements = [
                // Basic navigation
                'Trang ch?' => 'Trang ch?',
                '??ng nh?p' => '??ng nh?p',
                '??ng k?' => '??ng k?',
                '??ng xu?t' => '??ng xu?t',
                
                // User roles
                'H?c sinh' => 'H?c sinh',
                'Gi?o vi?n' => 'Gi?o vi?n',
                'Qu?n tr? vi?n' => 'Qu?n tr? vi?n',
                
                // Course related
                'Kh?a h?c' => 'Kh?a h?c',
                'Kh?a h?c c?a t?i' => 'Kh?a h?c c?a t?i',
                'T?o kh?a h?c' => 'T?o kh?a h?c',
                'T?m kh?a h?c' => 'T?m kh?a h?c',
                'Ch?nh s?a' => 'Ch?nh s?a',
                
                // Lessons
                'B?i gi?ng' => 'B?i gi?ng',
                'B?i gi?ng' => 'B?i gi?ng',
                'Th?m b?i gi?ng' => 'Th?m b?i gi?ng',
                'Th?m b?i gi?ng' => 'Th?m b?i gi?ng',
                
                // Quiz
                'B?i ki?m tra' => 'B?i ki?m tra',
                'B?i ki?m tra' => 'B?i ki?m tra',
                'T?o quiz' => 'T?o quiz',
                'L?m b?i' => 'L?m b?i',
                'N?p b?i' => 'N?p b?i',
                'C?u h?i' => 'C?u h?i',
                'C?u h?i' => 'C?u h?i',
                '??p ?n' => '??p ?n',
                
                // Progress
                'Ti?n ??' => 'Ti?n ??',
                'Ti?n ?? h?c t?p' => 'Ti?n ?? h?c t?p',
                'Ti?n ?? h?c t?p' => 'Ti?n ?? h?c t?p',
                '?? ho?n th?nh' => '?? ho?n th?nh',
                'Ch?a ho?n th?nh' => 'Ch?a ho?n th?nh',
                '?ang h?c' => '?ang h?c',
                
                // Messages
                'Tin nh?n' => 'Tin nh?n',
                'Tin nh?n m?i' => 'Tin nh?n m?i',
                'So?n tin' => 'So?n tin',
                'G?i ??n' => 'G?i ??n',
                'G?i' => 'G?i',
                
                // Profile
                'H? s?' => 'H? s?',
                'H? v? t?n' => 'H? v? t?n',
                'M?t kh?u' => 'M?t kh?u',
                'M?t kh?u hi?n t?i' => 'M?t kh?u hi?n t?i',
                'M?t kh?u m?i' => 'M?t kh?u m?i',
                'X?c nh?n m?t kh?u' => 'X?c nh?n m?t kh?u',
                'Gi?i thi?u' => 'Gi?i thi?u',
                
                // Actions
                'L?u' => 'L?u',
                'H?y' => 'H?y',
                'X?a' => 'X?a',
                'S?a' => 'S?a',
                'Th?m' => 'Th?m',
                'C?p nh?t' => 'C?p nh?t',
                'T?m ki?m' => 'T?m ki?m',
                'Xem t?t c?' => 'Xem t?t c?',
                'Ti?p t?c h?c' => 'Ti?p t?c h?c',
                'B?t ??u h?c' => 'B?t ??u h?c',
                '??nh d?u ho?n th?nh' => '??nh d?u ho?n th?nh',
                
                // Stats
                '?i?m' => '?i?m',
                '?i?m trung b?nh' => '?i?m trung b?nh',
                '?i?m cao nh?t' => '?i?m cao nh?t',
                '?i?m ??t' => '?i?m ??t',
                'Th?ng k?' => 'Th?ng k?',
                'B?o c?o' => 'B?o c?o',
                
                // Messages
                'Th?nh c?ng' => 'Th?nh c?ng',
                'L?i' => 'L?i',
                'C?nh b?o' => 'C?nh b?o',
                'Th?ng b?o' => 'Th?ng b?o',
                'Ch?a c?' => 'Ch?a c?',
                '??' => '??',
                
                // Others
                'Danh m?c' => 'Danh m?c',
                'M? t?' => 'M? t?',
                'Ti?u ??' => 'Ti?u ??',
                'N?i dung' => 'N?i dung',
                'Tr?ng th?i' => 'Tr?ng th?i',
                'Nh?p' => 'Nh?p',
                'Xu?t b?n' => 'Xu?t b?n',
                '?? xu?t b?n' => '?? xu?t b?n',
                'Th?i l??ng' => 'Th?i l??ng',
                'Ch? ??i' => 'Ch? ??i',
                'B?i ch? ch?m' => 'B?i ch? ch?m',
                
                // Greetings
                'Xin ch?o' => 'Xin ch?o',
                'Ch?o m?ng' => 'Ch?o m?ng',
                'Ch?o m?ng tr? l?i' => 'Ch?o m?ng tr? l?i',
            ];
            
            $files = [
                'index.php',
                'login.php',
                'register.php',
                'includes/header.php',
                'student/sidebar.php',
                'student/dashboard.php',
                'student/courses.php',
                'student/browse.php',
                'student/course_view.php',
                'student/quiz_take.php',
                'student/messages.php',
                'student/profile.php',
                'teacher/sidebar.php',
                'teacher/dashboard.php',
                'teacher/courses.php',
                'teacher/create_course.php',
                'teacher/course_edit.php',
                'teacher/create_quiz.php',
                'teacher/profile.php',
            ];
            
            $fixed_count = 0;
            $error_count = 0;
            
            foreach ($files as $file) {
                $filepath = __DIR__ . '/' . $file;
                
                if (file_exists($filepath)) {
                    $content = file_get_contents($filepath);
                    $original_content = $content;
                    
                    foreach ($replacements as $search => $replace) {
                        $content = str_replace($search, $replace, $content);
                    }
                    
                    if ($content !== $original_content) {
                        if (file_put_contents($filepath, $content)) {
                            echo "? Fixed: <strong>$file</strong><br>";
                            $fixed_count++;
                        } else {
                            echo "? Error: <strong>$file</strong> (kh?ng th? ghi file)<br>";
                            $error_count++;
                        }
                    } else {
                        echo "?? Skipped: <strong>$file</strong> (kh?ng c?n fix)<br>";
                    }
                } else {
                    echo "?? Not found: <strong>$file</strong><br>";
                }
            }
            
            echo "<hr>";
            echo "<h3>?? K?t qu?:</h3>";
            echo "<p>? ?? fix: <strong>$fixed_count</strong> file</p>";
            echo "<p>? L?i: <strong>$error_count</strong> file</p>";
            echo "<hr>";
            echo "<p><strong>?? HO?N TH?NH!</strong></p>";
            echo "<p>B?y gi? h?y:</p>";
            echo "<ol>";
            echo "<li>Clear cache browser (Ctrl+Shift+Del)</li>";
            echo "<li>Hard refresh (Ctrl+F5)</li>";
            echo "<li>V?o <a href='index.php' target='_blank'>Trang ch?</a> ?? ki?m tra</li>";
            echo "</ol>";
            echo '</div>';
        } else {
        ?>
        
        <div class="result">
            <h3>?? H??ng d?n:</h3>
            <p>Script n?y s? t? ??ng thay th? t?t c? text ti?ng Vi?t b? l?i trong c?c file PHP.</p>
            <p><strong>L?u ?:</strong> Script s? ghi ?? l?n file g?c. H?y backup n?u c?n!</p>
        </div>
        
        <h3>?? C?c file s? ???c x? l?:</h3>
        <pre>
? index.php
? login.php
? register.php
? includes/header.php
? student/*.php (9 files)
? teacher/*.php (7 files)
        </pre>
        
        <form method="POST" action="">
            <button type="submit" name="fix_now" class="btn" onclick="return confirm('B?n c? ch?c mu?n fix t?t c? file? Backup tr??c n?u c?n!')">
                ?? B?T ??U FIX NGAY!
            </button>
        </form>
        
        <hr>
        
        <h3>? N?u v?n kh?ng ???c?</h3>
        <p>Sau khi ch?y script:</p>
        <ol>
            <li><strong>Clear cache:</strong> Ctrl+Shift+Del</li>
            <li><strong>Hard refresh:</strong> Ctrl+F5</li>
            <li><strong>Restart Apache</strong> trong XAMPP</li>
        </ol>
        
        <?php } ?>
        
    </div>
</body>
</html>
