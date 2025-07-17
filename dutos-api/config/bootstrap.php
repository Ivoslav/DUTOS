<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/../utils/logger.php');
require_once(__DIR__ . '/../utils/response.php');
