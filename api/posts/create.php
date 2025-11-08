<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../includes/functions.php';
require_once '../../includes/Post.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '../../error.log');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method not allowed', [], 405);
}

requireAuth();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title']) || !isset($data['content'])) {
    sendJsonResponse(false, 'Title and content are required', [], 400);
}

// Debug information
error_log("Creating post with data: " . json_encode($data));
error_log("User ID from session: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));

// Debug logging
error_log("POST Data received: " . print_r($data, true));
error_log("Session data: " . print_r($_SESSION, true));

if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'User not authenticated', [], 401);
    exit;
}

$post = new Post();
$result = $post->create(
    $_SESSION['user_id'],
    sanitizeInput($data['title']),
    sanitizeInput($data['content'])
);

sendJsonResponse(
    $result['success'],
    $result['message'],
    $result['data'] ?? [],
    $result['success'] ? 201 : 400
);
?>