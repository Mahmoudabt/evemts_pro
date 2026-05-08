<?php
require_once '_auth_check.php';
require_once '../db.php';

// الحذف يجب أن يأتي عبر POST فقط — ليس GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: dashboard.php');
    exit;
}

// جلب الفعالية للتحقق من وجودها وأخذ اسم الصورة
$stmt = $pdo->prepare("SELECT image FROM events WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: dashboard.php');
    exit;
}

// حذف الصورة من الخادم إن لم تكن الافتراضية
if ($event['image'] !== 'default.png') {
    $imagePath = '../assets/img/' . $event['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// حذف السجل
$stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: dashboard.php?deleted=1');
exit;
