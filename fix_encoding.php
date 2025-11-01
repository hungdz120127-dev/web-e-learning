<?php
/**
 * Script ?? ki?m tra v? th?ng b?o v? encoding c?a c?c file
 * Ch?y script n?y m?t l?n ?? ki?m tra: php fix_encoding.php
 */

echo "=== H??ng d?n Fix Encoding UTF-8 ===\n\n";

echo "N?u b?n th?y c?c k? t? ti?ng Vi?t b? l?i (hi?n th? d?u ?), \n";
echo "h?y l?m theo c?c b??c sau:\n\n";

echo "C?CH 1: S? d?ng Text Editor (Khuy?n ngh?)\n";
echo "----------------------------------------\n";
echo "1. M? file b?ng VS Code, Notepad++, ho?c Sublime Text\n";
echo "2. Trong VS Code:\n";
echo "   - Nh?n Ctrl+Shift+P\n";
echo "   - G? 'Change File Encoding'\n";
echo "   - Ch?n 'Reopen with Encoding' > 'UTF-8'\n";
echo "   - Sau ?? 'Save with Encoding' > 'UTF-8'\n\n";

echo "3. Trong Notepad++:\n";
echo "   - Menu Encoding > Convert to UTF-8\n";
echo "   - Save file\n\n";

echo "C?CH 2: ??m b?o Database UTF-8\n";
echo "----------------------------------------\n";
echo "1. M? phpMyAdmin\n";
echo "2. Ch?n database 'elearning_db'\n";
echo "3. Tab 'Operations'\n";
echo "4. Collation: ch?n 'utf8mb4_unicode_ci'\n";
echo "5. Click 'Go'\n\n";

echo "C?CH 3: Ki?m tra PHP Settings\n";
echo "----------------------------------------\n";
echo "??m b?o trong file config/database.php c? d?ng:\n";
echo "\$conn->set_charset('utf8mb4');\n\n";

echo "C?CH 4: Browser Cache\n";
echo "----------------------------------------\n";
echo "1. Clear browser cache (Ctrl+Shift+Del)\n";
echo "2. Hard refresh trang web (Ctrl+F5)\n\n";

echo "=== Ki?m tra Current Encoding ===\n";
echo "PHP Default Charset: " . ini_get('default_charset') . "\n";
echo "MB Internal Encoding: " . (function_exists('mb_internal_encoding') ? mb_internal_encoding() : 'N/A') . "\n\n";

echo "N?u v?n g?p v?n ??, h?y:\n";
echo "1. X?a to?n b? database\n";
echo "2. Import l?i file database.sql\n";
echo "3. ??m b?o phpMyAdmin import v?i encoding UTF-8\n\n";

echo "File n?y ?? ho?n t?t ki?m tra!\n";
?>
