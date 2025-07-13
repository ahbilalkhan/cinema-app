<?php
require_once 'cors.php';
require_once '../db.php';

try {
    $stmt = $pdo->query('SELECT * FROM movies ORDER BY created_at DESC');
    $movies = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $movies]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 