import os

# إنشاء مجلد للملفات إذا لم يكن موجوداً
if not os.path.exists('registration_system'):
    os.makedirs('registration_system')

# إنشاء ملف register_process.php
register_process_content = '''<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // التحقق من عدم وجود المستخدم مسبقاً
    $check_user = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_user->bind_param("ss", $username, $email);
    $check_user->execute();
    $result = $check_user->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "اسم المستخدم أو البريد الإلكتروني مستخدم بالفعل";
        header("Location: register.html");
        exit();
    }
    
    // إدخال المستخدم الجديد
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("ssss", $username, $email, $phone, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "تم التسجيل بنجاح!";
        
        // إعادة التوجيه إلى لوحة التحكم
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.";
        header("Location: register.html");
        exit();
    }
}
?>'''

with open('registration_system/register_process.php', 'w', encoding='utf-8') as f:
    f.write(register_process_content)

print("تم إنشاء ملف register_process.php بنجاح")