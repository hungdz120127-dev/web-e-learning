# ?? H??NG D?N FIX NHANH TI?NG VI?T

## ? Gi?i Ph?p Nhanh Nh?t (5 ph?t)

### B??c 1: Backup
```bash
# Copy to?n b? th? m?c workspace sang ch? kh?c
# Ho?c t?o file zip backup
```

### B??c 2: Fix Database
1. M? phpMyAdmin: `http://localhost/phpmyadmin`
2. Ch?n database `elearning_db`
3. Tab **Operations**
4. T?m m?c **Collation**
5. Ch?n: `utf8mb4_unicode_ci`
6. Click **Go**

### B??c 3: Fix File config/database.php
M? file v? ??m b?o c? d?ng n?y:
```php
$conn->set_charset("utf8mb4");
```

### B??c 4: Clear Browser Cache
- Chrome/Edge: `Ctrl + Shift + Del` ? Clear All
- Firefox: `Ctrl + Shift + Del` ? Clear All
- Hard Refresh: `Ctrl + F5`

### B??c 5: Test
Truy c?p: `http://localhost/workspace`

---

## ?? N?u V?n L?i - Fix B?ng Editor

### V?i VS Code (Khuy?n ngh?):

#### 1. M? VS Code
M? th? m?c `/workspace`

#### 2. Install Extension (Ch? c?n 1 l?n)
- Nh?n `Ctrl+Shift+X`
- T?m v? c?i: **"Fix All JSON"**

#### 3. Find & Replace All
Nh?n `Ctrl+H` v? thay th? theo b?ng d??i:

| T?m (Find) | Thay b?ng (Replace) |
|------------|---------------------|
| `Trang ch?` | `Trang ch?` |
| `??ng nh?p` | `??ng nh?p` |
| `??ng k?` | `??ng k?` |
| `H?c sinh` | `H?c sinh` |
| `Gi?o vi?n` | `Gi?o vi?n` |
| `Kh?a h?c` | `Kh?a h?c` |
| `B?i gi?ng` | `B?i gi?ng` |
| `B?i ki?m tra` | `B?i ki?m tra` |
| `Tin nh?n` | `Tin nh?n` |
| `Ti?n ?? h?c t?p` | `Ti?n ?? h?c t?p` |
| `?? ho?n th?nh` | `?? ho?n th?nh` |
| `Ch?a ho?n th?nh` | `Ch?a ho?n th?nh` |
| `?i?m` | `?i?m` |
| `H? s?` | `H? s?` |
| `C?p nh?t` | `C?p nh?t` |
| `Th?nh c?ng` | `Th?nh c?ng` |
| `L?i` | `L?i` |
| `X?a` | `X?a` |
| `S?a` | `S?a` |
| `Th?m` | `Th?m` |

#### 4. Replace Trong Nhi?u File C?ng L?c
1. Nh?n `Ctrl+H`
2. Check ?: **"Replace in Files"** (ho?c `Ctrl+Shift+H`)
3. Files to include: `*.php`
4. Paste text c?n t?m
5. Paste text thay th?
6. Click **"Replace All"**

#### 5. Save All
Nh?n `Ctrl+K` r?i `S` (ho?c `Ctrl+Alt+S`)

---

## ?? File Quan Tr?ng Nh?t C?n Fix

?u ti?n fix c?c file n?y theo th? t?:

1. ? `index.php` - Trang ch?
2. ? `login.php` - ??ng nh?p
3. ? `register.php` - ??ng k?
4. ? `student/dashboard.php` - Dashboard h?c sinh
5. ? `teacher/dashboard.php` - Dashboard gi?o vi?n
6. ? `student/sidebar.php` - Menu h?c sinh
7. ? `teacher/sidebar.php` - Menu gi?o vi?n
8. ? `student/courses.php` - Kh?a h?c
9. ? `student/browse.php` - T?m kh?a h?c
10. ? `student/messages.php` - Tin nh?n

---

## ?? C?ch Fix T?ng File Th? C?ng

### V? d? v?i file login.php:

**B??C 1:** M? file b?ng VS Code

**B??C 2:** Nh?n g?c d??i b?n ph?i m?n h?nh, s? th?y encoding (v? d?: "UTF-8")

**B??C 3:** Click v?o encoding ??

**B??C 4:** Ch?n **"Reopen with Encoding"**

**B??C 5:** Ch?n **"UTF-8"**

