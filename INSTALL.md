# H??ng D?n C?i ??t Chi Ti?t - E-Learning Platform

## ?? M?c L?c
1. [Y?u C?u H? Th?ng](#y?u-c?u-h?-th?ng)
2. [C?i ??t M?i Tr??ng](#c?i-??t-m?i-tr??ng)
3. [C?i ??t ?ng D?ng](#c?i-??t-?ng-d?ng)
4. [C?u H?nh](#c?u-h?nh)
5. [Ki?m Tra](#ki?m-tra)
6. [X? L? L?i](#x?-l?-l?i)

---

## Y?u C?u H? Th?ng

### Ph?n M?m C?n Thi?t
- **PHP:** Version 7.4 tr? l?n
- **MySQL:** Version 5.7 tr? l?n (ho?c MariaDB 10.2+)
- **Web Server:** Apache 2.4+ ho?c Nginx
- **Browser:** Chrome, Firefox, Safari, Edge (phi?n b?n m?i nh?t)

### PHP Extensions
```
- mysqli
- pdo_mysql
- session
- json
- fileinfo
- gd (cho x? l? ?nh)
```

---

## C?i ??t M?i Tr??ng

### Windows - S? d?ng XAMPP

#### B??c 1: Download XAMPP
1. Truy c?p: https://www.apachefriends.org/
2. Download phi?n b?n XAMPP v?i PHP 7.4 tr? l?n
3. Ch?y file c?i ??t v? l?m theo h??ng d?n

#### B??c 2: C?i ??t
1. Ch?n Components:
   - ? Apache
   - ? MySQL
   - ? PHP
   - ? phpMyAdmin
   
2. Ch?n th? m?c c?i ??t (m?c ??nh: `C:\xampp`)

3. Ho?n t?t c?i ??t

#### B??c 3: Kh?i ??ng XAMPP
1. M? XAMPP Control Panel
2. Click "Start" cho Apache
3. Click "Start" cho MySQL
4. ??m b?o c? 2 ??u hi?n th? m?u xanh

### macOS - S? d?ng MAMP

#### B??c 1: Download MAMP
1. Truy c?p: https://www.mamp.info/
2. Download MAMP (phi?n b?n mi?n ph?)
3. C?i ??t nh? m?t ?ng d?ng macOS th?ng th??ng

#### B??c 2: Kh?i ??ng
1. M? MAMP
2. Click "Start Servers"
3. ??i Apache v? MySQL kh?i ??ng

### Linux - C?i ??t Th? C?ng

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-mysqli
sudo systemctl start apache2
sudo systemctl start mysql

# CentOS/RHEL
sudo yum install httpd mariadb-server php php-mysql
sudo systemctl start httpd
sudo systemctl start mariadb
```

---

## C?i ??t ?ng D?ng

### B??c 1: Download Source Code

#### Option A: Download ZIP
1. Download file ZIP c?a project
2. Gi?i n?n v?o th? m?c web root:
   - **XAMPP (Windows):** `C:\xampp\htdocs\elearning`
   - **MAMP (macOS):** `/Applications/MAMP/htdocs/elearning`
   - **Linux:** `/var/www/html/elearning`

#### Option B: Git Clone
```bash
# XAMPP
cd C:\xampp\htdocs
git clone <repository-url> elearning

# MAMP
cd /Applications/MAMP/htdocs
git clone <repository-url> elearning

# Linux
cd /var/www/html
sudo git clone <repository-url> elearning
```

### B??c 2: Ph?n Quy?n (Linux/macOS)

```bash
# Cho ph?p web server ghi v?o th? m?c uploads
sudo chmod -R 755 /var/www/html/elearning
sudo chmod -R 777 /var/www/html/elearning/uploads
sudo chown -R www-data:www-data /var/www/html/elearning

# macOS v?i MAMP
sudo chmod -R 777 /Applications/MAMP/htdocs/elearning/uploads
```

### B??c 3: T?o Database

#### Option A: S? d?ng phpMyAdmin

1. **M? phpMyAdmin:**
   - XAMPP: http://localhost/phpmyadmin
   - MAMP: http://localhost:8888/phpMyAdmin
   - Linux: http://localhost/phpmyadmin

2. **T?o Database:**
   - Click tab "Databases"
   - Nh?p t?n: `elearning_db`
   - Ch?n Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. **Import Database:**
   - Click v?o database `elearning_db` v?a t?o
   - Click tab "Import"
   - Click "Choose File" v? ch?n file `database.sql`
   - Click "Go" ? cu?i trang
   - ??i import ho?n t?t

#### Option B: S? d?ng Command Line

```bash
# T?o database
mysql -u root -p -e "CREATE DATABASE elearning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import database
mysql -u root -p elearning_db < database.sql

# N?u MySQL kh?ng c? password (XAMPP m?c ??nh)
mysql -u root elearning_db < database.sql
```

### B??c 4: C?u H?nh Database

1. **M? file:** `config/database.php`

2. **C?p nh?t th?ng tin k?t n?i:**

```php
<?php
// XAMPP (Windows/Mac) - M?c ??nh
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // ?? tr?ng n?u kh?ng c? password
define('DB_NAME', 'elearning_db');

// Linux - C? th? c?n password
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_mysql_password');
define('DB_NAME', 'elearning_db');

// N?u s? d?ng port kh?c
define('DB_HOST', 'localhost:3307');  // MAMP th??ng d?ng 8889
```

3. **L?u file**

### B??c 5: C?u H?nh Site URL

1. **M? file:** `config/config.php`

2. **C?p nh?t SITE_URL:**

```php
// XAMPP
define('SITE_URL', 'http://localhost/elearning');

// MAMP (m?c ??nh port 8888)
define('SITE_URL', 'http://localhost:8888/elearning');

// Linux
define('SITE_URL', 'http://localhost/elearning');

// N?u d?ng domain ri?ng
define('SITE_URL', 'http://yourdomain.com');
```

3. **L?u file**

---

## C?u H?nh

### 1. PHP Configuration

#### T?ng Gi?i H?n Upload File

**XAMPP (Windows):**
- M? file: `C:\xampp\php\php.ini`

**MAMP (macOS):**
- Preferences ? PHP ? Click tab "php.ini"

**Linux:**
- File: `/etc/php/7.4/apache2/php.ini`

**Thay ??i c?c gi? tr?:**
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

**Restart Apache sau khi thay ??i**

### 2. Apache Configuration (Optional)

#### Enable mod_rewrite

**XAMPP/Linux:**
```bash
# Ki?m tra module ?? enable ch?a
apache2ctl -M | grep rewrite

# Enable n?u ch?a c?
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 3. T?o Virtual Host (Optional, Khuy?n Ngh?)

#### XAMPP (Windows)
M?: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Th?m:
```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot "C:/xampp/htdocs/elearning"
    <Directory "C:/xampp/htdocs/elearning">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

S?a file hosts: `C:\Windows\System32\drivers\etc\hosts`
```
127.0.0.1 elearning.local
```

#### Linux
```bash
sudo nano /etc/apache2/sites-available/elearning.conf
```

Th?m:
```apache
<VirtualHost *:80>
    ServerName elearning.local
    DocumentRoot /var/www/html/elearning
    <Directory /var/www/html/elearning>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/elearning-error.log
    CustomLog ${APACHE_LOG_DIR}/elearning-access.log combined
</VirtualHost>
```

```bash
sudo a2ensite elearning.conf
sudo systemctl reload apache2
```

---

## Ki?m Tra

### 1. Ki?m Tra C?i ??t C? B?n

1. **M? tr?nh duy?t**

2. **Truy c?p:**
   - XAMPP: `http://localhost/elearning`
   - MAMP: `http://localhost:8888/elearning`
   - Virtual Host: `http://elearning.local`

3. **B?n s? th?y trang ch? E-Learning Platform**

### 2. Ki?m Tra Database

**Trong phpMyAdmin:**
1. Click v?o database `elearning_db`
2. Ki?m tra c?c table ?? ???c t?o:
   - users
   - courses
   - lessons
   - quizzes
   - quiz_questions
   - enrollments
   - messages
   - assignments
   - v.v.

### 3. Test ??ng Nh?p

**T?i kho?n Gi?o vi?n:**
- Username: `teacher1`
- Password: `teacher123`
- URL: `http://localhost/elearning/login.php`

**T?i kho?n H?c sinh:**
- Username: `student1`
- Password: `student123`

### 4. Test Ch?c N?ng

#### Gi?o Vi?n:
- ? ??ng nh?p th?nh c?ng
- ? T?o kh?a h?c m?i
- ? Th?m b?i gi?ng
- ? T?o quiz
- ? G?i tin nh?n

#### H?c sinh:
- ? ??ng nh?p th?nh c?ng
- ? T?m v? ??ng k? kh?a h?c
- ? Xem b?i gi?ng
- ? L?m quiz
- ? G?i tin nh?n

---

## X? L? L?i

### L?i: "Database connection failed"

**Nguy?n nh?n:**
- MySQL ch?a kh?i ??ng
- Th?ng tin database sai
- Port MySQL kh?ng ??ng

**Gi?i ph?p:**
```bash
# Ki?m tra MySQL c? ch?y kh?ng
# Windows
netstat -an | find "3306"

# Linux/Mac
netstat -an | grep 3306

# Restart MySQL
# XAMPP: Stop v? Start l?i MySQL trong Control Panel
# Linux:
sudo systemctl restart mysql
```

### L?i: "Cannot modify header information"

**Nguy?n nh?n:** C? kho?ng tr?ng ho?c BOM tr??c tag `<?php`

**Gi?i ph?p:**
1. M? file PHP b?o l?i
2. ??m b?o kh?ng c? kho?ng tr?ng tr??c `<?php`
3. Save file v?i encoding UTF-8 (kh?ng BOM)

### L?i: "404 Not Found"

**Nguy?n nh?n:** 
- URL kh?ng ??ng
- File kh?ng t?n t?i
- mod_rewrite ch?a enable

**Gi?i ph?p:**
```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2

# Ki?m tra .htaccess
# ??m b?o AllowOverride All trong config Apache
```

### L?i: "Permission denied" khi upload

**Gi?i ph?p:**
```bash
# Linux
sudo chmod -R 777 /var/www/html/elearning/uploads
sudo chown -R www-data:www-data /var/www/html/elearning/uploads

# macOS
sudo chmod -R 777 /Applications/MAMP/htdocs/elearning/uploads
```

### L?i: "Session error"

**Gi?i ph?p:**
```bash
# Linux - Ki?m tra quy?n session folder
sudo chmod -R 777 /var/lib/php/sessions

# ho?c trong php.ini
session.save_path = "/tmp"
```

### L?i: "Blank white page"

**Gi?i ph?p:**
1. Enable error reporting trong `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

2. Ki?m tra error log:
   - XAMPP: `C:\xampp\apache\logs\error.log`
   - Linux: `/var/log/apache2/error.log`

---

## B?o M?t Sau Khi C?i ??t

### 1. ??i Password M?c ??nh

**Ngay l?p t?c ??i password cho:**
- Admin account
- Teacher demo account
- Student demo account

### 2. Disable Error Display (Production)

Trong `config/config.php`:
```php
error_reporting(0);
ini_set('display_errors', 0);
```

### 3. B?o V? Th? M?c Quan Tr?ng

File `.htaccess` ?? ???c c?u h?nh ?? b?o v?:
- File config
- File .sql
- Directory listing

### 4. Backup Database ??nh K?

```bash
# Command line backup
mysqldump -u root -p elearning_db > backup_$(date +%Y%m%d).sql

# Ho?c d?ng phpMyAdmin
# Export ? Format: SQL ? Go
```

---

## H? Tr?

N?u b?n g?p v?n ??:

1. **Ki?m tra README.md** cho th?ng tin t?ng quan
2. **Xem ph?n X? L? L?i** ? tr?n
3. **Ki?m tra Error Log** c?a Apache v? MySQL
4. **Google l?i c? th?** v?i keyword "PHP" + "MySQL"
5. **T?o issue** tr?n GitHub repository

---

## Ghi Ch? Quan Tr?ng

?? **??y l? phi?n b?n Development:**
- Kh?ng s? d?ng tr?c ti?p cho Production
- C?n th?m c?c bi?n ph?p b?o m?t
- C?n optimize performance
- C?n th?m HTTPS
- C?n th?m email verification
- C?n th?m CAPTCHA

? **Ho?n th?nh c?i ??t!** 
Ch?c b?n s? d?ng E-Learning Platform th?nh c?ng! ??
