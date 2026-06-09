<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/database.php';
header('Location: ' . BASE_URL . '/controllers/AuthController.php?action=login');
exit;
