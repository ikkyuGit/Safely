<?php
/**
 * 获取题目数据 API
 */

header('Content-Type: application/json; charset=utf-8');

$type = $_GET['type'] ?? 'all';

// 数据文件路径
$dataFile = __DIR__ . '/../data/questions.json';

if (!file_exists($dataFile)) {
    echo json_encode(['success' => false, 'error' => '数据文件不存在']);
    exit;
}

$questions = json_decode(file_get_contents($dataFile), true);

if ($questions === null) {
    echo json_encode(['success' => false, 'error' => '数据解析失败']);
    exit;
}

// 按类型筛选
if ($type !== 'all') {
    $questions = array_filter($questions, function($q) use ($type) {
        return $q['type'] === $type;
    });
    $questions = array_values($questions); // 重排索引
}

echo json_encode([
    'success' => true,
    'count' => count($questions),
    'data' => $questions
]);
