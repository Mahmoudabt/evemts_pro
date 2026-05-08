<?php

/**
 * contact.php
 * ملاحظة: ملف زملائك مسمى contact.php.php (خطأ في التسمية)
 * هذا هو الملف الصحيح الذي يجب استخدامه.
 */
require_once 'db_connect.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'الرجاء ملء جميع الحقول.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'البريد الإلكتروني غير صحيح.';
    } elseif (mb_strlen($message) < 10) {
        $error = 'الرسالة قصيرة جداً (10 أحرف على الأقل).';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)"
        );
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':message' => $message,
        ]);
        $success = 'تم إرسال رسالتك بنجاح! شكراً لتواصلك معنا.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا - دليل فعاليات الجامعة الافتراضية</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-calendar-event-fill"></i> دليل الفعاليات
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">الفعاليات</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">عن الدليل</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">اتصل بنا</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">

            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">اتصل بنا</h1>
                <p class="text-muted lead">يمكنك التواصل معنا عبر النموذج التالي</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($success); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form method="POST" action="" id="contactForm" novalidate>
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        الاسم الكامل <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                            placeholder="اكتب اسمك هنا" required>
                                        <div class="invalid-feedback">الرجاء إدخال الاسم الكامل.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        البريد الإلكتروني <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                            placeholder="example@mail.com" required>
                                        <div class="invalid-feedback">الرجاء إدخال بريد إلكتروني صحيح.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">
                                        الرسالة <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="message" name="message"
                                        rows="5" placeholder="اكتب رسالتك هنا" required
                                        minlength="10"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                    <div class="invalid-feedback">الرسالة قصيرة جداً (10 أحرف على الأقل).</div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send-fill me-1"></i> إرسال الرسالة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات التواصل -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-envelope-fill fs-1 text-primary mb-3"></i>
                            <h5>البريد الإلكتروني</h5>
                            <p><a href="mailto:info@svuonline.org">info@svuonline.org</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-whatsapp fs-1 text-success mb-3"></i>
                            <h5>واتساب</h5>
                            <p><a href="tel:963112113469">+963 11 2113469</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-facebook fs-1 text-info mb-3"></i>
                            <h5>فيسبوك</h5>
                            <p><a href="https://facebook.com/svuonline.org" target="_blank">Virtual University</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-1">دليل فعاليات الجامعة الافتراضية</p>
            <small>جميع الحقوق محفوظة © 2025</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Client-side validation (JS)
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const message = document.getElementById('message');
            const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
            let valid = true;

            [name, email, message].forEach(f => f.classList.remove('is-invalid', 'is-valid'));

            if (!name.value.trim()) {
                name.classList.add('is-invalid');
                valid = false;
            } else {
                name.classList.add('is-valid');
            }

            if (!emailPattern.test(email.value.trim())) {
                email.classList.add('is-invalid');
                valid = false;
            } else {
                email.classList.add('is-valid');
            }

            if (message.value.trim().length < 10) {
                message.classList.add('is-invalid');
                valid = false;
            } else {
                message.classList.add('is-valid');
            }

            if (!valid) e.preventDefault();
        });
    </script>
</body>

</html>