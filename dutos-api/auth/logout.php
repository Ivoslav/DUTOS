<?php
session_start();
require_once('../config/db.php');

function log_auth_event($message) {
    $log_file = __DIR__ . '/../logs/auth.log';
    $timestamp = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'неизвестен IP';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'неизвестен агент';
    $script = $_SERVER['REQUEST_URI'] ?? 'неизвестен скрипт';

    file_put_contents($log_file, "[$timestamp] [$ip] [$script] $message | Agent: $agent\n", FILE_APPEND);
}

// Вземаме данните от сесията (ако има)
$user_id = $_SESSION['user']['id'] ?? 'непознат ID';
$username = $_SESSION['user']['username'] ?? 'Гост';

log_auth_event("Потребителят '$username' (ID: $user_id) се изписа (logout) от системата.");

// Изчистване на сесията
$_SESSION = [];
session_destroy();

header("Location: /index.html"); // Или към login
exit;
