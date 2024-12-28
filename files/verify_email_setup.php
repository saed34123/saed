<?php
// verify_email_setup.php

require_once 'config.php';

try {
    // إضافة أعمدة التحقق إلى جدول المستخدمين
    $sql = "ALTER TABLE users 
            ADD COLUMN email_verified TINYINT(1) DEFAULT 0,
            ADD COLUMN verification_token VARCHAR(255),
            ADD COLUMN token_expiry DATETIME";
    
    $pdo->exec($sql);
    
    echo "تم إضافة أعمدة التحقق بنجاح";
} catch(PDOException $e) {
    echo "خطأ: " . $e->getMessage();
}
?>