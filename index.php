<?php
require_once 'db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin    = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$currentUser = $_SESSION['user_username'] ?? '';

// جلب أحدث 3 فعاليات من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC LIMIT 3");
$latestEvents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>دليل فعاليات الجامعة الافتراضية</title>
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">الفعاليات</a></li>
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

    <main>
        <!-- Slider (Carousel) -->
        <div id="mainSlider" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <!-- مؤشرات التنقل (indicators) -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainSlider" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner rounded-4 shadow">
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="assets/img/slide1.png" class="d-block w-100" style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h3 class="fw-bold">🎉 مهرجان الفنون والثقافة</h3>
                        <p class="lead">انطلق معنا في رحلة ثقافية مميزة</p>
                        <a href="events.php?category=ثقافي" class="btn btn-primary">اكتشف المزيد</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="assets/img/slide2.png" class="d-block w-100" style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h3 class="fw-bold">⚽ دوري الكليات</h3>
                        <p class="lead">منافسات رياضية حماسية</p>
                        <a href="events.php?category=رياضي" class="btn btn-primary">سجل الآن</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="assets/img/slide3.png" class="d-block w-100" style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-3">
                        <h3 class="fw-bold">💡 ندوة علمية</h3>
                        <p class="lead">ابتكار ومستقبل مشرق</p>
                        <a href="events.php?category=علمي" class="btn btn-primary">احجز مقعدك</a>
                    </div>
                </div>
            </div>

            <!-- أزرار التالي والسابق -->
            <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
            </button>
        </div>

        <!-- تصنيفات سريعة -->
        <section class="container my-5">
            <h2 class="text-center mb-4">تصنيفات سريعة</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light border rounded">ثقافة</div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light border rounded">رياضة</div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light border rounded">موسيقى</div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-light border rounded">علمي</div>
                </div>
            </div>
        </section>

        <!-- أحدث الفعاليات (من قاعدة البيانات) -->
        <section class="container my-5">
            <h2 class="text-center mb-4">أحدث الفعاليات</h2>
            <div class="row">
                <?php foreach ($latestEvents as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 hover-shadow">
                            <img src="assets/img/<?php echo htmlspecialchars($event['image']); ?>"
                                class="card-img-top" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <p class="card-text text-muted flex-grow-1">
                                    <?php echo mb_substr(strip_tags($event['description']), 0, 80) . '...'; ?>
                                </p>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3"></i> <?php echo date('Y/m/d', strtotime($event['event_date'])); ?>
                                    </small>
                                </div>
                                <a href="event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary mt-auto">
                                    <i class="bi bi-eye-fill"></i> التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- فريق العمل -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">فريق العمل</h2>
                <div class="row justify-content-center">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">hani_286646</h5>
                                <p class="text-muted">HTML / CSS / هيكل المشروع</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">esraa_256474</h5>
                                <p class="text-muted">Bootstrap + UI Components</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">ahmad_241214</h5>
                                <p class="text-muted">JavaScript / Slider / Filtering</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title"> mahmoud_alabtah_245368</h5>
                                <p class="text-muted">PHP + MySQL / Backend</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-1">دليل فعاليات الجامعة الافتراضية</p>
            <small>جميع الحقوق محفوظة © 2025</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>