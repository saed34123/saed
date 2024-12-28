<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور | منصة التداول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('images/trading-background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(0, 65, 106, 0.9);
        }

        .nav-link {
            color: white !important;
            font-weight: 600;
        }

        .reset-container {
            max-width: 450px;
            margin: auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-weight: 600;
            color: #00416A;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #00416A;
            box-shadow: 0 0 0 0.2rem rgba(0, 65, 106, 0.25);
        }

        .btn-primary {
            background-color: #00416A;
            border-color: #00416A;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: #005f99;
            border-color: #005f99;
        }

        .reset-title {
            color: #00416A;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .reset-description {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .alert {
            display: none;
            margin-bottom: 20px;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #00416A;
            text-decoration: none;
            font-weight: 600;
        }

        #resetPasswordForm, #newPasswordForm {
            display: none;
        }

        #emailForm {
            display: block;
        }
    </style>
</head>
<body>
    <!-- النافبار -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.html">منصة التداول</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">الرئيسية</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="login.html" class="btn btn-outline-light">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- نموذج استعادة كلمة المرور -->
    <div class="container">
        <div class="reset-container">
            <!-- نموذج إدخال البريد الإلكتروني -->
            <form id="emailForm">
                <h2 class="reset-title">استعادة كلمة المرور</h2>
                <p class="reset-description">أدخل بريدك الإلكتروني المسجل وسنرسل لك رابط إعادة تعيين كلمة المرور</p>
                
                <div class="alert alert-danger" id="emailError" role="alert">
                    البريد الإلكتروني غير مسجل في النظام
                </div>
                
                <div class="alert alert-success" id="emailSuccess" role="alert">
                    تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" required>
                </div>

                <button type="submit" class="btn btn-primary">إرسال رابط التعيين</button>
                
                <div class="back-to-login">
                    <a href="login.html">العودة إلى تسجيل الدخول</a>
                </div>
            </form>

            <!-- نموذج كلمة المرور الجديدة -->
            <form id="newPasswordForm">
                <h2 class="reset-title">تعيين كلمة المرور الجديدة</h2>
                
                <div class="alert alert-danger" id="passwordError" role="alert">
                    كلمتا المرور غير متطابقتين
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" class="form-control" id="newPassword" required>
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" id="confirmPassword" required>
                </div>

                <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // قائمة البريد الإلكتروني المسجلة (محاكاة قاعدة البيانات)
        const registeredEmails = ['user@example.com', 'test@test.com'];

        document.getElementById('emailForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const emailSuccess = document.getElementById('emailSuccess');
            
            // التحقق من وجود البريد الإلكتروني
            if (registeredEmails.includes(email)) {
                emailError.style.display = 'none';
                emailSuccess.style.display = 'block';
                
                // محاكاة إرسال البريد الإلكتروني
                setTimeout(() => {
                    document.getElementById('emailForm').style.display = 'none';
                    document.getElementById('newPasswordForm').style.display = 'block';
                }, 2000);
            } else {
                emailError.style.display = 'block';
                emailSuccess.style.display = 'none';
            }
        });

        document.getElementById('newPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const passwordError = document.getElementById('passwordError');
            
            if (newPassword === confirmPassword) {
                // محاكاة تحديث كلمة المرور
                alert('تم تغيير كلمة المرور بنجاح!');
                window.location.href = 'index.html';
            } else {
                passwordError.style.display = 'block';
            }
        });
    </script>
</body>
</html>