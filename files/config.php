<?php
ob_start();

// التحقق من حالة الجلسة وإعادة تعيينها إذا كانت نشطة
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

// إعدادات الجلسة
ini_set("session.cookie_httponly", 1);
ini_set("session.use_only_cookies", 1);
ini_set("session.cookie_secure", 1);

// بدء جلسة جديدة
session_start();
date_default_timezone_set("Asia/Riyadh");

define("SITE_URL", "https://miembano.com");
define("ADMIN_EMAIL", "admin@miembano.com");
define("MAX_LOGIN_ATTEMPTS", 5);
define("LOGIN_TIMEOUT", 900);

define("SMTP_HOST", "smtp.miembano.com");
define("SMTP_USER", "noreply@miembano.com");
define("SMTP_PASS", "your-smtp-password");
define("SMTP_PORT", 587);

define("DB_HOST", "127.0.0.1");
define("DB_USER", "omtgqdit_root");
define("DB_PASS", "SsSs@@2020");
define("DB_NAME", "omtgqdit_trading_platform");

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    $conn->query("SET time_zone = '+03:00'");
} catch (Exception $e) {
    error_log("خطأ في قاعدة البيانات: " . $e->getMessage());
    die("عذراً، حدث خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.");
}

function sanitize_input($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

function isLoggedIn() {
    return isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]);
}

function getCurrentUser() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION["user_id"];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}
?>'''