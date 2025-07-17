<?php
function json_success($data = [], $message = "") {
    echo json_encode(["success" => true, "message" => $message, "data" => $data]);
    exit;
}

function json_error($message, $code = 400) {
    http_response_code($code);
    echo json_encode(["success" => false, "error" => $message]);
    exit;
}
