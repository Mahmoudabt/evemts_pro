<?php
require_once '_auth_check.php';
require_once '../db.php';

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- تعقيم وتحقق من المدخلات ---
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $category    = trim($_POST['category']    ?? '');
    $location    = trim($_POST['location']    ?? '');
    $event_date  = trim($_POST['event_date']  ?? '');

    if (empty($title))       $errors[] = 'عنوان الفعالية مطلوب.';
    if (empty($description)) $errors[] = 'وصف الفعالية مطلوب.';
    if (empty($category))    $errors[] = 'التصنيف مطلوب.';
    if (empty($location))    $errors[] = 'مكان الفعالية مطلوب.';
    if (empty($event_date))  $errors[] = 'تاريخ الفعالية مطلوب.';

    // --- معالجة رفع الصورة ---
    $imageName = 'default.png';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize      = 5 * 1024 * 1024; // 5MB

        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['image']['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = 'نوع الملف غير مسموح. الأنواع المقبولة: JPEG, PNG, GIF, WEBP.';
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = 'حجم الصورة يتجاوز الحد المسموح (5MB).';
        } else {
            $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('event_', true) . '.' . strtolower($ext);
            $uploadDir = '../assets/img/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
                $errors[] = 'فشل رفع الصورة. تحقق من صلاحيات المجلد.';
                $imageName = 'default.png';
            }
        }
    }

    // --- الإدراج في قاعدة البيانات ---
    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO events (title, description, category, location, event_date, image)
             VALUES (:title, :description, :category, :location, :event_date, :image)"
        );
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':category'    => $category,
            ':location'    => $location,
            ':event_date'  => $event_date,
            ':image'       => $imageName,
        ]);

        header('Location: dashboard.php?added=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فعالية - لوحة التحكم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            width: 240px;
            position: fixed;
            top: 0;
            right: 0;
            z-index: 100;
            padding-top: 1rem;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.2rem 0.75rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.15); color: white; }
        .sidebar .brand { padding: 1rem 1.5rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 0.5rem; }
        .main-content { margin-right: 240px; padding: 1.5rem; }
        #imagePreview { max-height: 200px; object-fit: cover; border-radius: 0.5rem; display: none; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-calendar-event-fill text-primary me-2"></i>
            <span class="fw-bold">لوحة التحكم</span>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> الرئيسية
            </a>
            <a class="nav-link active" href="add_event.php">
                <i class="bi bi-plus-circle me-2"></i> إضافة فعالية
            </a>
            <hr class="border-secondary mx-3">
            <a class="nav-link" href="../index.php" target="_blank">
                <i class="bi bi-box-arrow-up-left me-2"></i> عرض الموقع
            </a>
            <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0 fw-bold">إضافة فعالية جديدة</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="dashboard.php">الرئيسية</a></li>
                        <li class="breadcrumb-item active">إضافة فعالية</li>
                    </ol>
                </nav>
            </div>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-right me-1"></i> عودة
            </a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="" enctype="multipart/form-data">

                    <div class="row g-3">
                        <!-- عنوان الفعالية -->
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold">
                                عنوان الفعالية <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   name="title"
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                                   placeholder="مثال: ورشة عمل في البرمجة"
                                   required>
                        </div>

                        <!-- الوصف -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">
                                الوصف <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="اكتب وصفاً تفصيلياً للفعالية..."
                                      required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>

                        <!-- التصنيف -->
                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold">
                                التصنيف <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">-- اختر التصنيف --</option>
                                <?php
                                $cats = ['تقني', 'ثقافي', 'رياضي', 'موسيقي', 'علمي', 'عائلي', 'اجتماعي'];
                                foreach ($cats as $cat):
                                    $sel = (($_POST['category'] ?? '') === $cat) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $cat; ?>" <?php echo $sel; ?>>
                                    <?php echo $cat; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- التاريخ -->
                        <div class="col-md-6">
                            <label for="event_date" class="form-label fw-semibold">
                                تاريخ الفعالية <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control"
                                   id="event_date"
                                   name="event_date"
                                   value="<?php echo htmlspecialchars($_POST['event_date'] ?? ''); ?>"
                                   required>
                        </div>

                        <!-- المكان -->
                        <div class="col-12">
                            <label for="location" class="form-label fw-semibold">
                                مكان الفعالية <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="location"
                                   name="location"
                                   value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>"
                                   placeholder="مثال: المدرج الرئيسي"
                                   required>
                        </div>

                        <!-- الصورة -->
                        <div class="col-12">
                            <label for="image" class="form-label fw-semibold">صورة الفعالية</label>
                            <input type="file"
                                   class="form-control"
                                   id="image"
                                   name="image"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   onchange="previewImage(this)">
                            <div class="form-text">الأنواع المقبولة: JPEG, PNG, GIF, WEBP — الحد الأقصى: 5MB</div>
                            <img id="imagePreview" class="mt-2 img-fluid" alt="معاينة الصورة">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle-fill me-1"></i> إضافة الفعالية
                        </button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>
