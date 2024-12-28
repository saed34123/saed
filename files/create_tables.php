# التحقق من وجود MySQL وتثبيته إذا لم يكن موجوداً
print("تثبيت MySQL إذا لم يكن موجوداً...")
!apt-get update && apt-get install -y mysql-server

# بدء خدمة MySQL
print("\
بدء خدمة MySQL...")
!service mysql start

# إنشاء قاعدة البيانات والمستخدم
print("\
إنشاء قاعدة البيانات والمستخدم...")
import mysql.connector
from mysql.connector import Error

try:
    connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password=""
    )
    
    if connection.is_connected():
        cursor = connection.cursor()
        
        # إنشاء قاعدة البيانات
        cursor.execute("CREATE DATABASE IF NOT EXISTS omtgqdit_trading_platform")
        
        # إنشاء المستخدم ومنحه الصلاحيات
        cursor.execute("CREATE USER IF NOT EXISTS 'omtgqdit_trading'@'localhost' IDENTIFIED BY 'F0*UmtmG3h!x'")
        cursor.execute("GRANT ALL PRIVILEGES ON omtgqdit_trading_platform.* TO 'omtgqdit_trading'@'localhost'")
        cursor.execute("FLUSH PRIVILEGES")
        
        print("تم إنشاء قاعدة البيانات والمستخدم بنجاح")
        
except Error as e:
    print("خطأ:", e)
finally:
    if 'connection' in locals() and connection.is_connected():
        cursor.close()
        connection.close()

# إنشاء الجداول
print("\
إنشاء الجداول...")
try:
    connection = mysql.connector.connect(
        host="localhost",
        user="omtgqdit_trading",
        password="F0*UmtmG3h!x",
        database="omtgqdit_trading_platform"
    )
    
    if connection.is_connected():
        cursor = connection.cursor()
        
        # جدول المستخدمين
        users_table = """
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(20) NOT NULL,
            password VARCHAR(255) NOT NULL,
            balance DECIMAL(15,2) DEFAULT 0.00,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
            email_verified TINYINT(1) DEFAULT 0,
            verification_token VARCHAR(100),
            reset_token VARCHAR(100),
            reset_token_expires DATETIME,
            last_login DATETIME,
            UNIQUE KEY unique_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        """
        cursor.execute(users_table)
        
        # جدول سجل النشاطات
        activity_log_table = """
        CREATE TABLE IF NOT EXISTS activity_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            action VARCHAR(50) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        """
        cursor.execute(activity_log_table)
        
        # جدول المعاملات
        transactions_table = """
        CREATE TABLE IF NOT EXISTS transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        """
        cursor.execute(transactions_table)
        
        print("تم إنشاء جميع الجداول بنجاح")
        
except Error as e:
    print("خطأ:", e)
finally:
    if 'connection' in locals() and connection.is_connected():
        cursor.close()
        connection.close()

# التحقق من الجداول
print("\
التحقق من الجداول المنشأة...")
try:
    connection = mysql.connector.connect(
        host="localhost",
        user="omtgqdit_trading",
        password="F0*UmtmG3h!x",
        database="omtgqdit_trading_platform"
    )
    
    if connection.is_connected():
        cursor = connection.cursor()
        cursor.execute("SHOW TABLES")
        tables = cursor.fetchall()
        print("\
الجداول الموجودة في قاعدة البيانات:")
        for table in tables:
            print(table[0])
            
except Error as e:
    print("خطأ:", e)
finally:
    if 'connection' in locals() and connection.is_connected():
        cursor.close()
        connection.close()