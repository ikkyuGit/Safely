<?php
// study.php - 智能学习系统
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>智能学习 - 安管人员考试</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 15px;
        }
        .container { max-width: 900px; margin: 0 auto; }
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
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-primary:hover { transform: translateY(-2px); }
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
        .question-card {
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
        .q-badges { display: flex; gap: 10px; }
        .q-type {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        .q-difficulty {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .q-difficulty.普通 { background: #e3f2fd; color: #1976D2; }
        .q-difficulty.重点 { background: #fff3e0; color: #F57C00; }
        .q-difficulty.难点 { background: #fce4ec; color: #C2185B; }
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
        .q-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
        .q-option {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            background: #fafafa;
            transition: all 0.2s;
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
            margin-bottom: 25px;
        }
        .tips-section {
            background: #fff3e0;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-left: 4px solid #F57C00;
        }
        .tips-title { font-weight: bold; color: #F57C00; margin-bottom: 10px; }
        .tips-list { list-style: none; }
        .tips-list li { padding: 5px 0; color: #333; }
        .tips-list li::before { content: '💡 '; }
        .memory-section {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-left: 4px solid #4CAF50;
        }
        .analysis-section {
            background: #fffdf5;
            border: 2px solid #FFE082;
            border-left: 5px solid #FF9800;
            padding: 20px 24px;
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(255,152,0,0.12);
        }
        .analysis-title {
            font-weight: bold;
            color: #E65100;
            margin-bottom: 12px;
            font-size: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #FFE0B2;
        }
        .analysis-content {
            font-size: 15px;
            line-height: 1.8;
            color: #333;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .memory-title { font-weight: bold; color: #4CAF50; margin-bottom: 10px; }
        .memory-list { list-style: none; }
        .memory-list li { padding: 5px 0; color: #333; }
        .memory-list li::before { content: '📌 '; }
        .loading { text-align: center; padding: 50px; color: #666; }
        .nav-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .keyboard-hint {
            background: rgba(255,255,255,0.1);
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            text-align: center;
        }
        .keyboard-hint kbd {
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
            margin: 0 3px;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            h1 { font-size: 22px; }
            .q-text { font-size: 18px; }
            .btn { padding: 10px 20px; font-size: 13px; }
            .keyboard-hint { display: none; }
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
    <div class="container">
        <div class="header" style="position: relative;">
            <a href="index.php" class="back-btn">← 返回</a>
            <h1>🧠 智能学习</h1>
            <p class="subtitle">每题都有学习技巧和记忆重点</p>

        </div>

        <div class="round-info">
            <div class="round-badge" id="roundBadge">第 1 轮学习</div>
            <div class="round-stats">
                <div class="stat-item">
                    <div class="stat-num" id="completedCount">0</div>
                    <div class="stat-label">已完成</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num" id="remainCount">600</div>
                    <div class="stat-label">剩余</div>
                </div>
            </div>
        </div>
        <div class="progress-section">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 0%">0%</div>
            </div>
            <div class="progress-text">
                进度：<strong id="currentIndex">0</strong> / <strong id="totalCount">600</strong> 题
            </div>
        </div>
        <div class="controls">
            <button class="btn btn-secondary" onclick="finishRound()">✅ 完成本轮</button>
            <button class="btn btn-danger" onclick="resetProgress()">🔄 重置</button>
        </div>
        <div class="keyboard-hint">
            <kbd>←</kbd> 上一题 | <kbd>→</kbd> 下一题 | <kbd>Space</kbd> 显示答案
        </div>
        <div class="filter-group">
            <button class="filter-btn active" data-type="全部">全部</button>
            <button class="filter-btn" data-type="单选题">单选题</button>
            <button class="filter-btn" data-type="多选题">多选题</button>
            <button class="filter-btn" data-type="判断题">判断题</button>
            <button class="filter-btn" data-type="重点">重点题</button>
            <button class="filter-btn" data-type="难点">难点题</button>
        </div>
        <div class="question-card" id="questionCard">
            <div class="loading">加载中...</div>
        </div>
        <div class="analysis-section" id="analysisSection" style="display:none;"></div>
        <div class="tips-section" id="tipsSection" style="display:none;"></div>
        <div class="memory-section" id="memorySection" style="display:none;"></div>
    </div>
    <script>
        let questions = [];
        let filteredQuestions = [];
        let currentIndex = 0;
        let progress = { currentIndex: 0, currentRound: 1, completed: [], roundHistory: [] };

        async function loadQuestions() {
            try {
                const res = await fetch('api/questions.php');
                const data = await res.json();
                if (data.success) {
                    questions = data.data;
                    filteredQuestions = [...questions];
                    document.getElementById('totalCount').textContent = filteredQuestions.length;
                    // 恢复上次筛选设置（在loadProgress之前，这样currentIndex对应筛选后的列表）
                    const savedFilter = localStorage.getItem('safety_study_filter');
                    if (savedFilter) {
                        const btn = document.querySelector('.filter-btn[data-type="' + savedFilter + '"]');
                        if (btn) {
                            btn.classList.add('active');
                            const filter = savedFilter;
                            if (filter === '全部') {
                                filteredQuestions = [...questions];
                            } else if (filter === '重点') {
                                filteredQuestions = questions.filter(q => q.difficulty === '重点');
                            } else if (filter === '难点') {
                                filteredQuestions = questions.filter(q => q.difficulty === '难点');
                            } else {
                                filteredQuestions = questions.filter(q => q.type === filter);
                            }
                            document.getElementById('totalCount').textContent = filteredQuestions.length;
                        }
                    }
                    await loadProgress();
                    showQuestion(currentIndex);
                }
            } catch (e) {
                document.getElementById('questionCard').innerHTML = '<div class="loading">加载失败，请刷新重试</div>';
            }
        }

        // 获取用户ID（确保和导航页一致）
        let userId = localStorage.getItem('safety_user_id');
        if (userId) {
            initUserDisplay();
        }
        if (!userId) {
            // 没有保存的ID，从服务器获取
            fetch('api/user.php')
                .then(r => r.json())
                .then(data => {
                    userId = data.user_id;
                    localStorage.setItem('safety_user_id', userId);
                    initUserDisplay();
                });
        }
        
        // === 进度管理 ===
        const PROGRESS_LOCAL_KEY = 'safety_study_progress';

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

        async function saveProgress() {
            if (!userId) return;
            progress.currentIndex = currentIndex;
            saveProgressLocal(); // 始终保存到本地
            try {
                const res = await fetch('api/progress.php?user_id=' + userId, {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=save&type=study&data=' + encodeURIComponent(JSON.stringify(progress))
                });
                const result = await res.json();
                if (!result.success) console.warn('进度保存失败:', result);
            } catch (e) {
                console.warn('进度保存网络错误:', e);
            }
        }

        async function loadProgress() {
            // 等待userId就绪
            while (!userId) await new Promise(r => setTimeout(r, 100));
            let loaded = false;
            try {
                const res = await fetch('api/progress.php?action=get&type=study&user_id=' + userId, { credentials: 'include' });
                const data = await res.json();
                if (data.success && data.data) {
                    progress = (typeof data.data === 'string') ? JSON.parse(data.data) : data.data;
                    currentIndex = progress.currentIndex || 0;
                    progress.completed = progress.completed || [];
                    progress.currentRound = progress.currentRound || 1;
                    loaded = true;
                }
            } catch (e) {
                console.warn('加载进度网络错误:', e);
            }
            // 网络失败时从本地恢复
            if (!loaded) {
                const local = loadProgressLocal();
                if (local) {
                    progress = local;
                    currentIndex = progress.currentIndex || 0;
                    progress.completed = progress.completed || [];
                    progress.currentRound = progress.currentRound || 1;
                }
            }
            updateRoundInfo();
        }

        function showQuestion(index) {
            if (index >= filteredQuestions.length || index < 0) return;
            const q = filteredQuestions[index];

            let optionsHtml = '';
            const optionKeys = Object.keys(q.options);
            for (const key of optionKeys) {
                const text = q.options[key];
                let isCorrect = false;
                if (q.type === '多选题') {
                    isCorrect = q.answer.includes(key);
                } else if (q.type === '判断题') {
                    isCorrect = q.options[key] === q.answer;
                } else {
                    isCorrect = key === q.answer;
                }
                optionsHtml += '<div class="q-option ' + (isCorrect ? 'correct' : '') + '">' + key + '. ' + text + '</div>';
            }

            let answerText;
            if (q.type === '多选题') {
                answerText = q.answer.split('').join('、');
            } else if (q.type === '判断题') {
                answerText = q.answer;
            } else {
                answerText = q.answer + '. ' + (q.options[q.answer] || '');
            }

            const tipsHtml = (q.tips || []).map(t => '<li>' + t + '</li>').join('');
            const memHtml = (q.memory_points || []).map(m => '<li>' + m + '</li>').join('');

            document.getElementById('questionCard').innerHTML =
                '<div class="q-header">' +
                    '<span class="q-num">第 ' + (index + 1) + ' 题</span>' +
                    '<div class="q-badges">' +
                        '<span class="q-type">' + q.type + '</span>' +
                        '<span class="q-difficulty ' + q.difficulty + '">' + q.difficulty + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="q-text">' + q.question + '</div>' +
                '<div class="q-options">' + optionsHtml + '</div>' +
                '<div class="q-answer">答案：' + answerText + '</div>' +
                '<div class="nav-row">' +
                    '<button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>' +
                    '<button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>' +
                '</div>';

            // 显示解析（判断题清理错误的❌图标）
            const analysisSection = document.getElementById('analysisSection');
            if (q.analysis) {
                let analysisHtml = q.analysis;
                if (q.type === '判断题') {
                    // 判断题解析中选项前的❌/✅图标经常出错，统一替换为中性标记
                    analysisHtml = analysisHtml.replace(/^[❌✅]\s*([A-D]\.)/gm, '$1');
                }
                analysisSection.innerHTML = '<div class="analysis-title">🔍 题目解析</div><div class="analysis-content">' + analysisHtml.replace(/\n/g, '<br>') + '</div>';
                analysisSection.style.display = 'block';
            } else {
                analysisSection.style.display = 'none';
            }

            currentIndex = index;
            updateProgress();
            saveProgress();
        }

        function nextQuestion() {
            if (currentIndex < filteredQuestions.length - 1) {
                if (!progress.completed.includes(currentIndex)) {
                    progress.completed.push(currentIndex);
                }
                currentIndex++;
                showQuestion(currentIndex);
                updateRoundInfo();
            }
        }

        function prevQuestion() {
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        }

        function finishRound() {
            if (!confirm('确定完成本轮学习？')) return;
            progress.roundHistory.push({
                round: progress.currentRound,
                completed: progress.completed.length,
                total: filteredQuestions.length,
                date: new Date().toISOString()
            });
            progress.currentRound++;
            progress.completed = [];
            currentIndex = 0;
            saveProgress();
            updateRoundInfo();
            showQuestion(0);
            alert('🎉 本轮完成！开始第 ' + progress.currentRound + ' 轮学习');
        }

        function resetProgress() {
            if (!confirm('确定重置所有进度？')) return;
            progress = { currentIndex: 0, currentRound: 1, completed: [], roundHistory: [] };
            currentIndex = 0;
            saveProgress();
            updateRoundInfo();
            showQuestion(0);
            alert('✅ 已重置');
        }

        function updateProgress() {
            const total = filteredQuestions.length;
            const percent = Math.round((currentIndex + 1) / total * 100);
            document.getElementById('currentIndex').textContent = currentIndex + 1;
            const fill = document.getElementById('progressFill');
            fill.style.width = percent + '%';
            fill.textContent = percent > 5 ? percent + '%' : '';
        }

        function updateRoundInfo() {
            document.getElementById('roundBadge').textContent = '第 ' + progress.currentRound + ' 轮学习';
            document.getElementById('completedCount').textContent = progress.completed.length;
            document.getElementById('remainCount').textContent = filteredQuestions.length - progress.completed.length;
        }

        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.type;
                localStorage.setItem('safety_study_filter', filter);
                if (filter === '全部') {
                    filteredQuestions = [...questions];
                } else if (filter === '重点') {
                    filteredQuestions = questions.filter(q => q.difficulty === '重点');
                } else if (filter === '难点') {
                    filteredQuestions = questions.filter(q => q.difficulty === '难点');
                } else {
                    filteredQuestions = questions.filter(q => q.type === filter);
                }
                currentIndex = 0;
                document.getElementById('totalCount').textContent = filteredQuestions.length;
                showQuestion(0);
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'ArrowLeft') prevQuestion();
            if (e.key === 'ArrowRight') nextQuestion();
            if (e.key === ' ' || e.code === 'Space') {
                e.preventDefault();
                showAnswer();
            }
        });

        // === 滑动切换题目 ===
        let touchStartX = 0;
        let touchStartY = 0;
        const SWIPE_THRESHOLD = 50; // 滑动阈值（像素）

        document.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        document.addEventListener('touchmove', e => {
            // 如果是上下滚动，不拦截
            const diffY = Math.abs(e.touches[0].clientY - touchStartY);
            const diffX = Math.abs(e.touches[0].clientX - touchStartX);
            // 如果竖向移动大于横向，可能是滚动，不阻止
            if (diffY > diffX) return;
            // 横向滑动时阻止默认行为（防止页面左右抖动）
            if (diffX > 5) {
                e.preventDefault();
            }
        }, { passive: false });

        document.addEventListener('touchend', e => {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const diffX = touchEndX - touchStartX;
            const diffY = Math.abs(touchEndY - touchStartY);

            // 只处理水平滑动且超过阈值，且竖向偏移不大的情况
            if (Math.abs(diffX) < SWIPE_THRESHOLD) return;
            if (diffY > Math.abs(diffX)) return; // 斜度过大不算

            // 从右向左滑 → 下一题
            if (diffX < 0) {
                nextQuestion();
            }
            // 从左向右滑 → 上一题
            else {
                prevQuestion();
            }
        }, { passive: true });

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