**B??C 6:** N?u v?n th?y k? t? l?i, g? l?i b?ng tay:
```php
// L?I:
<title>??ng nh?p</title>

// ??NG (g? l?i):
<title>??ng nh?p</title>
```

**B??C 7:** Click encoding l?i

**B??C 8:** Ch?n **"Save with Encoding"** > **"UTF-8"** (KH?NG ch?n UTF-8 with BOM)

**B??C 9:** L?u file (`Ctrl+S`)

**B??C 10:** Test l?i tr?n browser

---

## ?? Re-import Database (N?u Data B? L?i)

### N?u d? li?u trong database c?ng b? l?i encoding:

1. **Backup database hi?n t?i:**
   ```
   phpMyAdmin > Export > Format: SQL > Go
   ```

2. **X?a database:**
   ```sql
   DROP DATABASE elearning_db;
   ```

3. **T?o l?i v?i UTF-8:**
   ```sql
   CREATE DATABASE elearning_db 
   CHARACTER SET utf8mb4 
   COLLATE utf8mb4_unicode_ci;
   ```

4. **Import l?i:**
   - Click v?o database `elearning_db`
   - Tab **Import**
   - Choose file: `database.sql`
   - Character set: **utf8mb4**
   - Click **Go**

---

## ?? Test Script

T?o file `test_vietnamese.php` trong th? m?c workspace:

```php
<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Test Ti?ng Vi?t</title>
</head>
<body>
    <h1>Test Encoding UTF-8</h1>
    
    <h2>1. HTML Text:</h2>
    <p>Xin ch?o - ??ng nh?p - ??ng k? - H?c sinh - Gi?o vi?n</p>
    <p>Kh?a h?c - B?i gi?ng - B?i ki?m tra - Tin nh?n - Ti?n ??</p>
    
    <h2>2. PHP Echo:</h2>
    <?php
    echo '<p>Xin ch?o - ??ng nh?p - ??ng k? - H?c sinh - Gi?o vi?n</p>';
    echo '<p>Kh?a h?c - B?i gi?ng - B?i ki?m tra - Tin nh?n - Ti?n ??</p>';
    ?>
    
    <h2>3. Database Connection:</h2>
    <?php
    require_once 'config/database.php';
    
    $result = $conn->query("SELECT 'Ti?ng Vi?t t? Database' as test");
    if ($result) {
        $row = $result->fetch_assoc();
        echo '<p>' . $row['test'] . '</p>';
        echo '<p style="color: green;">? Database UTF-8 OK!</p>';
    } else {
        echo '<p style="color: red;">? Database Error!</p>';
    }
    ?>
    
    <hr>
    <p><strong>N?u t?t c? text tr?n hi?n th? ??NG ? Encoding OK! ?</strong></p>
    <p><strong>N?u c? d?u ? ho?c k? t? l? ? C?n fix encoding ?</strong></p>
</body>
</html>
```

Truy c?p: `http://localhost/workspace/test_vietnamese.php`

---

## ? Checklist Ho?n Ch?nh

- [ ] Database collation = utf8mb4_unicode_ci
- [ ] File config/database.php c? `set_charset("utf8mb4")`
- [ ] M? file PHP b?ng VS Code
- [ ] Check encoding = UTF-8 (kh?ng BOM)
- [ ] Replace c?c text l?i
- [ ] Save v?i UTF-8
- [ ] Clear browser cache
- [ ] Hard refresh (Ctrl+F5)
- [ ] Test file test_vietnamese.php
- [ ] Test ??ng nh?p
- [ ] Test t?o kh?a h?c
- [ ] Test t?t c? ch?c n?ng

---

## ?? V?n Kh?ng ???c?

### Option 1: Re-download Project
N?u qu? nhi?u file b? l?i, c? th?:
1. Download l?i source code
2. ??m b?o unzip b?ng tool h? tr? UTF-8
3. Import database l?i

### Option 2: Manual Fix
D?ng file `VIETNAMESE_TEXT_CORRECT.txt` t?i ?? t?o:
1. M? file ??
2. Copy text ??ng
3. Find & Replace trong t?ng file PHP

### Option 3: Contact Support
N?u v?n kh?ng ???c, g?i cho t?i:
- Screenshot l?i
- File database.sql
- 1-2 file PHP b? l?i

---

**Ch?c b?n fix th?nh c?ng! ??**
