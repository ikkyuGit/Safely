<?php
/**
 * 安管人员考试学习系统 - 配置文件
 */

// 进度文件路径（JSON 文件存储，无需数据库扩展）
define('PROGRESS_FILE', __DIR__ . '/../data/progress.json');

// 题库数据路径
define('QUESTIONS_FILE', __DIR__ . '/../data/questions.json');

// 默认用户 ID（浏览器首次访问时生成）
define('DEFAULT_USER_ID', 'default_user');

// API 响应格式
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 允许预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
