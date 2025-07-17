<?php
session_start();
header("Content-Type: application/json");
require_once("../config/db.php");

function log_auth_event($message) {
    $log_file = __DIR__ . '/../logs/auth.log';
    $timestamp = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'неизвестен IP';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'неизвестен агент';
    $script = $_SERVER['REQUEST_URI'] ?? 'неизвестен скрипт';
    file_put_contents($log_file, "[$timestamp] [$ip] [$script] $message | Agent: $agent\n", FILE_APPEND);
}

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

$_SESSION['user'] = [
    "id" => $pdo->lastInsertId(),
    "name" => $name,
    "username" => $username,
    "rank" => $rank,
    "role" => $role,
    "crew_id" => $crew_id
];

$redirect = match ($role) {
    'admin'   => '/admin/dashboard.php',
    'officer' => '/officer/overview.php',
    default   => '/user/home.php'
};

log_auth_event("Успешна регистрация и вход за '$username'($rank) с роля '$role', екипаж #$crew_id (пренасочен към $redirect)");

echo json_encode([
    "success" => true,
    "message" => "Регистрацията е успешна.",
    "redirect" => $redirect
]);

?>
