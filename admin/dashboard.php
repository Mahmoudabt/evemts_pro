<?php
require_once '_auth_check.php';
require_once '../db.php';

// جلب جميع الفعاليات مرتبة بالأحدث
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();

// إحصائيات سريعة
$totalStmt      = $pdo->query("SELECT COUNT(*) FROM events");
$totalEvents    = $totalStmt->fetchColumn();

$upcomingStmt   = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date >= CURDATE()");
$upcomingEvents = $upcomingStmt->fetchColumn();

$catsStmt       = $pdo->query("SELECT COUNT(DISTINCT category) FROM events");
$totalCategories = $catsStmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - إدارة الفعاليات</title>
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
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .sidebar .brand {
            padding: 1rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 0.5rem;
        }
        .main-content {
            margin-right: 240px;
            padding: 1.5rem;
        }
        .stat-card {
            border: none;
            border-radius: 0.75rem;
            border-left: 4px solid;
        }
        .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); }
        .topbar {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-calendar-event-fill text-primary me-2"></i>
            <span class="fw-bold">لوحة التحكم</span>
            <br>
            <small class="text-white-50"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i> الرئيسية
            </a>
            <a class="nav-link" href="add_event.php">
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

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h5 class="mb-0 fw-bold">إدارة الفعاليات</h5>
                <small class="text-muted">مرحباً، <?php echo htmlspecialchars($_SESSION['admin_username']); ?></small>
            </div>
            <a href="add_event.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> فعالية جديدة
            </a>
        </div>


        <!-- Flash Messages -->
        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> تمت إضافة الفعالية بنجاح.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (isset($_GET['updated'])): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <i class="bi bi-pencil-fill me-2"></i> تم تعديل الفعالية بنجاح.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (isset($_GET['deleted'])): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="bi bi-trash-fill me-2"></i> تم حذف الفعالية بنجاح.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- إحصائيات -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 shadow-sm" style="border-left-color:#0d6efd">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">إجمالي الفعاليات</div>
                            <div class="fs-2 fw-bold text-primary"><?php echo $totalEvents; ?></div>
                        </div>
                        <i class="bi bi-calendar-event fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 shadow-sm" style="border-left-color:#198754">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">الفعاليات القادمة</div>
                            <div class="fs-2 fw-bold text-success"><?php echo $upcomingEvents; ?></div>
                        </div>
                        <i class="bi bi-calendar-check fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3 shadow-sm" style="border-left-color:#fd7e14">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">التصنيفات</div>
                            <div class="fs-2 fw-bold text-warning"><?php echo $totalCategories; ?></div>
                        </div>
                        <i class="bi bi-tags fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول الفعاليات -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2 text-primary"></i> قائمة الفعاليات
                </h6>
                <span class="badge bg-secondary"><?php echo $totalEvents; ?> فعالية</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($events)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        لا توجد فعاليات بعد.
                        <a href="add_event.php" class="btn btn-primary btn-sm mt-2 d-block mx-auto" style="width:fit-content">
                            إضافة أول فعالية
                        </a>
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>التصنيف</th>
                                <th>التاريخ</th>
                                <th>المكان</th>
                                <th>الصورة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                            <tr>
                                <td class="text-muted small"><?php echo $event['id']; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($event['title']); ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars($event['category']); ?>
                                    </span>
                                </td>
                                <td class="small">
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    <?php echo date('Y/m/d', strtotime($event['event_date'])); ?>
                                </td>
                                <td class="small text-muted"><?php echo htmlspecialchars($event['location']); ?></td>
                                <td>
                                    <img src="../assets/img/<?php echo htmlspecialchars($event['image']); ?>"
                                         alt="صورة"
                                         width="50" height="40"
                                         style="object-fit:cover;border-radius:6px;"
                                         onerror="this.src='../assets/img/default.png'">
                                </td>
                                <td class="text-center">
                                    <a href="../event.php?id=<?php echo $event['id']; ?>"
                                       class="btn btn-sm btn-outline-secondary me-1"
                                       target="_blank"
                                       title="معاينة">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit_event.php?id=<?php echo $event['id']; ?>"
                                       class="btn btn-sm btn-outline-primary me-1"
                                       title="تعديل">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form method="POST" action="delete_event.php" class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الفعالية؟')">
                                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
