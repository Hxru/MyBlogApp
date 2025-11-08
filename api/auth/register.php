<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../includes/functions.php';
require_once '../../includes/Auth.php';

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method not allowed', [], 405);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
    sendJsonResponse(false, 'All fields are required', [], 400);
}

$auth = new Auth();
$result = $auth->register(
    sanitizeInput($data['username']),
    sanitizeInput($data['email']),
    $data['password']
);

if ($result['success']) {
    // Auto-login after registration
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $result['data']['id'];
    $_SESSION['username'] = $result['data']['username'];
    $_SESSION['email'] = $result['data']['email'];
}

sendJsonResponse(
    $result['success'],
    $result['message'],
    $result['data'] ?? [],
    $result['success'] ? 201 : 400
);
?>