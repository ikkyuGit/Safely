<?php
/**
 * 进度 API 接口 - JSON文件版（无需数据库）
 */

require_once __DIR__ . '/config.php';

// 获取用户ID
$userId = $_POST['user_id'] ?? $_GET['user_id'] ?? $_COOKIE['safety_user_id'] ?? null;

if (!$userId) {
    $userId = 'u_' . substr(md5(uniqid(mt_rand(), true)), 0, 16);
    setcookie('safety_user_id', $userId, time() + 86400 * 365, '/');
}

// 进度文件路径
$progressFile = PROGRESS_FILE;

// 确保文件存在
if (!file_exists($progressFile)) {
    $fp = fopen($progressFile, 'c');
    if ($fp) {
        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}

// 读取所有进度（带文件锁）
function readProgress($file) {
    $fp = fopen($file, 'r');
    if (!$fp) return [];
    flock($fp, LOCK_SH);
    $content = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    if (!$content) return [];
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

// 保存所有进度（带排他锁）
function saveProgress($file, $data) {
    $fp = fopen($file, 'c');
    if (!$fp) return false;
    flock($fp, LOCK_EX);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $result = ftruncate($fp, 0) && rewind($fp) && fwrite($fp, $json) !== false;
    flock($fp, LOCK_UN);
    fclose($fp);
    clearstatcache(true, $file);
    if (function_exists('opcache_invalidate')) opcache_invalidate($file, true);
    return $result;
}

// 刷新读取（避免OPcache）
function readProgressFresh($file) {
    clearstatcache(true, $file);
    if (function_exists('opcache_invalidate')) opcache_invalidate($file, true);
    return readProgress($file);
}

// 获取操作类型
$action = $_POST['action'] ?? $_GET['action'] ?? 'get';

try {
    $allProgress = readProgressFresh($progressFile);
    
    // 确保用户记录存在
    if (!isset($allProgress[$userId])) {
        $allProgress[$userId] = [
            'study' => null,
            'exam' => null,
            'voice' => null,
            'wrongbook' => null
        ];
    }
    
    switch ($action) {
        case 'get':
            $type = $_GET['type'] ?? $_POST['type'] ?? 'study';
            $data = $allProgress[$userId][$type] ?? null;
            echo json_encode([
                'success' => true,
                'user_id' => $userId,
                'type' => $type,
                'data' => $data
            ]);
            break;
            
        case 'getAll':
            echo json_encode([
                'success' => true,
                'user_id' => $userId,
                'data' => $allProgress[$userId]
            ]);
            break;
            
        case 'save':
            $type = $_POST['type'] ?? '';
            $data = $_POST['data'] ?? null;
            
            if (!$type) {
                throw new Exception('缺少type参数');
            }
            
            if (is_string($data)) {
                $data = json_decode($data, true);
            }
            
            if ($data === null) {
                throw new Exception('无效的data参数');
            }
            
            $allProgress[$userId][$type] = $data;
            
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            
            echo json_encode([
                'success' => true,
                'message' => '保存成功'
            ]);
            break;
            
        case 'recordAnswer':
            // 简化版：记录答题（存入exam进度）
            $type = $_POST['type'] ?? 'exam';
            $questionId = $_POST['question_id'] ?? '';
            $answer = $_POST['answer'] ?? null;
            $isCorrect = isset($_POST['is_correct']) ? (bool)$_POST['is_correct'] : null;
            
            if (!isset($allProgress[$userId][$type])) {
                $allProgress[$userId][$type] = ['answers' => []];
            }
            if (!isset($allProgress[$userId][$type]['answers'])) {
                $allProgress[$userId][$type]['answers'] = [];
            }
            
            $allProgress[$userId][$type]['answers'][$questionId] = [
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'time' => date('Y-m-d H:i:s')
            ];
            
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            
            echo json_encode([
                'success' => true,
                'message' => '记录成功'
            ]);
            break;
            
        case 'clear':
            $type = $_POST['type'] ?? '';
            if ($type && isset($allProgress[$userId][$type])) {
                $allProgress[$userId][$type] = null;
            }
            
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            
            echo json_encode([
                'success' => true,
                'message' => '清除成功'
            ]);
            break;
            
        case 'addWrong':
            $cert = $_POST['cert'] ?? '';
            $questionNum = $_POST['question_num'] ?? null;
            if ($questionNum === null) {
                throw new Exception('缺少question_num参数');
            }
            if (!isset($allProgress[$userId]['wrongbook'])) {
                $allProgress[$userId]['wrongbook'] = [];
            }
            if (!isset($allProgress[$userId]['wrongbook'][$cert])) {
                $allProgress[$userId]['wrongbook'][$cert] = [];
            }
            $allProgress[$userId]['wrongbook'][$cert][$questionNum] = [
                'added_at' => date('Y-m-d H:i:s'),
                'mastered' => false,
                'correct_count' => 0
            ];
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            echo json_encode(['success' => true, 'message' => '已加入错题本']);
            break;
            
        case 'removeWrong':
            $cert = $_POST['cert'] ?? '';
            $questionNum = $_POST['question_num'] ?? null;
            if ($questionNum === null) {
                throw new Exception('缺少question_num参数');
            }
            if (isset($allProgress[$userId]['wrongbook'][$cert][$questionNum])) {
                unset($allProgress[$userId]['wrongbook'][$cert][$questionNum]);
            }
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            echo json_encode(['success' => true, 'message' => '已从错题本移除']);
            break;
            
        case 'markMastered':
            $cert = $_POST['cert'] ?? '';
            $questionNum = $_POST['question_num'] ?? null;
            $mastered = isset($_POST['mastered']) ? (bool)$_POST['mastered'] : true;
            if ($questionNum === null) {
                throw new Exception('缺少question_num参数');
            }
            if (isset($allProgress[$userId]['wrongbook'][$cert][$questionNum])) {
                $allProgress[$userId]['wrongbook'][$cert][$questionNum]['mastered'] = $mastered;
                $allProgress[$userId]['wrongbook'][$cert][$questionNum]['correct_count'] = 
                    ($allProgress[$userId]['wrongbook'][$cert][$questionNum]['correct_count'] ?? 0) + 1;
            }
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            echo json_encode(['success' => true, 'message' => '已标记为掌握']);
            break;
            
        case 'getWrongbook':
            $cert = $_GET['cert'] ?? null;
            $wrongbook = $allProgress[$userId]['wrongbook'] ?? [];
            if ($cert) {
                $wrongbook = $wrongbook[$cert] ?? [];
            }
            echo json_encode([
                'success' => true,
                'data' => $wrongbook
            ]);
            break;
            
        case 'clearWrongbook':
            $cert = $_POST['cert'] ?? null;
            if ($cert) {
                $allProgress[$userId]['wrongbook'][$cert] = [];
            } else {
                $allProgress[$userId]['wrongbook'] = [];
            }
            if (!saveProgress($progressFile, $allProgress)) {
                throw new Exception('保存失败');
            }
            echo json_encode(['success' => true, 'message' => '错题本已清空']);
            break;
            
        case 'stats':
            $stats = ['total' => 0];
            foreach (['study', 'exam', 'voice'] as $t) {
                if (isset($allProgress[$userId][$t]) && $allProgress[$userId][$t]) {
                    $stats[$t . '_rounds'] = isset($allProgress[$userId][$t]['currentRound']) 
                        ? $allProgress[$userId][$t]['currentRound'] - 1 : 0;
                    if ($t === 'exam') {
                        $answers = $allProgress[$userId][$t]['answers'] ?? [];
                        $correct = array_filter($answers, fn($a) => $a['is_correct'] ?? false);
                        $stats['total'] = count($answers);
                        $stats['correct'] = count($correct);
                    }
                }
            }
            echo json_encode([
                'success' => true,
                'user_id' => $userId,
                'stats' => $stats
            ]);
            break;
            
        default:
            throw new Exception('未知操作: ' . $action);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}