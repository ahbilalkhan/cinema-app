<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    $stmt = $pdo->query('SELECT id, username, email, created_at FROM users ORDER BY created_at DESC');
    $users = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 