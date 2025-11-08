<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../includes/functions.php';
require_once '../../includes/Post.php';

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendJsonResponse(false, 'Method not allowed', [], 405);
}

requireAuth();

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
    sendJsonResponse(false, 'All fields are required', [], 400);
}

$post = new Post();
$result = $post->update(
    $data['id'],
    $_SESSION['user_id'],
    sanitizeInput($data['title']),
    sanitizeInput($data['content'])
);

sendJsonResponse(
    $result['success'],
    $result['message'],
    $result['data'] ?? [],
    $result['success'] ? 200 : ($result['message'] === 'Unauthorized' ? 403 : 400)
);
?>