<?php

function require_any_role(array $roles) {
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles)) {
        http_response_code(403);
        echo json_encode(['error' => 'Нямате достъп']);
        exit;
    }
}
