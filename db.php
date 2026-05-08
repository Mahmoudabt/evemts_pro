<?php

/**
 * db.php — ملف الاتصال بقاعدة البيانات
 * قاعدة البيانات: city_events
 * تقنية الاتصال: PDO مع معالجة استثناءات كاملة
 */

//define('DB_HOST', 'localhost');
// define('DB_HOST', '127.0.0.1:3307');

// define('DB_NAME', 'city_events');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_CHARSET', 'utf8mb4');
define('DB_HOST',    'sql302.infinityfree.com');
define('DB_PORT',    '3306');
define('DB_NAME',    'if0_41861627_city_events');
define('DB_USER',    'if0_41861627');
define('DB_PASS',    'f60p971ZwYFJwha');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    //  die('خطأ: ' . $e->getMessage());
    // أظهر الخطأ الحقيقي

    // في بيئة الإنتاج لا نعرض تفاصيل الخطأ للمستخدم
    http_response_code(500);
    die('<div style="font-family:Arial;text-align:center;margin-top:50px;color:#dc3545;">
        <h2>خطأ في الاتصال بقاعدة البيانات</h2>
        <p>يرجى التواصل مع المسؤول.</p>
        <!-- ' . htmlspecialchars($e->getMessage()) . ' -->
    </div>');
}
