<?php
// export.php - 数据导出功能
session_start();

require_once __DIR__ . '/api/config.php';

// 获取用户ID
$userId = $_GET['user_id'] ?? $_COOKIE['user_id'] ?? null;
if (!$userId) {
    die('请先开始学习后再导出数据');
}

// 读取进度数据
$progressFile = PROGRESS_FILE;
$allProgress = [];
if (file_exists($progressFile)) {
    $content = file_get_contents($progressFile);
    $allProgress = json_decode($content, true) ?: [];
}
$userData = $allProgress[$userId] ?? [];

// 获取导出格式
$format = $_GET['format'] ?? 'json';

if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="学习进度_' . $userId . '_' . date('Ymd') . '.json"');
    echo json_encode([
        'export_time' => date('Y-m-d H:i:s'),
        'user_id' => $userId,
        'data' => $userData
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="学习进度_' . $userId . '_' . date('Ymd') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel
    
    // 写入表头
    fputcsv($output, ['类型', '轮次', '已完成题数', '当前题号', '记录时间']);
    
    // 智能学习数据
    if (isset($userData['study'])) {
        $study = $userData['study'];
        fputcsv($output, [
            '智能学习',
            $study['currentRound'] ?? 1,
            count($study['completed'] ?? []),
            ($study['currentIndex'] ?? 0) + 1,
            $study['lastStudyTime'] ?? '-'
        ]);
    }
    
    // 语音朗读数据
    if (isset($userData['voice'])) {
        $voice = $userData['voice'];
        fputcsv($output, [
            '语音朗读',
            $voice['round'] ?? 1,
            count($voice['completed'] ?? []),
            ($voice['currentIndex'] ?? 0) + 1,
            $voice['lastVoiceTime'] ?? '-'
        ]);
    }
    
    // 模拟考试数据
    if (isset($userData['exam'])) {
        $exam = $userData['exam'];
        fputcsv($output, [
            '模拟考试',
            '-',
            $exam['lastCorrect'] ?? 0,
            $exam['lastScore'] ?? 0,
            $exam['lastDate'] ?? '-'
        ]);
    }
    
    fclose($output);
    exit;
}

// HTML 导出页面
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>导出学习数据 - 安管人员考试</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 600px; margin: 0 auto; }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: 10px; }
        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .card h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        .export-btn {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 15px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .export-btn:hover {
            background: #e8eaf6;
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .export-icon {
            font-size: 2rem;
        }
        .export-info h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        .export-info p {
            font-size: 0.85rem;
            color: #666;
        }
        .back-btn {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: background 0.2s;
        }
        .back-btn:hover { background: rgba(255,255,255,0.3); }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-num {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📥 导出学习数据</h1>
            <p>备份你的学习进度</p>
        </div>
        
        <div class="card">
            <h2>📊 学习统计</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-num"><?php echo isset($userData['study']) ? count($userData['study']['completed'] ?? []) : 0; ?></div>
                    <div class="stat-label">智能学习完成</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num"><?php echo isset($userData['voice']) ? count($userData['voice']['completed'] ?? []) : 0; ?></div>
                    <div class="stat-label">语音朗读完成</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num"><?php echo isset($userData['exam']) ? ($userData['exam']['lastScore'] ?? 0) : 0; ?></div>
                    <div class="stat-label">最近考试分数</div>
                </div>
                <div class="stat-box">
                    <?php
                    $wrongCount = 0;
                    if (isset($userData['wrongbook'])) {
                        foreach ($userData['wrongbook'] as $cert => $questions) {
                            $wrongCount += count($questions);
                        }
                    }
                    ?>
                    <div class="stat-num"><?php echo $wrongCount; ?></div>
                    <div class="stat-label">错题本题目</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>💾 选择导出格式</h2>
            <a href="?format=json&user_id=<?php echo htmlspecialchars($userId); ?>" class="export-btn">
                <span class="export-icon">📋</span>
                <div class="export-info">
                    <h3>导出为 JSON</h3>
                    <p>包含完整的学习数据，可用于备份或迁移</p>
                </div>
            </a>
            <a href="?format=csv&user_id=<?php echo htmlspecialchars($userId); ?>" class="export-btn">
                <span class="export-icon">📊</span>
                <div class="export-info">
                    <h3>导出为 CSV</h3>
                    <p>Excel 兼容格式，方便查看和分享</p>
                </div>
            </a>
        </div>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-btn">← 返回首页</a>
        </div>
    </div>
</body>
</html>
