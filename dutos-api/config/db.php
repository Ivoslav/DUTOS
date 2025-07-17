<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db = 'DUTOS';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Включваш логера веднъж
require_once(__DIR__ . '/../utils/logger.php');

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    log_db_event("Успешна връзка с базата '$db'");
} catch (PDOException $e) {
    log_db_event("Грешка при връзка с базата: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Wrapper функция за логване на заявки
function pdo_query(PDO $pdo, string $sql, array $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
        $script = $_SERVER['REQUEST_URI'] ?? 'неизвестен скрипт';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'неизвестен IP';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'неизвестен агент';

        $paramStr = json_encode($params);

        $message = "SQL: \"$sql\" | Params: $paramStr | Caller: {$caller['file']} @ line {$caller['line']}";
        log_db_event($message);

        return $stmt;
    } catch (PDOException $e) {
        $paramStr = json_encode($params);
        log_db_event("SQL грешка при \"$sql\" с параметри $paramStr: " . $e->getMessage());
        throw $e;
    }
}
