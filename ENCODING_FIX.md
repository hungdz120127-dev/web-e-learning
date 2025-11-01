# H??ng D?n Fix L?i Encoding Ti?ng Vi?t

## V?n ??
C?c k? t? ti?ng Vi?t c? d?u hi?n th? th?nh d?u `?` ho?c k? t? l?.

## Nguy?n Nh?n
- File PHP kh?ng ???c save v?i encoding UTF-8
- Database kh?ng s? d?ng utf8mb4
- Browser cache
- PHP/Apache kh?ng c?u h?nh UTF-8

---

## ? GI?I PH?P 1: Fix Encoding Trong Editor (KHUY?N NGH?)

### V?i Visual Studio Code:
1. M? file PHP c? v?n ??
2. Nh?n g?c d??i b?n ph?i, b?n s? th?y encoding hi?n t?i (v? d?: "UTF-8 with BOM" ho?c "Windows-1252")
3. Click v?o encoding ??
4. Ch?n **"Reopen with Encoding"**
5. Ch?n **"UTF-8"**
6. N?u k? t? v?n l?i, g? l?i b?ng tay c?c ch? ti?ng Vi?t
7. Click encoding m?t l?n n?a
8. Ch?n **"Save with Encoding"** > **"UTF-8"** (KH?NG ch?n UTF-8 with BOM)
9. Save file (Ctrl+S)

### V?i Notepad++:
1. M? file PHP
2. Menu: **Encoding** > **Convert to UTF-8** (KH?NG ch?n UTF-8-BOM)
3. Save file (Ctrl+S)

### V?i Sublime Text:
1. M? file PHP
2. Menu: **File** > **Save with Encoding** > **UTF-8**

---

## ? GI?I PH?P 2: T?o L?i File T? ??u

### B??c 1: Backup
Backup to?n b? th? m?c hi?n t?i ?? gi? code logic

### B??c 2: T?o File M?i UTF-8
```php
<?php
// Khi t?o file m?i, ??m b?o:
// 1. Editor set encoding = UTF-8 (kh?ng BOM)
// 2. Copy code t? file c?
// 3. G? l?i TO?N B? text ti?ng Vi?t c? d?u
// 4. Save as UTF-8
```

### V? d?: S?a file index.php
```php
<!-- TR??C (L?I) -->
<title>Trang ch?</title>
<a href="#">??ng nh?p</a>

<!-- SAU (??NG) -->
<title>Trang ch?</title>
<a href="#">??ng nh?p</a>
```

---

## ? GI?I PH?P 3: Fix Database

### B??c 1: Ki?m Tra Database Collation
```sql
-- Trong phpMyAdmin, ch?y query n?y:
ALTER DATABASE elearning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### B??c 2: Ki?m Tra T?ng Table
```sql
-- Xem collation c?a c?c table
SHOW TABLE STATUS;

-- N?u kh?ng ??ng, s?a t?ng table:
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE courses CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- L?m t??ng t? cho c?c table kh?c
```

### B??c 3: Re-import Data
1. Export database hi?n t?i (backup)
2. X?a database c?
3. T?o database m?i v?i utf8mb4:
```sql
CREATE DATABASE elearning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
4. Import l?i file `database.sql`
5. Trong phpMyAdmin khi import, ch?n:
   - Character set: **utf8mb4**
   - Format: **SQL**

---

## ? GI?I PH?P 4: Ki?m Tra PHP Config

### File: config/database.php
??m b?o c? d?ng n?y sau khi connect:
```php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// QUAN TR?NG: Th?m d?ng n?y
$conn->set_charset("utf8mb4");
```

### File: M?i PHP file
??m b?o d?ng ??u ti?n (sau `<?php`) kh?ng c? kho?ng tr?ng:
```php
<?php
// ??NG - Kh?ng c? kho?ng tr?ng tr??c <?php

   <?php
// SAI - C? kho?ng tr?ng tr??c <?php
```

### HTML Meta Tag
M?i file PHP output HTML ph?i c?:
```html
<meta charset="UTF-8">
```

