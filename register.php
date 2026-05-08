<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    if (empty($username) || empty($password) || empty($password2)) {
        $error = 'جميع الحقول مطلوبة.';
    } elseif (mb_strlen($username) < 3) {
        $error = 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل.';
    } elseif ($password !== $password2) {
        $error = 'كلمتا المرور غير متطابقتين.';
    } elseif (mb_strlen($password) < 6) {
        $error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        if ($stmt->fetch()) {
            $error = 'اسم المستخدم محجوز، جرّب اسماً آخر.';
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:u, :p, 'user')");
            $stmt->execute([':u' => $username, ':p' => $hashed]);
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - دليل الفعاليات</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 440px;
        }

        .register-header {
            background: linear-gradient(135deg, #198754, #146c43);
            border-radius: 1rem 1rem 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .register-header i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
</head>

<body>
    <div class="register-card card">
        <div class="register-header">
            <i class="bi bi-person-plus-fill"></i>
            <h4 class="mb-0">إنشاء حساب جديد</h4>
            <small class="opacity-75">دليل فعاليات الجامعة الافتراضية</small>
        </div>
        <div class="card-body p-4">

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success text-center">
                    <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                    <strong>تم إنشاء حسابك بنجاح!</strong>
                    <div class="mt-3">
                        <a href="login.php" class="btn btn-success w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> تسجيل الدخول الآن
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">اسم المستخدم</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" name="username"
                                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                placeholder="اختر اسم مستخدم (3 أحرف على الأقل)"
                                required autofocus minlength="3">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="password"
                                placeholder="6 أحرف على الأقل" required minlength="6">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">تأكيد كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="password2"
                                placeholder="أعد إدخال كلمة المرور" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="bi bi-person-check-fill me-1"></i> إنشاء الحساب
                    </button>
                </form>

                <hr>
                <div class="text-center">
                    <span class="text-muted small">لديك حساب بالفعل؟</span>
                    <a href="login.php" class="small fw-semibold"> تسجيل الدخول</a>
                </div>
                <div class="text-center mt-2">
                    <a href="index.php" class="text-muted small">
                        <i class="bi bi-arrow-right me-1"></i> العودة للموقع
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>