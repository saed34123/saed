# تحديث ملف dashboard.php للتأكد من عمل الجلسة
dashboard_content = '''<?php
session_start();
error_log("Dashboard accessed. Session data: " . print_r($_SESSION, true));

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    error_log("No user_id in session. Redirecting to login");
    header("Location: ./register.html");
    exit();
}

// طباعة معلومات الجلسة للتحقق
error_log("User ID: " . $_SESSION['user_id']);
error_log("Username: " . $_SESSION['username']);

?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - منصة التداول</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* نفس الستايل السابق */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .dashboard-header {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .welcome-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <div class="welcome-message">
                مرحباً، <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'زائر'; ?>
            </div>
            <p>تم تسجيل الدخول بنجاح!</p>
            <p>معرف المستخدم: <?php echo $_SESSION['user_id']; ?></p>
        </div>
    </div>
</body>
</html>'''

with open('registration_system/dashboard.php', 'w', encoding='utf-8') as f:
    f.write(dashboard_content)

print("تم تحديث ملف dashboard.php مع إضافة تسجيل الأخطاء وعرض معلومات الجلسة")