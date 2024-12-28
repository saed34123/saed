<?php
// verify.php

require_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // التحقق من صحة الرمز وتاريخ انتهاء الصلاحية
        $stmt = $pdo->prepare("
            SELECT id 
            FROM users 
            WHERE verification_token = ? 
            AND token_expiry > NOW() 
            AND email_verified = 0
        ");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            // تحديث حالة التحقق
            $stmt = $pdo->prepare("
                UPDATE users 
                SET email_verified = 1,
                    verification_token = NULL,
                    token_expiry = NULL
                WHERE verification_token = ?
            ");
            $stmt->execute([$token]);
            
            echo "تم تفعيل حسابك بنجاح! يمكنك الآن تسجيل الدخول.";
        } else {
            echo "رابط التحقق غير صالح أو منتهي الصلاحية.";
        }
    } catch(PDOException $e) {
        echo "حدث خطأ في عملية التحقق.";
    }
} else {
    echo "رمز التحقق مفقود.";
}
?>