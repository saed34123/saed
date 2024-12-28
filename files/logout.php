# Create logout.php
with open('logout.php', 'w', encoding='utf-8') as f:
    f.write('''<?php
session_start();

// تدمير جلسة المستخدم
session_destroy();

// حذف الكوكيز
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
header("Location: login.php");
exit();
?>''')
print("تم إنشاء ملف logout.php بنجاح!")