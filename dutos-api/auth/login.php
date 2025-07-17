<?php
require_once("../config/bootstrap.php");

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if (!$username || !$password) {
    log_auth_event("Опит за вход с липсващи данни (username: '$username')");
    json_error("Потребителско име и парола са задължителни", 400);
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    log_auth_event("Грешни данни за вход: '$username'");
    json_error("Невалидни данни за вход", 401);
}

$_SESSION['user'] = [
    "id"       => $user['id'],
    "name"     => $user['name'],
    "username" => $user['username'],
    "rank"     => $user['rank'],
    "role"     => $user['role'],
    "crew_id"  => $user['crew_id']
];

$redirect = match ($user['role']) {
    'admin'   => '/admin/dashboard.php',
    'officer' => '/officer/overview.php',
    default   => '/user/home.php'
};

log_auth_event("Успешен вход: ID #{$user['id']} | '{$username}' ({$user['rank']}) | Роля: '{$user['role']}' | Екипаж: #{$user['crew_id']} | Пренасочен към: $redirect");

json_success("Успешен вход", ["redirect" => $redirect]);
