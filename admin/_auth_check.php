<?php
/**
 * _auth_check.php
 * يُضمَّن في بداية كل صفحة إدارية لضمان أن المستخدم مسجل دخوله.
 * الاستخدام: require_once '_auth_check.php';
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
