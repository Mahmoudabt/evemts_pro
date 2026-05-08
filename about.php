<?php
require_once 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عن الدليل - دليل فعاليات الجامعة الافتراضية</title>
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
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-fill"></i> الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="events.php">
                            <i class="bi bi-grid-fill"></i> الفعاليات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php">
                            <i class="bi bi-info-circle-fill"></i> عن الدليل
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">
                            <i class="bi bi-envelope-fill"></i> اتصل بنا
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">

            <!-- عنوان الصفحة -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">عن دليل الفعاليات</h1>
                <p class="text-muted lead">تعرف على منصة دليل فعاليات الجامعة الافتراضية</p>
            </div>

            <div class="row">
                <!-- العمود الأيمن: معلومات عن الدليل -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h3 mb-4">
                                <i class="bi bi-info-square-fill text-primary ms-2"></i>
                                نبذة عن المشروع
                            </h2>
                            <p class="text-muted mb-4">
                                دليل فعاليات الجامعة الافتراضية هو منصة إلكترونية متكاملة تهدف إلى تجميع وعرض جميع
                                الفعاليات والأنشطة التي تقام داخل الجامعة الافتراضية. يسهل الدليل على الطلاب وأعضاء
                                الهيئة التدريسية والزوار اكتشاف الفعاليات المتنوعة في مختلف المجالات.
                            </p>

                            <h2 class="h3 mb-4 mt-5">
                                <i class="bi bi-star-fill text-warning ms-2"></i>
                                رؤيتنا
                            </h2>
                            <p class="text-muted mb-4">
                                نسعى لأن نكون المنصة الرائدة في عالم الفعاليات الجامعية، حيث نوفر تجربة سلسة ومتكاملة
                                لجميع المستخدمين، ونسهم في تعزيز النشاط الثقافي والعلمي والرياضي داخل الجامعة.
                            </p>

                            <h2 class="h3 mb-4 mt-5">
                                <i class="bi bi-gear-fill text-success ms-2"></i>
                                المميزات
                            </h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success fs-4 ms-3"></i>
                                        <span>عرض جميع الفعاليات بسهولة</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success fs-4 ms-3"></i>
                                        <span>بحث وتصفية متقدم</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success fs-4 ms-3"></i>
                                        <span>تصميم متجاوب مع جميع الأجهزة</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success fs-4 ms-3"></i>
                                        <span>تفاصيل شاملة لكل فعالية</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العمود الأيسر: إحصائيات سريعة -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-calendar-event fs-1 text-primary mb-3 d-block"></i>
                            <h2 class="display-5 fw-bold text-primary">
                                <?php
                                $stmt = $pdo->query("SELECT COUNT(*) FROM events");
                                echo $stmt->fetchColumn();
                                ?>
                            </h2>
                            <p class="text-muted mb-0">فعالية منوعة</p>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h4 class="mb-3">
                                <i class="bi bi-people-fill text-primary ms-2"></i>
                                فريق العمل
                            </h4>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item bg-transparent px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong>hani_286646</strong>
                                            <p class="text-muted small mb-0">HTML / CSS / هيكل المشروع</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item bg-transparent px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong>esraa_256474</strong>
                                            <p class="text-muted small mb-0">Bootstrap + UI Components</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item bg-transparent px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong>ahmad_241214</strong>
                                            <p class="text-muted small mb-0">JavaScript / Slider / Filtering</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item bg-transparent px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <strong>mahmoud_alabtah_245368</strong>
                                            <p class="text-muted small mb-0">PHP + MySQL / Backend</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- التقنيات المستخدمة -->
            <div class="mt-5">
                <div class="card shadow-sm border-0 rounded-4 bg-dark text-white">
                    <div class="card-body p-5 text-center">
                        <h3 class="mb-4">
                            <i class="bi bi-code-slash ms-2"></i>
                            التقنيات المستخدمة
                        </h3>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <span class="badge bg-primary fs-6 p-3">HTML5</span>
                            <span class="badge bg-info fs-6 p-3">CSS3</span>
                            <span class="badge bg-warning fs-6 p-3 text-dark">JavaScript</span>
                            <span class="badge bg-success fs-6 p-3">Bootstrap 5</span>
                            <span class="badge bg-secondary fs-6 p-3">PHP</span>
                            <span class="badge bg-danger fs-6 p-3">MySQL</span>
                            <span class="badge bg-light text-dark fs-6 p-3">PDO</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
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