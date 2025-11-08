<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../includes/functions.php';
require_once '../../includes/Post.php';

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