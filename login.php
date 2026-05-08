<?php
session_start();
require_once 'db.php';

// إذا كان مسجلاً بالفعل، أعده للرئيسية
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'الرجاء إدخال اسم المستخدم وكلمة المرور.';
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id']        = $user['id'];
            $_SESSION['user_username']  = $user['username'];
            $_SESSION['role']           = $user['role']; // 'admin' أو 'user'

            // إذا كان أدمن، وجّهه للوحة التحكم
            if ($user['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $user['id'];
                $_SESSION['admin_username']  = $user['username'];
                header('Location: admin/dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - دليل الفعاليات</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-radius: 1rem 1rem 0 0;
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .login-header i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: block;
        }
    </style>
</head>

<body>
    <div class="login-card card">
        <div class="login-header">
            <i class="bi bi-person-circle"></i>
            <h4 class="mb-0">تسجيل الدخول</h4>
            <small class="opacity-75">دليل فعاليات الجامعة الافتراضية</small>
        </div>
        <div class="card-body p-4">

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">اسم المستخدم</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                            placeholder="أدخل اسم المستخدم" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="أدخل كلمة المرور" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> دخول
                </button>
            </form>

            <hr>
            <div class="text-center">
                <span class="text-muted small">ليس لديك حساب؟</span>
                <a href="register.php" class="small fw-semibold"> إنشاء حساب جديد</a>
            </div>
            <div class="text-center mt-2">
                <a href="index.php" class="text-muted small">
                    <i class="bi bi-arrow-right me-1"></i> العودة للموقع
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>