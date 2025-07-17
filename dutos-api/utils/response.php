<?php
function json_success($message, $data = []) {
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

function json_error($message, $code = 400) {
    http_response_code($code);
    echo json_encode([
        "success" => false,
        "message" => $message
    ]);
    exit;
}
