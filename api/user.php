<?php
/**
 * 获取用户ID（如果不存在则创建）
 */
header('Content-Type: application/json; charset=utf-8');

// 从Cookie获取或创建新的用户ID
$userId = $_COOKIE['safety_user_id'] ?? null;

if (!$userId) {
    $userId = 'u_' . substr(md5(uniqid(mt_rand(), true)), 0, 16);
    setcookie('safety_user_id', $userId, time() + 86400 * 365, '/');
}

echo json_encode([
    'success' => true,
    'user_id' => $userId
]);