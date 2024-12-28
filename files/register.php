<?php
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    try {
        // التحقق من وجود جميع البيانات المطلوبة
        $required_fields = ['username', 'email', 'phone', 'password', 'securityCode'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("جميع الحقول مطلوبة");
            }
        }

        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $phone = sanitize_input($_POST['phone']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $security_code = sanitize_input($_POST['securityCode']);

        // التحقق من عدم وجود المستخدم
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
        if (!$check_stmt) {
            throw new Exception($conn->error);
        }
        
        $check_stmt->bind_param("ss", $email, $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("المستخدم موجود بالفعل");
        }

        // إضافة المستخدم الجديد
        $insert_stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, security_code) VALUES (?, ?, ?, ?, ?)");
        if (!$insert_stmt) {
            throw new Exception($conn->error);
        }

        $insert_stmt->bind_param("sssss", $username, $email, $phone, $password, $security_code);
        
        if (!$insert_stmt->execute()) {
            throw new Exception("فشل في تنفيذ عملية التسجيل: " . $insert_stmt->error);
        }

        echo json_encode(['status' => 'success', 'message' => 'تم التسجيل بنجاح']);
        exit;

    } catch (Exception $e) {
        error_log("خطأ في التسجيل: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3') center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 90%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .input-group {
            position: relative;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(74, 175, 80, 0.2);
        }
        .password-toggle {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }
        .password-info {
            display: flex;
            align-items: center;
            margin-top: 8px;
            font-size: 13px;
        }
        .password-info i {
            margin-left: 5px;
        }
        .weak { background-color: #ff4444; }
        .medium { background-color: #ffa700; }
        .strong { background-color: #00c851; }
        
        .security-code-info {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        .security-code-info i {
            color: #ffa700;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-link a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        .error {
            color: #ff4444;
            font-size: 13px;
            margin-top: 5px;
        }
        .iti {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-title">إنشاء حساب جديد</h2>
        <form id="registerForm" method="POST">
            <!-- اسم المستخدم -->
            <div class="form-group">
                <label>اسم المستخدم</label>
                <input type="text" id="username" required>
            </div>

            <!-- البريد الإلكتروني -->
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" id="email" required>
            </div>

            <!-- تأكيد البريد الإلكتروني -->
            <div class="form-group">
                <label>تأكيد البريد الإلكتروني</label>
                <input type="email" id="confirmEmail" required>
            </div>

            <!-- رقم الهاتف -->
            <div class="form-group">
                <label>رقم الهاتف</label>
                <input type="tel" id="phone" required>
            </div>

            <!-- كلمة المرور -->
            <div class="form-group">
                <label>كلمة المرور</label>
                <div class="input-group">
                    <input type="password" id="password" required>
                    <i class="password-toggle fas fa-eye"></i>
                </div>
                <div class="password-strength"></div>
                <div class="password-info">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>تاكد من ان تمتلك كلمة مرور قوية تحتوي علي ارقام واحرف ورموز @#$
                    </span>
                </div>
            </div>

            <!-- تأكيد كلمة المرور -->
            <div class="form-group">
                <label>تأكيد كلمة المرور</label>
                <div class="input-group">
                    <input type="password" id="confirmPassword" required>
                    <i class="password-toggle fas fa-eye"></i>
                </div>
            </div>

            <!-- كود الأمان -->
            <div class="form-group">
                <label>كود الأمان (12 رقم)</label>
                <input type="text" id="securityCode" maxlength="12" pattern="\d{12}" required>
                <div class="security-code-info">
                    <i class="fas fa-exclamation-circle"></i>
                    هذا الكود مهم جداً
                </div>
            </div>

            <!-- تأكيد كود الأمان -->
            <div class="form-group">
                <label>تأكيد كود الأمان</label>
                <input type="text" id="confirmSecurityCode" maxlength="12" pattern="\d{12}" required>
            </div>

            <button type="submit" class="submit-btn">إنشاء حساب</button>
        </form>

<div class="login-link">
    <p>لديك حساب بالفعل؟ <a href="login.html">تسجيل الدخول</a></p>
</div>

<style>
    /* أنماط رابط تسجيل الدخول */
    .login-link {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .login-link p {
        color: #666;
        margin: 0;
    }

    .login-link a {
        color: #4CAF50;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
    }

    .login-link a:hover {
        color: #45a049;
        text-decoration: underline;
    }
</style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        // تهيئة مكتبة رموز الدول للهاتف
        const phoneInput = document.querySelector("#phone");
        const iti = window.intlTelInput(phoneInput, {
            preferredCountries: ["sa", "ae", "kw", "bh", "om", "qa"],
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // التحقق من قوة كلمة المرور
        const passwordInput = document.querySelector("#password");
        const strengthBar = document.querySelector(".password-strength");
        const passwordInfo = document.querySelector(".password-info span");

        passwordInput.addEventListener("input", function() {
            const password = this.value;
            let strength = 0;
            
            // التحقق من وجود أرقام
            if (password.match(/\d+/)) strength += 1;
            // التحقق من وجود أحرف صغيرة وكبيرة
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            // التحقق من وجود رموز خاصة
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

            strengthBar.className = "password-strength";
            if (strength === 1) {
                strengthBar.classList.add("weak");
                passwordInfo.textContent = "كلمة المرور ضعيفة";
            } else if (strength === 2) {
                strengthBar.classList.add("medium");
                passwordInfo.textContent = "كلمة المرور متوسطة";
            } else if (strength === 3) {
                strengthBar.classList.add("strong");
                passwordInfo.textContent = "كلمة المرور قوية";
            }
        });

        // إظهار/إخفاء كلمة المرور
        document.querySelectorAll(".password-toggle").forEach(toggle => {
            toggle.addEventListener("click", function() {
                const input = this.previousElementSibling;
                if (input.type === "password") {
                    input.type = "text";
                    this.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    this.classList.replace("fa-eye-slash", "fa-eye");
                }
            });
        });

        // التحقق من تطابق البريد الإلكتروني
        const emailInput = document.querySelector("#email");
        const confirmEmailInput = document.querySelector("#confirmEmail");
        
        confirmEmailInput.addEventListener("input", function() {
            if (this.value !== emailInput.value) {
                this.setCustomValidity("البريد الإلكتروني غير متطابق");
            } else {
                this.setCustomValidity("");
            }
        });

        // التحقق من تطابق كلمة المرور
        const confirmPasswordInput = document.querySelector("#confirmPassword");
        
        confirmPasswordInput.addEventListener("input", function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity("كلمة المرور غير متطابقة");
            } else {
                this.setCustomValidity("");
            }
        });

        // التحقق من تطابق كود الأمان
        const securityCodeInput = document.querySelector("#securityCode");
        const confirmSecurityCodeInput = document.querySelector("#confirmSecurityCode");
        
        confirmSecurityCodeInput.addEventListener("input", function() {
            if (this.value !== securityCodeInput.value) {
                this.setCustomValidity("كود الأمان غير متطابق");
            } else {
                this.setCustomValidity("");
            }
        });

        // التحقق من النموذج قبل الإرسال
        document.querySelector("#registerForm").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // التحقق من صحة رقم الهاتف
            if (!iti.isValidNumber()) {
                alert("الرجاء إدخال رقم هاتف صحيح");
                return;
            }

            // يمكنك إضافة المزيد من التحققات هنا

            // إرسال النموذج
            this.submit();
        });
    </script>
</body>
<!-- إضافة هذا الجزء في نهاية body قبل إغلاق الوسم -->
<div class="success-modal" id="successModal">
    <div class="success-content">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3>تم إنشاء حسابك بنجاح!</h3>
        <p>جاري تحويلك إلى لوحة التحكم...</p>
    </div>
</div>

<style>
    /* أنماط رسالة النجاح */
    .success-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .success-content {
        background: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .success-icon {
        font-size: 60px;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .success-icon i {
        animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
        }
        to {
            transform: scale(1);
        }
    }

    .success-content h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .success-content p {
        color: #666;
    }
</style>

<script>
    // تعديل معالج النموذج
    document.querySelector("#registerForm").addEventListener("submit", function(e) {
        e.preventDefault();
        
        // التحقق من صحة البيانات
        if (this.checkValidity()) {
            // إظهار رسالة النجاح
            const successModal = document.getElementById('successModal');
            successModal.style.display = 'flex';

            // إعادة التوجيه بعد 3 ثواني
            setTimeout(() => {
                window.location.href = '/dashboard.html'; // تغيير المسار حسب هيكل موقعك
            }, 3000);
        }
    });
</script>
</html>

