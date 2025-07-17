<?php
session_start();
require_once('../config/db.php');
require_once('../utils/logger.php'); // ← централизираният логер

// Вземаме данните от сесията
$user_id = $_SESSION['user']['id'] ?? 'непознат ID';
$username = $_SESSION['user']['username'] ?? 'Гост';

// Логваме събитието
log_auth_event("Потребителят '$username' (ID: $user_id) се изписа (logout) от системата.");

// Изчистване на сесията
$_SESSION = [];
session_destroy();

// Пренасочване
header("Location: /index.html"); // или login page
exit;
