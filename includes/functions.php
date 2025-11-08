<?php
/**
 * Common utility functions
 */

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

function sendJsonResponse($success, $message, $data = [], $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

function isAuthenticated() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        if (isApiRequest()) {
            sendJsonResponse(false, 'Authentication required', [], 401);
        } else {
            header('Location: login.php');
            exit;
        }
    }
}

function isApiRequest() {
    return isset($_SERVER['HTTP_ACCEPT']) && 
           strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
}

function formatDate($dateString) {
    return date('F j, Y', strtotime($dateString));
}

function generateExcerpt($content, $length = 150) {
    $content = strip_tags($content);
    if (strlen($content) > $length) {
        $content = substr($content, 0, $length) . '...';
    }
    return $content;
}
?>