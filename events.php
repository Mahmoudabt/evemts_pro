<?php
require_once 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin    = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$currentUser = $_SESSION['user_username'] ?? '';

// جلب التصنيفات للفلترة
$stmt = $pdo->query("SELECT DISTINCT category FROM events ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// الفلترة والبحث
$where = [];
$params = [];

// بحث نصي
if (!empty($_GET['search'])) {
    $where[] = "(title LIKE :search OR description LIKE :search2)";
    $params[':search'] = "%{$_GET['search']}%";
    $params[':search2'] = "%{$_GET['search']}%";
}

// فلترة حسب التصنيف
if (!empty($_GET['category'])) {
    $where[] = "category = :category";
    $params[':category'] = $_GET['category'];
}

// فلترة حسب التاريخ (جديد)
if (!empty($_GET['event_date'])) {
    $where[] = "event_date = :event_date";
    $params[':event_date'] = $_GET['event_date'];
}

// ترتيب حسب التاريخ (الأحدث أولاً أو الأقدم أولاً)
$orderBy = "event_date DESC";
if (!empty($_GET['sort']) && $_GET['sort'] == 'oldest') {
    $orderBy = "event_date ASC";
}

$sql = "SELECT * FROM events";
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY $orderBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();
?>
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الفعاليات - دليل فعاليات الجامعة الافتراضية</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

    <!-- ===== Navbar ===== -->
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

    <!-- ===== Main Content ===== -->
    <main class="py-5">
        <div class="container">

            <!-- عنوان الصفحة -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold mb-3">جميع الفعاليات</h1>
                <p class="text-muted lead">استعرض جميع الفعاليات المتاحة في الجامعة الافتراضية</p>
                <?php if ($isAdmin): ?>
                <a href="admin/add_event.php" class="btn btn-success mt-2">
                    <i class="bi bi-plus-circle-fill"></i> إضافة فعالية جديدة
                </a>
                <?php endif; ?>
            </div>

            <!-- ===== شريط البحث والفلترة (Bootstrap Grid + Forms) ===== -->
            <div class="card filter-card mb-5">
                <div class="card-body p-4">
                    <form method="GET" action="events.php" class="row g-3 align-items-end">
                        <!-- حقل البحث -->
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-search"></i> بحث
                            </label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث عن فعالية..."
                                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- فلتر التصنيف (قائمة منسدلة) -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-funnel"></i> التصنيف
                            </label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">جميع التصنيفات</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"
                                        <?php echo ($_GET['category'] ?? '') === $cat ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- فلترة حسب التاريخ -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar-range"></i> التاريخ
                            </label>
                            <input type="date" name="event_date" class="form-control"
                                value="<?php echo htmlspecialchars($_GET['event_date'] ?? ''); ?>"
                                onchange="this.form.submit()">
                        </div>

                        <!-- خيار الترتيب -->
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-sort-down"></i> ترتيب
                            </label>
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" <?php echo ($_GET['sort'] ?? 'newest') == 'newest' ? 'selected' : ''; ?>>الأحدث أولاً</option>
                                <option value="oldest" <?php echo ($_GET['sort'] ?? '') == 'oldest' ? 'selected' : ''; ?>>الأقدم أولاً</option>
                            </select>
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="col-lg-2 col-md-6">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel-fill"></i> تصفية
                            </button>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <a href="events.php" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-counterclockwise"></i> إعادة تعيين
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ===== تنبيه بعدد النتائج (Bootstrap Alert) ===== -->
            <?php if (!empty($_GET['search']) || !empty($_GET['category'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill"></i>
                    تم العثور على <strong><?php echo count($events); ?></strong> فعالية
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ===== شبكة البطاقات (Bootstrap Grid Cards) ===== -->
            <div class="row">
                <?php if (empty($events)): ?>
                    <!-- تنبيه عدم وجود نتائج -->
                    <div class="col-12">
                        <div class="alert alert-warning text-center py-5" role="alert">
                            <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
                            <h4>لا توجد فعاليات متاحة</h4>
                            <p>لم يتم العثور على فعاليات تطابق معايير البحث</p>
                            <a href="events.php" class="btn btn-primary">عرض جميع الفعاليات</a>
                        </div>
                    </div>
                <?php else: ?>

                    <?php foreach ($events as $event): ?>
                        <!-- col-lg-3 = 4 بطاقات | col-md-6 = 2 بطاقات | الهواتف: بطاقة واحدة -->
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 hover-shadow">
                                <!-- صورة الفعالية -->
                                <div class="position-relative">
                                    <img src="assets/img/<?php echo htmlspecialchars($event['image']); ?>"
                                        class="card-img-top"
                                        style="height: 200px; object-fit: cover;"
                                        alt="<?php echo htmlspecialchars($event['title']); ?>">

                                    <!-- ===== شارة التصنيف (Bootstrap Badge) ===== -->
                                    <span class="badge position-absolute top-0 end-0 m-2 
                                <?php
                                $badgeColors = [
                                    'تقني' => 'bg-primary',
                                    'ثقافي' => 'bg-purple',
                                    'رياضي' => 'bg-danger',
                                    'موسيقي' => 'bg-success',
                                    'علمي' => 'bg-info'
                                ];
                                echo $badgeColors[$event['category']] ?? 'bg-secondary';
                                ?>" style="background-color: <?php echo $event['category'] === 'ثقافي' ? '#6f42c1' : ''; ?>">
                                        <?php echo htmlspecialchars($event['category']); ?>
                                    </span>
                                </div>

                                <!-- محتوى البطاقة -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>

                                    <p class="card-text text-muted flex-grow-1">
                                        <?php echo mb_substr(strip_tags($event['description']), 0, 80) . '...'; ?>
                                    </p>

                                    <!-- معلومات إضافية -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="bi bi-calendar3 text-primary ms-2"></i>
                                            <small class="text-muted">
                                                <?php echo date('Y/m/d', strtotime($event['event_date'])); ?>
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-danger ms-2"></i>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($event['location']); ?>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- زر التفاصيل -->
                                    <a href="event.php?id=<?php echo $event['id']; ?>"
                                        class="btn btn-primary w-100 mb-2">
                                        <i class="bi bi-eye-fill"></i> عرض التفاصيل
                                    </a>
                                    <?php if ($isAdmin): ?>
                                    <div class="d-flex gap-2">
                                        <a href="admin/edit_event.php?id=<?php echo $event['id']; ?>"
                                           class="btn btn-warning btn-sm flex-fill">
                                            <i class="bi bi-pencil-fill"></i> تعديل
                                        </a>
                                        <a href="admin/delete_event.php?id=<?php echo $event['id']; ?>"
                                           class="btn btn-danger btn-sm flex-fill"
                                           onclick="return confirm('هل أنت متأكد من حذف هذه الفعالية؟')">
                                            <i class="bi bi-trash-fill"></i> حذف
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- ===== Footer ===== -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-1 fs-5">دليل فعاليات الجامعة الافتراضية</p>
            <small class="text-white-50">جميع الحقوق محفوظة © 2025</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>