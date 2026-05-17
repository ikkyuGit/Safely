<?php
// voice.php - 语音朗读学习 (iOS Safari 后台自动暂停版)
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>AI语音学习 - 安管人员考试</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
    <!-- iOS 防休眠核心 meta 标签 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#667eea">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 15px;
            /* iOS 防触摸休眠 */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            /* 防止 iOS 休眠 */
            -webkit-text-size-adjust: 100%;
            /* 保持屏幕常亮提示 */
            touch-action: manipulation;
        }
        .container { max-width: 800px; margin: 0 auto; }
        .header {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }
        .back-btn {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 8px 14px;
            background: #f0f0f0;
            color: #555;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .back-btn:hover { background: #e0e0e0; color: #333; }
        h1 { color: #333; font-size: 28px; margin-bottom: 10px; }
        .subtitle { color: #666; font-size: 14px; }
        .round-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .round-badge {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 18px;
        }
        .round-stats { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 24px; font-weight: bold; }
        .stat-label { font-size: 12px; opacity: 0.9; }
        .progress-section {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .progress-bar {
            background: #e0e0e0;
            border-radius: 10px;
            height: 30px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .progress-fill {
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            height: 100%;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
        }
        .progress-text { text-align: center; color: #666; font-size: 14px; }
        .controls {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .control-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .control-row:last-child { margin-bottom: 0; }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-primary:hover { transform: translateY(-2px); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-secondary { background: #f0f0f0; color: #333; }
        .btn-secondary:hover { background: #e0e0e0; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
        .filter-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
        }
        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #667eea;
            background: #fff;
            color: #667eea;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }
        .filter-btn.active { background: #667eea; color: #fff; }
        .voice-selector {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        .voice-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }
        .voice-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .voice-option {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            background: #fff;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .voice-option:hover { border-color: #667eea; }
        .voice-option.selected {
            border-color: #667eea;
            background: #e3f2fd;
            font-weight: bold;
        }
        .voice-icon { font-size: 20px; }
        .voice-name { font-size: 13px; }
        .speed-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .speed-label { font-weight: bold; color: #333; }
        .speed-slider {
            width: 150px;
            height: 8px;
            -webkit-appearance: none;
            background: #e0e0e0;
            border-radius: 4px;
            outline: none;
        }
        .speed-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: #667eea;
            border-radius: 50%;
            cursor: pointer;
        }
        .speed-value { color: #667eea; font-weight: bold; min-width: 40px; }
        .question-display {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .q-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .q-num { font-size: 24px; font-weight: bold; color: #667eea; }
        .q-type {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        .q-text {
            font-size: 20px;
            line-height: 1.8;
            color: #333;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 4px solid #667eea;
        }
        .q-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 25px;
        }
        .q-option {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            background: #fafafa;
        }
        .q-option.correct {
            border-color: #4CAF50;
            background: #e8f5e9;
            font-weight: bold;
        }
        .q-answer {
            text-align: center;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 12px;
            font-size: 22px;
            font-weight: bold;
            color: #1976D2;
        }
        .q-analysis {
            padding: 20px 24px;
            background: #fffdf5;
            border: 2px solid #FFE082;
            border-left: 5px solid #FF9800;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(255,152,0,0.12);
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .q-analysis-title {
            font-weight: bold;
            color: #E65100;
            margin-bottom: 12px;
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #FFE0B2;
        }
        .nav-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .keyboard-hint {
            background: rgba(255,255,255,0.15);
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 13px;
            color: rgba(255,255,255,0.9);
            text-align: center;
        }
        .keyboard-hint kbd {
            background: rgba(255,255,255,0.25);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            margin: 0 3px;
        }
        .status {
            text-align: center;
            padding: 15px;
            background: #fff3cd;
            border-radius: 12px;
            margin-top: 20px;
            color: #856404;
            font-weight: bold;
        }
        /* 播放状态指示器 */
        .playing-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .playing-indicator .dot {
            width: 8px;
            height: 8px;
            background: #4CAF50;
            border-radius: 50%;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        /* 朗读高亮 */
        .q-text.speaking, .q-option.speaking, .q-answer.speaking, .q-analysis.speaking {
            background: #fff3cd !important;
            border-color: #FF9800 !important;
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.35);
        }
        .q-answer.speaking {
            background: #fff3cd !important;
            color: #E65100 !important;
        }
        .q-analysis.speaking {
            border-color: #FF9800 !important;
        }
        .q-analysis-line {
            padding: 6px 0;
            line-height: 1.8;
            border-left: 3px solid transparent;
            padding-left: 10px;
            margin: 2px 0;
            border-radius: 4px;
        }
        .q-analysis-line.speaking {
            background: #fff3cd !important;
            border-left-color: #FF9800;
            font-weight: 600;
        }
        .tips-block {
            margin-top: 12px;
        }
        .tips-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .tips-content {
            background: #fff5f5;
            border-left: 4px solid #f5576c;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 15px;
            line-height: 1.8;
            color: #333;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            h1 { font-size: 22px; }
            .q-text { font-size: 18px; }
            .btn { padding: 10px 20px; font-size: 13px; }
        }
    
/* 用户名显示样式 */
.user-id {
    display: flex;
    justify-content: flex-end;
    padding: 8px 16px;
    background: rgba(255,255,255,0.9);
    border-radius: 8px;
    margin-bottom: 10px;
    font-size: 14px;
    color: #666;
}
.user-display {
    display: flex;
    align-items: center;
    gap: 8px;
}
.user-input {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    width: 150px;
}
.btn-edit {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 14px;
    opacity: 0.6;
    transition: opacity 0.2s;
}
.btn-edit:hover { opacity: 1; }
.btn-save {
    padding: 4px 12px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}
.btn-cancel {
    padding: 4px 12px;
    background: #f44336;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

</style>
</head>
<body>
    <!-- iOS 原生支持的保活机制：WakeLock + Silent AudioContext -->


    <div class="container">
        <div class="header" style="position: relative;">
            <a href="index.php" class="back-btn">← 返回</a>
            <h1>🎙️ AI语音学习</h1>
            <p class="subtitle">自然流畅的AI配音</p>

        </div>


        <div class="round-info">
            <div class="round-badge" id="roundBadge">第 1 轮学习</div>
            <div class="round-stats">
                <div class="stat-item">
                    <div class="stat-num" id="completedCount">0</div>
                    <div class="stat-label">已完成</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num" id="remainCount">0</div>
                    <div class="stat-label">剩余</div>
                </div>
            </div>
        </div>

        <div class="progress-section">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 0%">0%</div>
            </div>
            <div class="progress-text">
                进度：<strong id="currentIndex">0</strong> / <strong id="totalCount">0</strong> 题
            </div>
        </div>

        <div class="controls">
            <div class="control-row">
                <button class="btn btn-secondary" onclick="finishRound()">✅ 完成本轮</button>
                <button class="btn btn-danger" onclick="resetProgress()">🔄 重置</button>
            </div>
        </div>

        <div class="controls">
            <div class="voice-selector">
                <span class="voice-label">🎙️ 选择AI配音声音：</span>
                <div class="voice-options" id="voiceOptions"></div>
            </div>
            <div class="control-row">
                <div class="speed-control">
                    <span class="speed-label">朗读速度：</span>
                    <input type="range" class="speed-slider" id="speedSlider" min="0.5" max="2" step="0.1" value="1.2">
                    <span class="speed-value" id="speedValue">1.2x</span>
                </div>
            </div>
            <div class="control-row">
                <button class="btn btn-primary" id="startBtn" onclick="startLearning()">
                    <span>▶️</span> 开始朗读
                </button>
                <button class="btn btn-secondary" id="pauseBtn" onclick="pauseLearning()" disabled>
                    <span>⏸️</span> 暂停
                </button>
                <button class="btn btn-secondary" id="stopBtn" onclick="stopLearning()" disabled>
                    <span>⏹️</span> 停止
                </button>
            </div>
            <div class="control-row">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="autoNext" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: bold; color: #333;">自动朗读下一题</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="showAnswer" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: bold; color: #333;">朗读答案</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="showAnalysis" checked style="width: 18px; height: 18px;">
                    <span style="font-weight: bold; color: #333;">朗读解析</span>
                </label>
            </div>
            <div class="keyboard-hint">
                <kbd>←</kbd> 上一题 | <kbd>→</kbd> 下一题 | <kbd>Space</kbd> 播放/暂停
            </div>
        </div>

        <div class="filter-group">
            <button class="filter-btn active" data-type="全部">全部</button>
            <button class="filter-btn" data-type="单选题">单选题</button>
            <button class="filter-btn" data-type="多选题">多选题</button>
            <button class="filter-btn" data-type="判断题">判断题</button>
        </div>

        <div class="question-display">
            <div class="q-header">
                <span class="q-num" id="qNum">第 1 题</span>
                <span class="q-type" id="qType">加载中</span>
            </div>
            <div class="q-text" id="qText">加载中...</div>
            <div class="q-options" id="qOptions"></div>
            <div class="q-answer" id="qAnswer" style="display: none;"></div>
            <div class="nav-row">
                <button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>
                <button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>
            </div>
        </div>
        <div class="q-analysis" id="qAnalysis" style="display: none;"></div>

        <div class="status" id="status" style="display: none;"></div>
    </div>

    <script>
        let questions = [];
        let filteredQuestions = [];
        let currentIndex = 0;
        let progress = { currentIndex: 0, completed: [], round: 1 };
        let isPlaying = false;
        let isPaused = false;
        let synth = window.speechSynthesis;
        let currentUtterance = null;
        let currentFilter = '全部';
        let selectedVoice = null;
        let isVoiceInitialized = false;
        // iOS 保活：仅用wakeLock防屏幕熄灭

        // === 数据加载 ===
        async function loadQuestions() {
            try {
                const res = await fetch('api/questions.php');
                const data = await res.json();
                if (data.success) {
                    questions = data.data;
                    filteredQuestions = [...questions];
                    // 恢复上次筛选设置
                    const savedFilter = localStorage.getItem('safety_voice_filter');
                    if (savedFilter) {
                        const fBtn = document.querySelector('.filter-btn[data-type="' + savedFilter + '"]');
                        if (fBtn) {
                            fBtn.classList.add('active');
                            currentFilter = savedFilter;
                            filteredQuestions = currentFilter === '全部' ? [...questions] : questions.filter(q => q.type === currentFilter);
                        }
                    }
                    await loadProgress();
                    document.getElementById('totalCount').textContent = filteredQuestions.length;
                    showQuestion(currentIndex);
                }
            } catch (e) {
                document.getElementById('qText').textContent = '加载失败，请刷新重试';
            }
        }

        // 获取用户ID
        let userId = localStorage.getItem('safety_user_id');
        if (userId) {
            initUserDisplay();
        }
        if (!userId) {
            fetch('api/user.php')
                .then(r => r.json())
                .then(data => {
                    userId = data.user_id;
                    localStorage.setItem('safety_user_id', userId);
                    initUserDisplay();
                });
        }
        
        // === 进度管理 ===
        const PROGRESS_LOCAL_KEY = 'safety_voice_progress';

        function saveProgressLocal() {
            progress.currentIndex = currentIndex;
            localStorage.setItem(PROGRESS_LOCAL_KEY, JSON.stringify(progress));
        }

        function loadProgressLocal() {
            try {
                const saved = localStorage.getItem(PROGRESS_LOCAL_KEY);
                if (saved) {
                    const p = JSON.parse(saved);
                    if (p.completed && p.completed.length > 0) return p;
                }
            } catch (e) {}
            return null;
        }

        async function loadProgress() {
            while (!userId) await new Promise(r => setTimeout(r, 100));
            let loaded = false;
            try {
                const res = await fetch('api/progress.php?action=get&type=voice&user_id=' + userId, { credentials: 'include' });
                const data = await res.json();
                if (data.success && data.data) {
                    progress = (typeof data.data === 'string') ? JSON.parse(data.data) : data.data;
                    currentIndex = progress.currentIndex || 0;
                    progress.round = progress.round || 1;
                    loaded = true;
                }
            } catch (e) {
                console.warn('加载进度网络错误:', e);
            }
            if (!loaded) {
                const local = loadProgressLocal();
                if (local) {
                    progress = local;
                    currentIndex = progress.currentIndex || 0;
                    progress.round = progress.round || 1;
                }
            }
            updateRoundInfo();
        }

        function updateRoundInfo() {
            const total = filteredQuestions.length;
            const completed = progress.completed ? progress.completed.length : 0;
            const remain = total - completed;
            document.getElementById('roundBadge').textContent = '第 ' + (progress.round || 1) + ' 轮学习';
            document.getElementById('completedCount').textContent = completed;
            document.getElementById('remainCount').textContent = remain;
        }

        async function finishRound() {
            if (!confirm('确定要完成本轮学习吗？\n\n本轮已完成的题目将被标记，下一轮将优先学习未完成的题目。')) return;
            
            progress.round = (progress.round || 1) + 1;
            await saveProgress();
            updateRoundInfo();
            
            // 重新加载题目，优先显示未完成的
            const uncompleted = questions.filter((q, i) => !progress.completed.includes(i));
            if (uncompleted.length > 0) {
                filteredQuestions = uncompleted;
                currentIndex = 0;
                document.getElementById('totalCount').textContent = filteredQuestions.length;
                showQuestion(currentIndex);
                showStatus('✅ 第 ' + (progress.round - 1) + ' 轮完成！进入第 ' + progress.round + ' 轮');
            } else {
                showStatus('🎉 恭喜！所有题目已完成');
            }
        }

        async function resetProgress() {
            if (!confirm('确定要重置所有学习进度吗？\n\n此操作不可恢复！')) return;
            
            progress = { currentIndex: 0, completed: [], round: 1 };
            currentIndex = 0;
            await saveProgress();
            
            // 重置筛选
            filteredQuestions = [...questions];
            document.getElementById('totalCount').textContent = filteredQuestions.length;
            updateRoundInfo();
            showQuestion(currentIndex);
            showStatus('🔄 进度已重置');
        }

        async function saveProgress() {
            if (!userId) return;
            progress.currentIndex = currentIndex;
            saveProgressLocal();
            try {
                const res = await fetch('api/progress.php?user_id=' + userId, {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=save&type=voice&data=' + encodeURIComponent(JSON.stringify(progress))
                });
                const result = await res.json();
                if (!result.success) console.warn('进度保存失败:', result);
            } catch (e) {
                console.warn('进度保存网络错误:', e);
            }
        }

        // === iOS 保活：wakeLock + Silent AudioContext ===
        // wakeLock → 防止自动锁屏（用户在看时屏幕不熄灭）
        // Silent AudioContext → 锁屏后 iOS 因有音频会话，页面存活更久
        let wakeLock = null;
        let audioCtx = null;

        function initSilentAudio() {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    gain.gain.value = 0;
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume().catch(() => {});
                }
            } catch(e) {}
        }

        function stopSilentAudio() {
            try {
                if (audioCtx && audioCtx.state !== 'closed') {
                    audioCtx.suspend().catch(() => {});
                }
            } catch(e) {}
        }

        function destroySilentAudio() {
            try {
                if (audioCtx && audioCtx.state !== 'closed') {
                    audioCtx.close().catch(() => {});
                }
                audioCtx = null;
            } catch(e) {}
        }

        async function startKeepAlive() {
            if ('wakeLock' in navigator) {
                try {
                    wakeLock = await navigator.wakeLock.request('screen');
                    wakeLock.addEventListener('release', () => {
                        console.log('屏幕唤醒锁已释放');
                        wakeLock = null;
                    });
                } catch (err) {
                    console.log('唤醒锁请求失败:', err);
                }
            }
            initSilentAudio();
        }

        function stopKeepAlive() {
            if (wakeLock) {
                wakeLock.release().catch(() => {});
                wakeLock = null;
            }
            stopSilentAudio();
        }

        // === iOS Safari：锁屏/切换App 行为 ===
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // 用户锁屏或切到其他App
                console.log('页面隐藏 (isPlaying=' + isPlaying + ')');
                if (isPlaying) {
                    stopSilentAudio();
                }
            } else {
                // 用户回到页面：自动续播
                console.log('页面恢复可见');
                if (isPlaying && !isPaused) {
                    initSilentAudio();
                    try {
                        synth.cancel();
                        speakQuestion(currentIndex);
                    } catch(e) {}
                }
            }
        });

        window.addEventListener('pagehide', () => {
            console.log('pagehide: 强制停止语音');
            synth.cancel();
            destroySilentAudio();
            stopKeepAlive();
        });

        document.addEventListener('freeze', () => {
            console.log('页面被冻结，停止语音');
            synth.cancel();
            stopSilentAudio();
        });

        

        // === 浏览器检测 ===
        function detectBrowser() {
            const ua = navigator.userAgent;
            return {
                isSafariIOS: /iPhone|iPad|iPod/.test(ua) && /Safari/.test(ua) && !/Chrome|Edg|OPR|CriOS/.test(ua),
                isEdge: /Edg\//.test(ua)
            };
        }

        // === 语音初始化 ===
        function initVoices() {
            const voiceOptions = document.getElementById('voiceOptions');
            function loadVoices() {
                const voices = synth.getVoices();
                const allowedVoices = [
                    { name: 'Microsoft Yunyang', icon: '🎙️', desc: '云扬（自然）' },
                    { name: 'Microsoft Xiaoyi', icon: '🎙️', desc: '晓伊（女声）' },
                    { name: 'Microsoft Yunxi', icon: '🎙️', desc: '云希（男声）' }
                ];
                voiceOptions.innerHTML = '';
                let foundAny = false;
                allowedVoices.forEach(allowed => {
                    if (allowed.isOnline) {
                        foundAny = true;
                        const option = document.createElement('div');
                        option.className = 'voice-option';
                        option.dataset.voiceName = allowed.name;
                        option.innerHTML = '<span class="voice-icon">' + allowed.icon + '</span><span class="voice-name">' + allowed.desc + '</span>';
                        option.onclick = () => {
                        localStorage.setItem('safety_voice_user_set', '1');
                        selectVoice(voice.name);
                    };
                        voiceOptions.appendChild(option);
                        return;
                    }
                    const voice = voices.find(v => v.name.includes(allowed.name));
                    if (voice) {
                        foundAny = true;
                        const option = document.createElement('div');
                        option.className = 'voice-option';
                        option.dataset.voiceName = voice.name;
                        option.innerHTML = '<span class="voice-icon">' + allowed.icon + '</span><span class="voice-name">' + allowed.desc + '</span>';
                        option.onclick = () => {
                            localStorage.setItem('safety_voice_user_set', '1');
                            selectVoice(voice.name);
                        };
                        voiceOptions.appendChild(option);
                    }
                });
                if (!foundAny) {
                    voiceOptions.innerHTML = '<div style="color: #000000; padding: 10px;">🎙️本地语音</div>';
                } else if (!isVoiceInitialized) {
                    if (voices.length === 0) {
                        // 语音引擎尚未就绪（首次加载时常见），暂选在线兜底，不锁定
                        const fb = voices.find(v => v.name.includes("Xiaoyi")) || voices[0]; if (fb) selectVoice(fb.name);
                        // 不设 isVoiceInitialized，等 onvoiceschanged 触发后重新选择
                    } else {
                        // 语音已就绪，按策略选择默认语音
                        const userSet = localStorage.getItem('safety_voice_user_set') === '1';
                        const savedType = localStorage.getItem('safety_voice_type');
                        const savedName = localStorage.getItem('safety_voice_name');
                        if (userSet && savedType === 'offline' && savedName && voices.find(v => v.name === savedName)) {
                            selectVoice(savedName);
                        } else {
                            // 无用户手动选择记录，按浏览器策略自动选择
                            const browser = detectBrowser();
                            if (browser.isSafariIOS) {
                                // iOS Safari → 使用本地 speechSynthesis 中文语音
                                const zhVoice = voices.find(v => v.lang.startsWith('zh'));
                                if (zhVoice) {
                                    selectVoice(zhVoice.name);
                                } else {
                                    const fallback = voices.find(v => v.name.includes('Xiaoyi')) || voices[0];
                                    if (fallback) selectVoice(fallback.name);
                                }
                            } else if (browser.isEdge) {
                                // Edge → 用云希（男声）
                                const yunxi = voices.find(v => v.name.includes('Yunxi'));
                                if (yunxi) {
                                    selectVoice(yunxi.name);
                                } else {
                                    const fb = voices.find(v => v.name.includes("Xiaoyi")) || voices[0]; if (fb) selectVoice(fb.name);
                                }
                            } else {
                                // 其他浏览器：默认第一个可用中文语音
                                const zhVoice = voices.find(v => v.lang.startsWith('zh'));
                                if (zhVoice) {
                                    selectVoice(zhVoice.name);
                                } else {
                                    const fallback = voices.find(v => v.name.includes('Xiaoyi')) || voices[0];
                                    if (fallback) selectVoice(fallback.name);
                                }
                            }
                        }
                        isVoiceInitialized = true;
                    }
                }
            }
            if (synth.onvoiceschanged !== undefined) {
                synth.onvoiceschanged = loadVoices;
            }
            loadVoices();
        }

        function selectVoice(voiceName) {
            const voices = synth.getVoices();
            selectedVoice = voices.find(v => v.name === voiceName);
            localStorage.setItem('safety_voice_type', 'offline');
            localStorage.setItem('safety_voice_name', voiceName);
            document.querySelectorAll('.voice-option').forEach(opt => opt.classList.remove('selected'));
            document.querySelector(`[data-voice-name="${voiceName}"]`).classList.add('selected');
            showStatus('✅ 已选择语音：' + voiceName);
        }

        // === 速度控制 ===
        const speedSlider = document.getElementById('speedSlider');
        const speedValueEl = document.getElementById('speedValue');
        // 恢复速度设置
        const savedSpeed = localStorage.getItem('safety_voice_speed');
        if (savedSpeed) { speedSlider.value = savedSpeed; speedValueEl.textContent = savedSpeed + 'x'; }
        speedSlider.addEventListener('input', () => {
            speedValueEl.textContent = speedSlider.value + 'x';
            localStorage.setItem('safety_voice_speed', speedSlider.value);
        });

        // === 朗读选项记忆 ===
        ['autoNext', 'showAnswer', 'showAnalysis'].forEach(id => {
            const cb = document.getElementById(id);
            if (cb) {
                const saved = localStorage.getItem('safety_voice_' + id);
                if (saved !== null) cb.checked = saved === 'true';
                cb.addEventListener('change', () => {
                    localStorage.setItem('safety_voice_' + id, cb.checked);
                });
            }
        });

        // === 筛选 ===
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.type;
                localStorage.setItem('safety_voice_filter', currentFilter);
                filteredQuestions = currentFilter === '全部' ? [...questions] : questions.filter(q => q.type === currentFilter);
                currentIndex = 0;
                updateProgress();
                showQuestion(currentIndex);
                showStatus('已筛选 ' + filteredQuestions.length + ' 题');
            });
        });

        // === 题目显示 ===
        function showQuestion(index) {
            clearHighlight();
            if (index >= filteredQuestions.length) {
                showStatus('🎉 已完成所有题目！');
                stopLearning();
                return;
            }
            const q = filteredQuestions[index];
            document.getElementById('qNum').textContent = '第 ' + (index + 1) + ' 题';
            document.getElementById('qType').textContent = q.type;
            document.getElementById('qText').textContent = q.question;
            const optionsDiv = document.getElementById('qOptions');
            let html = '';
            Object.keys(q.options).forEach(key => {
                const text = q.options[key];
                let isCorrect = false;
                if (q.type === '多选题') isCorrect = q.answer.includes(key);
                else if (q.type === '判断题') isCorrect = q.options[key] === q.answer;
                else isCorrect = key === q.answer;
                html += `<div class="q-option ${isCorrect ? 'correct' : ''}">${key}. ${text}</div>`;
            });
            optionsDiv.innerHTML = html;
            document.getElementById('qAnswer').style.display = 'none';
            // 显示解析（逐行包裹，用于朗读高亮）
            const analysisDiv = document.getElementById('qAnalysis');
            if (q.analysis) {
                let analysisText = q.analysis;
                if (q.type === '判断题') {
                    analysisText = analysisText.replace(/^[❌✅]\s*([A-D]\.)/gm, '$1');
                }
                const lines = analysisText.split('\n').filter(l => l.trim());
                let linesHtml = '';
                lines.forEach((line, i) => {
                    linesHtml += '<div class="q-analysis-line" data-idx="' + i + '">' + line + '</div>';
                });
                analysisDiv.innerHTML = '<div class="q-analysis-title">🔍 题目解析</div>' + linesHtml;
                analysisDiv.style.display = 'block';
            // tips 追加显示（analysis 不含时）
            if (q.tips && Array.isArray(q.tips) && q.tips.length > 0) {
                if (!q.analysis || !q.analysis.includes('补充小知识')) {
                    let tipsHtml = '<div class="tips-block"><span class="tips-badge">💡 补充小知识</span><div class="tips-content">• ' + q.tips.join('<br>• ') + '</div></div>';
                    analysisDiv.innerHTML += tipsHtml;
                }
            }
            } else {
                analysisDiv.style.display = 'none';
            }
            
            updateProgress();
            saveProgress();
        }

        // === 导航 ===
        function nextQuestion() {
            if (currentIndex < filteredQuestions.length - 1) {
                synth.cancel();
                clearHighlight();
                const realIndex = questions.indexOf(filteredQuestions[currentIndex]);
                if (!progress.completed.includes(realIndex)) progress.completed.push(realIndex);
                currentIndex++;
                showQuestion(currentIndex);
                updateRoundInfo();
            }
        }
        function prevQuestion() {
            if (currentIndex > 0) {
                synth.cancel();
                clearHighlight();
                currentIndex--;
                showQuestion(currentIndex);
            }
        }

        // === 朗读高亮（性能优化：跟踪元素避免冗余DOM操作）===
        let _curHlEl = null;
        let _lastScrollEl = null;

        function clearHighlight() {
            if (_curHlEl) {
                _curHlEl.classList.remove('speaking');
                _curHlEl = null;
            }
            _lastScrollEl = null;
        }

        function setHighlight(el) {
            if (el === _curHlEl) return; // 同一个元素，不重复操作
            clearHighlight();
            _curHlEl = el;
            el.classList.add('speaking');
        }

        function scrollIntoViewIfNeeded(el) {
            if (el === _lastScrollEl) return;
            try {
                const rect = el.getBoundingClientRect();
                if (rect.top < 60 || rect.bottom > window.innerHeight - 60) {
                    el.scrollIntoView({ block: 'center', behavior: 'smooth' });
                    _lastScrollEl = el;
                }
            } catch(e) {}
        }

        function highlightSegment(charIndex, segments) {
            for (const seg of segments) {
                if (charIndex >= seg.start && charIndex < seg.end) {
                    setHighlight(seg.el);
                    scrollIntoViewIfNeeded(seg.el);
                    break;
                }
            }
        }

        function buildHighlightSegments(index, q) {
            const segments = [];
            let pos = 0;
            const prefix = `第${index + 1}题，${q.type}。`;
            pos += prefix.length;
            const qEl = document.getElementById('qText');
            if (qEl) {
                segments.push({ start: pos, end: pos + q.question.length, el: qEl });
            }
            pos += q.question.length + 1;
            const keys = Object.keys(q.options);
            const optEls = document.querySelectorAll('.q-option');
            keys.forEach((key, i) => {
                const optPrefix = `选项${key}，`;
                pos += optPrefix.length;
                if (optEls[i]) {
                    segments.push({ start: pos, end: pos + q.options[key].length, el: optEls[i] });
                }
                pos += q.options[key].length + 1;
            });
            return segments;
        }

        // === 朗读逻辑 ===
        function speakQuestion(index) {
            if (index >= filteredQuestions.length) return;
            const q = filteredQuestions[index];
            const speed = parseFloat(speedSlider.value);
            let text = `第${index + 1}题，${q.type}。${q.question}。`;
            if (q.type !== '判断题') {
                Object.keys(q.options).forEach(key => {
                    text += `选项${key}，${q.options[key]}。`;
                });
            }

            // 朗读前滚动到题目显示区
            const qDisplay = document.querySelector('.question-display');
            if (qDisplay) qDisplay.scrollIntoView({ block: 'start', behavior: 'smooth' });

                const voices = synth.getVoices();
                if (!selectedVoice && voices.length) {
                    selectedVoice = voices.find(v => v.name.includes('Xiaoyi')) || voices[0];
                }
                currentUtterance = new SpeechSynthesisUtterance(text);
                currentUtterance.rate = speed;
                currentUtterance.pitch = 1;
                currentUtterance.volume = 1;
                if (selectedVoice) currentUtterance.voice = selectedVoice;

                // iOS Safari 修复：每次 speak 前 cancel 防止后续 utterance 静默
                synth.cancel();

                // 朗读高亮：onboundary 追踪朗读位置
                const qSegments = buildHighlightSegments(index, q);
                currentUtterance.onboundary = (e) => {
                    if (e.name === 'word') {
                        highlightSegment(e.charIndex, qSegments);
                    }
                };

                currentUtterance.onend = () => {
                    clearHighlight();
                    if (document.getElementById('showAnswer').checked) {
                        const ansUtterance = new SpeechSynthesisUtterance(buildAnswerText(q));
                        ansUtterance.rate = speed;
                        ansUtterance.voice = selectedVoice;
                        const ansEl = document.getElementById('qAnswer');
                        ansUtterance.onboundary = () => {
                            if (ansEl) setHighlight(ansEl);
                        };
                        ansUtterance.onend = () => {
                            clearHighlight();
                            showAnswerDiv(q);
                            speakAnalysisAndTips(q, () => { autoNextQuestion(); }, speed);
                        };
                        synth.cancel();
                        synth.speak(ansUtterance);
                    } else {
                        speakAnalysisAndTips(q, () => { autoNextQuestion(); }, speed);
                    }
                };
                synth.speak(currentUtterance);
        }

        // 朗读解析 + 补充小知识（speechSynthesis）
        function speakAnalysisAndTips(q, callback, speed) {
            const showAnalysis = document.getElementById('showAnalysis').checked;
            // 先收集要朗读的文本
            let segments = [];
            if (showAnalysis && q.analysis) {
                const cleanAnalysis = q.analysis.replace(/[🔍🎯✅💡■◆►★▶]/g, '').replace(/\n+/g, '。').trim();
                if (cleanAnalysis) segments.push(cleanAnalysis);
            }
            if (q.tips && Array.isArray(q.tips) && q.tips.length > 0) {
                // analysis 已含"补充小知识"时不重复添加
                if (!q.analysis || !q.analysis.includes('补充小知识')) {
                    const tipsText = '补充小知识：' + q.tips.join('。');
                    segments.push(tipsText);
                }
            }
            // 顺序朗读
            function speakNext(i) {
                if (i >= segments.length) { if (callback) callback(); return; }
                const u = new SpeechSynthesisUtterance(segments[i]);
                u.rate = speed;
                u.voice = selectedVoice;
                // 构建解析文本的行级高亮映射
            let analysisLineSegments = null;
            if (segments.length > 0) {
                const analysis = q.analysis || '';
                const lines = analysis.split('\n').filter(l => l.trim());
                const lineEls = document.querySelectorAll('.q-analysis-line');
                if (lines.length > 0 && lineEls.length > 0) {
                    analysisLineSegments = [];
                    let cleanPos = 0;
                    const cleanedText = analysis.replace(/\n+/g, '\u3002');
                    for (let i = 0; i < lines.length; i++) {
                        const cleanedLine = lines[i];
                        const notNewline = cleanedLine.replace(/\n/g, '');
                        if (lineEls[i]) {
                            analysisLineSegments.push({
                                start: cleanPos,
                                end: cleanPos + notNewline.length,
                                el: lineEls[i]
                            });
                        }
                        cleanPos += notNewline.length + 1;
                    }
                }
            }
            // 滚动到解析区
            const analysisEl = document.getElementById('qAnalysis');
            if (analysisEl) {
                analysisEl.scrollIntoView({ block: 'start', behavior: 'smooth' });
            }
            // 解析高亮：行级别 + onboundary 细粒度追踪
                if (i === 0 && analysisLineSegments) {
                    const segs = analysisLineSegments;
                    u.onboundary = (e) => {
                        if (e.name === 'word' && e.charIndex !== undefined) {
                            for (const s of segs) {
                                if (e.charIndex >= s.start && e.charIndex < s.end) {
                                    setHighlight(s.el);
                                    scrollIntoViewIfNeeded(s.el);
                                    break;
                                }
                            }
                        }
                    };
                } else if (i > 0 && analysisLineSegments) {
                    const lastLine = analysisLineSegments[analysisLineSegments.length - 1];
                    if (lastLine) setHighlight(lastLine.el);
                }
                u.onend = () => { speakNext(i + 1); };
                u.onerror = () => { speakNext(i + 1); };
                synth.cancel();
                synth.speak(u);
            }
            speakNext(0);
        }

        function buildAnswerText(q) {
            if (q.type === '判断题') return `答案是：${q.answer}`;
            if (q.type === '多选题') return `答案是：${q.answer.split('').join('、')}。`;
            return `答案是：${q.answer}。${q.options[q.answer]}`;
        }

        function showAnswerDiv(q) {
            const answerDiv = document.getElementById('qAnswer');
            if (q.type === '判断题') answerDiv.textContent = `答案：${q.answer}`;
            else if (q.type === '多选题') answerDiv.textContent = `答案：${q.answer}`;
            else answerDiv.textContent = `答案：${q.answer}. ${q.options[q.answer] || ''}`;
            answerDiv.style.display = 'block';
        }

        function autoNextQuestion() {
            if (document.getElementById('autoNext').checked && isPlaying && !isPaused) {
                setTimeout(() => {
                    currentIndex++;
                    if (!progress.completed.includes(currentIndex - 1)) progress.completed.push(currentIndex - 1);
                    showQuestion(currentIndex);
                    speakQuestion(currentIndex);
                }, 1000);
            }
        }

        // === 播放控制（集成iOS保活） ===
        function startLearning() {
            if (filteredQuestions.length === 0) {
                showStatus('⚠️ 没有可选题目');
                return;
            }
            startKeepAlive();
            isPlaying = true;
            isPaused = false;
            document.getElementById('startBtn').disabled = true;
            document.getElementById('pauseBtn').disabled = false;
            document.getElementById('stopBtn').disabled = false;
            document.getElementById('pauseBtn').innerHTML = '<span>⏸️</span> 暂停';
            showQuestion(currentIndex);
            speakQuestion(currentIndex);
            showStatus('🔊 正在朗读...');
        }

        function pauseLearning() {
            if (isPaused) {
                isPaused = false;
                synth.cancel();
                startKeepAlive();
                document.getElementById('pauseBtn').innerHTML = '<span>⏸️</span> 暂停';
                speakQuestion(currentIndex);
                showStatus('🔊 继续朗读...');
            } else {
                isPaused = true;
                synth.cancel();
                clearHighlight();
        document.getElementById('pauseBtn').innerHTML = '<span>▶️</span> 继续';
                showStatus('⏸️ 已暂停');
            }
        }

        function stopLearning() {
            stopKeepAlive();
            isPlaying = false;
            isPaused = false;
            clearHighlight();
synth.cancel();
            setTimeout(() => synth.cancel(), 100);
            setTimeout(() => synth.cancel(), 300);
            document.getElementById('startBtn').disabled = false;
            document.getElementById('pauseBtn').disabled = true;
            document.getElementById('stopBtn').disabled = true;
            document.getElementById('pauseBtn').innerHTML = '<span>⏸️</span> 暂停';
            showStatus('⏹️ 已停止');
        }

        // === 进度 & 状态 ===
        function updateProgress() {
            const total = filteredQuestions.length;
            if (total === 0) return;
            const percent = Math.round((currentIndex + 1) / total * 100);
            document.getElementById('currentIndex').textContent = currentIndex + 1;
            document.getElementById('totalCount').textContent = total;
            const fill = document.getElementById('progressFill');
            fill.style.width = percent + '%';
            fill.textContent = percent > 5 ? percent + '%' : '';
            updateRoundInfo();
        }

        function showStatus(text) {
            const status = document.getElementById('status');
            // 如果在播放状态，添加防熄屏指示器
            if (isPlaying && !isPaused && (text.includes('朗读') || text.includes('继续'))) {
                status.innerHTML = '<div class="playing-indicator"><span class="dot"></span>' + text + '</div>';
            } else {
                status.textContent = text;
            }
            status.style.display = 'block';
            setTimeout(() => status.style.display = 'none', 3000);
        }

        // === 键盘快捷键 ===
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') prevQuestion();
            if (e.key === 'ArrowRight') nextQuestion();
        });

        // === 滑动切换题目 ===
        let touchStartX = 0;
        let touchStartY = 0;
        const SWIPE_THRESHOLD = 50;

        document.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        document.addEventListener('touchmove', e => {
            const diffY = Math.abs(e.touches[0].clientY - touchStartY);
            const diffX = Math.abs(e.touches[0].clientX - touchStartX);
            if (diffY > diffX) return;
            if (diffX > 5) e.preventDefault();
        }, { passive: false });

        document.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const diffX = touchEndX - touchStartX;
            const diffY = Math.abs(touchEndY - touchStartY);
            if (Math.abs(diffX) < SWIPE_THRESHOLD) return;
            if (diffY > Math.abs(diffX)) return;
            if (diffX < 0) nextQuestion();
            else prevQuestion();
        }, { passive: true });

        // === 启动 ===
        initVoices();
        loadQuestions();
    

function editUserId() {
    const display = document.querySelector('.user-display');
    if (!display) return;
    const currentName = userId || '我的账号';
    display.innerHTML = '<input type="text" class="user-input" id="userInput" value="' + currentName + '" maxlength="20" placeholder="输入用户名...">' +
        '<button class="btn-save" onclick="saveUserId()">保存</button>' +
        '<button class="btn-cancel" onclick="cancelEdit()">取消</button>';
    const input = document.getElementById('userInput');
    if (input) {
        input.focus();
        input.select();
    }
}

function saveUserId() {
    const input = document.getElementById('userInput');
    if (!input) return;
    const newId = input.value.trim();
    if (!newId) return;
    userId = newId;
    localStorage.setItem('safety_user_id', userId);
    location.reload();
}

function cancelEdit() {
    const display = document.querySelector('.user-display');
    if (!display) return;
    display.innerHTML = '用户: <span id="user-id">' + userId + '</span><button class="btn-edit" onclick="editUserId()" title="修改用户名">✏️</button>';
}

// 初始化用户显示
function initUserDisplay() {
    const userIdEl = document.getElementById('user-id');
    if (userIdEl && userId) {
        userIdEl.textContent = userId;
    }
}

    </script>
</body>
</html>
