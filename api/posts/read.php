<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

require_once '../../includes/functions.php';
require_once '../../includes/Post.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(false, 'Method not allowed', [], 405);
}

$post = new Post();

// Get single post
if (isset($_GET['id'])) {
    $result = $post->getPost($_GET['id']);
    sendJsonResponse(
        $result['success'],
        $result['message'] ?? '',
        $result['data'] ?? [],
        $result['success'] ? 200 : 404
    );
}

// Get posts with pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? min(max(1, (int)$_GET['limit']), 50) : 10;

$result = $post->getAllPosts($page, $limit);
sendJsonResponse(
    $result['success'],
    $result['message'] ?? '',
    $result['data'] ?? [],
    $result['success'] ? 200 : 400
);
?>