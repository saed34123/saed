<?php
// email_verification.php

function sendVerificationEmail($email, $token) {
    $to = $email;
    $subject = "تأكيد البريد الإلكتروني - منصة التداول";
    
    $verification_link = "https://yourwebsite.com/verify.php?token=" . $token;
    
    $message = "
    <html>
    <head>
        <title>تأكيد البريد الإلكتروني</title>
    </head>
    <body dir='rtl'>
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2>مرحباً بك في منصة التداول</h2>
            <p>شكراً لتسجيلك معنا. لتفعيل حسابك، يرجى النقر على الرابط أدناه:</p>
            <p><a href='$verification_link' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>تأكيد البريد الإلكتروني</a></p>
            <p>أو قم بنسخ الرابط التالي في متصفحك:</p>
            <p>$verification_link</p>
            <p>ينتهي هذا الرابط خلال 24 ساعة.</p>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: منصة التداول <noreply@yourwebsite.com>" . "\r\n";

    return mail($to, $subject, $message, $headers);
}
?>