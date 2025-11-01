<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Ti?ng Vi?t UTF-8</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        h1 { color: #4361ee; }
        h2 { color: #333; border-bottom: 2px solid #4361ee; padding-bottom: 10px; }
        .vietnam-text {
            font-size: 18px;
            line-height: 1.6;
            padding: 15px;
            background: #f0f8ff;
            border-left: 4px solid #4361ee;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>???? Test Encoding Ti?ng Vi?t</h1>

    <div class="test-box">
        <h2>1?? Test HTML Text</h2>
        <div class="vietnam-text">
            <p>? Ch? th??ng: a b c d ? e ? g h i k l m n o ? ? p q r s t u ? v x y</p>
            <p>? Ch? hoa: A B C D ? E ? G H I K L M N O ? ? P Q R S T U ? V X Y</p>
            <p>? D?u s?c: ? ? ? ? ? ?</p>
            <p>? D?u huy?n: ? ? ? ? ? ?</p>
            <p>? D?u h?i: ? ? ? ? ? ?</p>
            <p>? D?u ng?: ? ? ? ? ? ?</p>
            <p>? D?u n?ng: ? ? ? ? ? ?</p>
        </div>
        
        <div class="vietnam-text">
            <strong>C?c t? th??ng d?ng:</strong><br>
            Xin ch?o - ??ng nh?p - ??ng k? - H?c sinh - Gi?o vi?n<br>
            Kh?a h?c - B?i gi?ng - B?i ki?m tra - Tin nh?n - Ti?n ??<br>
            Ho?n th?nh - Ch?a ho?n th?nh - ?? x?a - C?p nh?t - Th?nh c?ng
        </div>
    </div>

    <div class="test-box">
        <h2>2?? Test PHP Echo</h2>
        <div class="vietnam-text">
            <?php
            echo '<p>? Xin ch?o - ??ng nh?p - ??ng k? - H?c sinh - Gi?o vi?n</p>';
            echo '<p>? Kh?a h?c - B?i gi?ng - B?i ki?m tra - Tin nh?n - Ti?n ??</p>';
            echo '<p>? Ho?n th?nh - Ch?a ho?n th?nh - ?? x?a - C?p nh?t - Th?nh c?ng</p>';
            ?>
        </div>
    </div>

    <div class="test-box">
        <h2>3?? Test Database Connection</h2>
        <?php
        if (file_exists('config/database.php')) {
            require_once 'config/database.php';
            
            // Test connection
            if ($conn) {
                echo '<p class="success">? K?t n?i database th?nh c?ng!</p>';
                
                // Test charset
                $charset = $conn->character_set_name();
                echo '<p>Current charset: <strong>' . $charset . '</strong></p>';
                
                if ($charset == 'utf8mb4' || $charset == 'utf8') {
                    echo '<p class="success">? Database charset OK!</p>';
                } else {
                    echo '<p class="error">? Database charset kh?ng ??ng! N?n l? utf8mb4</p>';
                }
                
                // Test query with Vietnamese
                $result = $conn->query("SELECT 'Ti?ng Vi?t t? Database: Xin ch?o!' as test");
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo '<div class="vietnam-text">' . $row['test'] . '</div>';
                } else {
                    echo '<p class="error">? Query l?i!</p>';
                }
                
            } else {
                echo '<p class="error">? Kh?ng k?t n?i ???c database!</p>';
            }
        } else {
            echo '<p class="error">? Kh?ng t?m th?y file config/database.php!</p>';
        }
        ?>
    </div>

    <div class="test-box">
        <h2>4?? Test PHP Info</h2>
        <?php
        echo '<p>Default Charset: <strong>' . ini_get('default_charset') . '</strong></p>';
        echo '<p>MB Internal Encoding: <strong>' . (function_exists('mb_internal_encoding') ? mb_internal_encoding() : 'N/A') . '</strong></p>';
        echo '<p>PHP Version: <strong>' . phpversion() . '</strong></p>';
        ?>
    </div>

    <div class="test-box">
        <h2>?? K?t Qu?</h2>
        <div style="padding: 20px; background: #e8f5e9; border-radius: 5px;">
            <p style="font-size: 20px;">
                <strong>N?u T?T C? text ti?ng Vi?t ? tr?n hi?n th? ??NG:</strong>
            </p>
            <p class="success" style="font-size: 24px;">? ENCODING HO?N TO?N OK! Kh?ng c?n fix g? c?!</p>
        </div>
        
        <div style="padding: 20px; background: #ffebee; border-radius: 5px; margin-top: 20px;">
            <p style="font-size: 20px;">
                <strong>N?u c? d?u ? ho?c k? t? l?:</strong>
            </p>
            <p class="error" style="font-size: 24px;">? C?N FIX ENCODING!</p>
            <p>H?y l?m theo h??ng d?n trong file: <strong>QUICK_FIX.md</strong></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="index.php" style="display: inline-block; padding: 15px 30px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; font-size: 18px;">
            ? V? Trang Ch?
        </a>
    </div>

    <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px;">
        <h3>?? T?i Li?u H??ng D?n:</h3>
        <ul>
            <li><strong>QUICK_FIX.md</strong> - Fix nhanh trong 5 ph?t</li>
            <li><strong>ENCODING_FIX.md</strong> - H??ng d?n chi ti?t ??y ??</li>
            <li><strong>VIETNAMESE_TEXT_CORRECT.txt</strong> - Danh s?ch text ??ng ?? copy</li>
            <li><strong>fix_encoding.php</strong> - Script ki?m tra encoding</li>
        </ul>
    </div>
</body>
</html>
