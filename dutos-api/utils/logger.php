<?php

function log_auth_event($message) {
    $log_file = __DIR__ . '/../logs/auth.log';
    log_event($log_file, $message);
}

function log_db_event($message) {
    $log_file = __DIR__ . '/../logs/db.log';
    log_event($log_file, $message);
}

function log_event($log_file, $message) {
    $timestamp = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'неизвестен IP';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'неизвестен агент';
    $script = $_SERVER['REQUEST_URI'] ?? 'неизвестен скрипт';

    file_put_contents(
        $log_file,
        "[$timestamp] [$ip] [$script] $message | Agent: $agent\n",
        FILE_APPEND
    );
}
