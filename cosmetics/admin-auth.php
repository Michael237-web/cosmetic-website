<?php
require_once __DIR__ . '/db.php';
session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}