---

## ? GI?I PH?P 5: Apache/PHP Settings

### File .htaccess (?? c?)
```apache
AddDefaultCharset UTF-8
```

### File php.ini
T?m v? s?a c?c d?ng sau:
```ini
default_charset = "UTF-8"
mbstring.internal_encoding = UTF-8
mbstring.http_output = UTF-8
```

Sau ?? restart Apache.

---

## ? GI?I PH?P 6: Browser

1. **Clear Cache:**
   - Chrome: `Ctrl+Shift+Del` > Clear All
   - Firefox: `Ctrl+Shift+Del` > Clear All

2. **Hard Refresh:**
   - `Ctrl+F5` ho?c `Ctrl+Shift+R`

3. **Ki?m tra encoding:**
   - Chrome: F12 > Network > Ch?n file HTML > Headers > Content-Type: text/html; charset=UTF-8

---

## ?? SCRIPT T? ??NG FIX (N?ng Cao)

### Fix Encoding T?t C? File PHP:

T?o file `fix_all.php`:
```php
<?php
$files = glob("*.php") + glob("*/*.php") + glob("*/*/*.php");

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Detect encoding
    $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    
    if ($encoding !== 'UTF-8') {
        echo "Converting: $file ($encoding -> UTF-8)\n";
        $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        file_put_contents($file, $content);
    }
}

echo "Done!\n";
?>
```

Ch?y: `php fix_all.php`

**?? C?NH B?O:** Backup tr??c khi ch?y script n?y!

---

## ?? CHECKLIST HO?N CH?NH

- [ ] M? file PHP b?ng VS Code/Notepad++
- [ ] Check encoding ? g?c d??i ph?i
- [ ] Reopen v?i UTF-8 (n?u c?n)
- [ ] G? l?i c?c ch? ti?ng Vi?t c? d?u
- [ ] Save with UTF-8 (KH?NG BOM)
- [ ] Check `config/database.php` c? `set_charset("utf8mb4")`
- [ ] Check database collation = utf8mb4_unicode_ci
- [ ] Re-import database n?u c?n
- [ ] Clear browser cache
- [ ] Hard refresh (Ctrl+F5)
- [ ] Test l?i tr?n browser

---

## ?? V?n Kh?ng ???c?

### Debug Steps:
1. **T?o file test.php:**
```php
<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Test: Ti?ng Vi?t c? d?u</h1>
    <p>Xin ch?o - ??ng nh?p - ??ng k?</p>
    <?php
    echo "PHP: Xin ch?o - ??ng nh?p - ??ng k?";
    ?>
</body>
</html>
```

2. **M? http://localhost/workspace/test.php**

3. **N?u test.php hi?n th? ??NG:**
   - V?n ?? ? c?c file kh?c
   - C?n fix encoding t?ng file

4. **N?u test.php c?ng L?I:**
   - V?n ?? ? PHP/Apache config
   - Check php.ini
   - Restart Apache

---

## ?? L?U ? QUAN TR?NG

1. **LU?N save file v?i UTF-8 (KH?NG BOM)**
2. **KH?NG copy-paste t? Word/Google Docs** (c? th? b? encoding l?)
3. **G? TR?C TI?P ti?ng Vi?t v?o editor**
4. **Test ngay sau khi s?a** ?? ??m b?o kh?ng b? l?i l?i
5. **Backup tr??c khi fix** ?? tr?nh m?t code

---

## ?? Khuy?n Ngh?

**Gi?i ph?p NHANH NH?T v? AN TO?N NH?T:**

1. M? **VS Code**
2. Install extension: **"Fix All JSON Errors"** v? **"UTF-8 BOM Helper"**
3. Open folder `/workspace`
4. Nh?n `Ctrl+H` (Find and Replace)
5. Find: c?c text l?i (v? d?: `Trang ch?`)
6. Replace: text ??ng (v? d?: `Trang ch?`)
7. Replace All
8. Save All (Ctrl+K S)

**Xong!** ?
