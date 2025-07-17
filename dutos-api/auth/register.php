<?php
session_start();
header("Content-Type: application/json");

require_once("../config/db.php");
require_once("../utils/logger.php"); // ← централизираният логър

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
$name     = trim($data['name'] ?? '');
$rank     = trim($data['rank'] ?? '');
$role     = trim($data['role'] ?? 'user');
$crew_id  = $data['crew_id'] ?? null;

if (!$username || !$password || !$name || !$rank) {
    log_auth_event("Невалидна регистрация (липсващо поле)");
    http_response_code(400);
    echo json_encode(["error" => "Всички основни полета са задължителни."]);
    exit;
}

// Проверка за съществуващ потребител
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);

if ($stmt->fetch()) {
    log_auth_event("Опит за регистрация с вече съществуващо име '$username'");
    http_response_code(409);
    echo json_encode(["error" => "Потребителското име вече съществува."]);
    exit;
}

// Хеширане и запис
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, password, name, rank, role, crew_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$username, $hashed, $name, $rank, $role, $crew_id]);

$user_id = $pdo->lastInsertId();

$_SESSION['user'] = [
    "id"       => $user_id,
    "name"     => $name,
    "username" => $username,
    "rank"     => $rank,
    "role"     => $role,
    "crew_id"  => $crew_id
];

$redirect = match ($role) {
    'admin'   => '/admin/dashboard.php',
    'officer' => '/officer/overview.php',
    default   => '/user/home.php'
};

log_auth_event("Успешна регистрация и вход за '$username' (ID: $user_id, $rank), роля '$role', екипаж #$crew_id → $redirect");

echo json_encode([
    "success" => true,
    "message" => "Регистрацията е успешна.",
    "redirect" => $redirect
]);
