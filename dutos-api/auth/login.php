<?php
session_start();
header("Content-Type: application/json");
require_once("../config/db.php");
require_once("../utils/logger.php"); // ← Добавен

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (!$username || !$password) {
    log_auth_event("Опит за вход с липсващи данни (username: '$username')");
    http_response_code(400);
    echo json_encode(["error" => "Username and password are required"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    log_auth_event("Грешни данни за вход: '$username'");
    http_response_code(401);
    echo json_encode(["error" => "Invalid credentials"]);
    exit;
}

// Старт на сесия
$_SESSION['user'] = [
    "id"       => $user['id'],
    "name"     => $user['name'],
    "username" => $user['username'],
    "rank"     => $user['rank'],
    "role"     => $user['role'],
    "crew_id"  => $user['crew_id']
];

// Пренасочване по роля
$redirect = match ($user['role']) {
    'admin'   => '/admin/dashboard.php',
    'officer' => '/officer/overview.php',
    default   => '/user/home.php'
};

log_auth_event("Успешен вход: ID #{$user['id']} | '{$username}' ({$user['rank']}) | Роля: '{$user['role']}' | Екипаж: #{$user['crew_id']} | Пренасочен към: $redirect");

echo json_encode([
    "success" => true,
    "message" => "Успешен вход",
    "redirect" => $redirect
]);