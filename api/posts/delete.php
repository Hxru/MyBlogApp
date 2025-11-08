<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../includes/functions.php';
require_once '../../includes/Post.php';

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendJsonResponse(false, 'Method not allowed', [], 405);
}

requireAuth();

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    sendJsonResponse(false, 'Post ID is required', [], 400);
}

$post = new Post();
$result = $post->delete($id, $_SESSION['user_id']);

sendJsonResponse(
    $result['success'],
    $result['message'],
    $result['data'] ?? [],
    $result['success'] ? 200 : ($result['message'] === 'Unauthorized' ? 403 : 400)
);
?>