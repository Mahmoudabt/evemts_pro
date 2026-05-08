<?php
require_once 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin    = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$currentUser = $_SESSION['user_username'] ?? '';

// جلب معرف الفعالية
$id = $_GET['id'] ?? 0;

// جلب تفاصيل الفعالية
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute([':id' => $id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: events.php');
    exit;
}

// جلب فعاليات ذات صلة (نفس التصنيف)
$stmt = $pdo->prepare("SELECT * FROM events WHERE category = :category AND id != :id LIMIT 3");
$stmt->execute([':category' => $event['category'], ':id' => $id]);
$relatedEvents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - دليل الفعاليات</title>
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
                    <li class="nav-item"><a class="nav-link active" href="events.php">الفعاليات</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">عن الدليل</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">اتصل بنا</a></li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item"><a class="nav-link text-warning fw-bold" href="admin/dashboard.php"><i class="bi bi-speedometer2"></i> لوحة التحكم</a></li>
                    <?php endif; ?>
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> خروج (<?php echo htmlspecialchars($currentUser); ?>)</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-person-fill"></i> دخول</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="events.php">الفعاليات</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($event['title']); ?></li>
                </ol>
            </nav>

            <!-- أزرار الأدمن -->
            <?php if ($isAdmin): ?>
            <div class="mb-4 d-flex gap-2">
                <a href="admin/edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-warning">
                    <i class="bi bi-pencil-fill"></i> تعديل الفعالية
                </a>
                <a href="admin/delete_event.php?id=<?php echo $event['id']; ?>"
                   class="btn btn-danger"
                   onclick="return confirm('هل أنت متأكد من حذف هذه الفعالية؟')">
                    <i class="bi bi-trash-fill"></i> حذف الفعالية
                </a>
            </div>
            <?php endif; ?>

            <!-- ===== تصميم من عمودين (Bootstrap Grid) ===== -->
            <div class="row">

                <!-- العمود الأيسر: الصورة (4 أجزاء) - يتحول للأعلى على الهواتف -->
                <div class="col-lg-4 order-lg-2 mb-4">
                    <div class="sticky-top" style="top: 80px;">
                        <img src="assets/img/<?php echo htmlspecialchars($event['image']); ?>"
                            class="img-fluid rounded-4 event-detail-image w-100"
                            style="max-height: 400px; object-fit: cover;"
                            alt="<?php echo htmlspecialchars($event['title']); ?>">

                        <!-- معلومات سريعة -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">معلومات سريعة</h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="bi bi-calendar-check text-primary ms-2"></i>
                                        <strong>التاريخ:</strong>
                                        <?php echo date('Y/m/d', strtotime($event['event_date'])); ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-geo-alt-fill text-danger ms-2"></i>
                                        <strong>المكان:</strong>
                                        <?php echo htmlspecialchars($event['location']); ?>
                                    </li>
                                    <li>
                                        <i class="bi bi-tag-fill text-success ms-2"></i>
                                        <strong>التصنيف:</strong>
                                        <span class="badge bg-primary">
                                            <?php echo htmlspecialchars($event['category']); ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العمود الأيمن: المحتوى (8 أجزاء) -->
                <div class="col-lg-8 order-lg-1">

                    <!-- عنوان + شارة -->
                    <div class="mb-4">
                        <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h1>
                        <span class="badge bg-primary fs-6">
                            <?php echo htmlspecialchars($event['category']); ?>
                        </span>
                    </div>

                    <!-- وصف كامل -->
                    <div class="mb-5">
                        <h4><i class="bi bi-file-text ms-2"></i>وصف الفعالية</h4>
                        <div class="text-muted">
                            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        </div>
                    </div>

                    <!-- ===== أزرار الإجراءات (Bootstrap Buttons) ===== -->
                    <div class="mb-4">
                        <button class="btn btn-primary me-2 mb-2" onclick="addToCalendar()">
                            <i class="bi bi-calendar-plus"></i> أضف إلى التقويم
                        </button>

                        <button class="btn btn-outline-primary me-2 mb-2" onclick="shareEvent()">
                            <i class="bi bi-share-fill"></i> شارك الفعالية
                        </button>

                        <!-- زر حجز تذكرة (يفتح Modal) -->
                        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <i class="bi bi-ticket-perforated-fill"></i> حجز تذكرة
                        </button>
                    </div>

                    <!-- ===== تنبيهات Bootstrap Alerts للرسائل ===== -->
                    <div id="actionAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="alertMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- ===== قسم "فعاليات ذات صلة" (بطاقات أفقية) ===== -->
                    <?php if (!empty($relatedEvents)): ?>
                        <div class="mt-5">
                            <h3 class="mb-4">
                                <i class="bi bi-link-45deg"></i> فعاليات ذات صلة
                            </h3>

                            <div class="row related-event-card">
                                <?php foreach ($relatedEvents as $related): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card hover-shadow">
                                            <div class="row g-0">
                                                <!-- صورة مصغرة -->
                                                <div class="col-4">
                                                    <img src="assets/img/<?php echo htmlspecialchars($related['image']); ?>"
                                                        class="img-fluid h-100"
                                                        style="object-fit: cover;"
                                                        alt="<?php echo htmlspecialchars($related['title']); ?>">
                                                </div>
                                                <!-- معلومات -->
                                                <div class="col-8">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <?php echo htmlspecialchars($related['title']); ?>
                                                        </h6>
                                                        <small class="text-muted d-block mb-2">
                                                            <i class="bi bi-calendar3"></i>
                                                            <?php echo date('Y/m/d', strtotime($related['event_date'])); ?>
                                                        </small>
                                                        <a href="event.php?id=<?php echo $related['id']; ?>"
                                                            class="btn btn-sm btn-outline-primary">
                                                            عرض التفاصيل
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

    <!-- ===== Modal - حجز تذكرة (Bootstrap Modal) ===== -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-ticket-perforated"></i> حجز تذكرة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">عدد التذاكر</label>
                            <select class="form-select">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="confirmBooking()">
                        تأكيد الحجز
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-1">دليل فعاليات الجامعة الافتراضية</p>
            <small>جميع الحقوق محفوظة © 2025</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // إضافة للتقويم - عرض تنبيه Bootstrap
        function addToCalendar() {
            showAlert('تمت إضافة الفعالية إلى تقويمك بنجاح! 🎉', 'success');
        }

        // مشاركة - نسخ الرابط
        function shareEvent() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showAlert('تم نسخ رابط الفعالية إلى الحافظة! 📋', 'info');
            });
        }

        // تأكيد الحجز من Modal
        function confirmBooking() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
            modal.hide();
            showAlert('تم حجز التذكرة بنجاح! سيتم إرسال التفاصيل إلى بريدك الإلكتروني. 🎫', 'success');
        }

        // عرض التنبيه
        function showAlert(message, type) {
            const alert = document.getElementById('actionAlert');
            const alertMessage = document.getElementById('alertMessage');

            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alertMessage.textContent = message;
            alert.classList.remove('d-none');

            setTimeout(() => {
                alert.classList.add('d-none');
            }, 4000);
        }
    </script>
</body>

</html>