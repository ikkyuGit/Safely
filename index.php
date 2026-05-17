<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安管人员考试学习</title>
    <style>* { margin: 0; padding: 0; box-sizing: border-box;}
body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;}
.container { max-width: 800px; margin: 0 auto;}
.header { 
            text-align: center; 
            color: white; 
            margin-bottom: 30px;}
.header h1 { font-size: 2rem; margin-bottom: 10px;}
.header p { opacity: 0.9;}
.card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);}
.card h2 { 
            color: #333; 
            margin-bottom: 20px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;}
.btn-group { display: flex; gap: 15px; flex-wrap: wrap;}
.btn {
            flex: 1;
            min-width: 200px;
            padding: 20px;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            text-align: center;}
.btn:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,0,0,0.2);}
.btn-study { background: linear-gradient(135deg, #11998e, #38ef7d); color: white;}
.btn-exam { background: linear-gradient(135deg, #eb3349, #f45c43); color: white;}
.btn-voice { background: linear-gradient(135deg, #4776e6, #8e54e9); color: white;}
.btn-wrongbook { background: linear-gradient(135deg, #f093fb, #f5576c); color: white;}
.stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;}
.stat-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;}
.stat-value { font-size: 1.8rem; font-weight: 700; color: #667eea;}
.stat-label { color: #666; font-size: 0.9rem; margin-top: 5px;}
.progress-section { margin-top: 20px;}
.progress-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;}
.progress-title { font-weight: 600; color: #333; margin-bottom: 8px;}
.progress-bar { 
            height: 8px; 
            background: #e9ecef; 
            border-radius: 4px; 
            overflow: hidden;}
.progress-fill { 
            height: 100%; 
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 4px;
            transition: width 0.3s;}
.progress-info { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 8px;
            font-size: 0.85rem;
            color: #666;}
.user-id {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #666;
            margin-top: 15px;
            text-align: center;}
.export-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-left: 10px;
            transition: color 0.2s;}
.export-link:hover { color: #764ba2;}
.import-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: 600;
            margin-left: 10px;
            transition: color 0.2s;}
.import-link:hover { color: #2E7D32;}
.user-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;}
.user-actions {
            display: flex;
            justify-content: center;
            gap: 10px;}
.btn-edit {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 2px 6px;
            border-radius: 4px;
            transition: background 0.2s;}
.btn-edit:hover { background: #e0e0e0;}
.user-input {
            padding: 6px 12px;
            border: 2px solid #667eea;
            border-radius: 6px;
            font-size: 0.9rem;
            width: 150px;
            text-align: center;}
.btn-save {
            padding: 6px 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;}
.btn-save:hover { background: #5a6fd6;}
.btn-cancel {
            padding: 6px 12px;
            background: #f0f0f0;
            color: #666;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;}
.btn-cancel:hover { background: #e0e0e0;}
.modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;}
.modal-content {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);}
.modal-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;}
.modal-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            margin-bottom: 20px;
            outline: none;}
.modal-input:focus { border-color: #667eea;}
.modal-hint {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 20px;}
.modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;}
.modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;}
.modal-btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;}
.modal-btn-secondary {
            background: #f0f0f0;
            color: #333;}
.modal-btn-primary:hover { opacity: 0.9;}
.modal-btn-secondary:hover { background: #e0e0e0;}
.modal {
            background: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);}
.modal h3 {
            margin-bottom: 20px;
            color: #333;}
.modal-content h3 {
            margin-bottom: 20px;
            color: #333;}
.btn-confirm {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;}
.btn-confirm:hover { background: #5a6fd6;}
.loading { text-align: center; padding: 20px; color: #666;}
.error { color: #eb3349; padding: 10px; background: #ffe6e6; border-radius: 8px; margin: 10px 0;}
/* 搜题样式 */
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;}
.search-input {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s;}
.search-input:focus { border-color: #667eea;}
.search-btn {
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: transform 0.2s;}
.search-btn:hover { transform: translateY(-1px);}
.search-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 15px;}
.search-filter {
            padding: 6px 16px;
            border: 2px solid #667eea;
            background: #fff;
            color: #667eea;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s;}
.search-filter.active { background: #667eea; color: #fff;}
.search-info { color: #999; font-size: 14px; margin-bottom: 10px;}
.search-results { max-height: 500px; overflow-y: auto;}
.search-item {
            padding: 16px;
            border: 1px solid #eee;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.15s;}
.search-item:hover { background: #f8f9ff;}
.search-item-head {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;}
.search-item-num { font-weight: 700; color: #333; font-size: 14px;}
.search-item-type {
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;}
.type-单选题 { background: #667eea;}
.type-多选题 { background: #f7971e;}
.type-判断题{ background: #4CAF50;}
.search-item-q {
            font-size: 15px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 8px;}
.search-item-q mark {
            background: #ffe082;
            padding: 0 2px;
            border-radius: 2px;}
.search-item-answer {
            font-size: 14px;
            color: #1976D2;
            font-weight: 600;}
.search-item-options {
            display: none;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;}
.search-item-options.show { display: block;}
.search-option {
            padding: 8px 12px;
            margin-bottom: 4px;
            border-radius: 6px;
            font-size: 14px;
            color: #555;}
.search-option.correct {
            background: #e8f5e9;
            color: #2e7d32;
            font-weight: 600;}
.search-item-tips {
            margin-top: 8px;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.5;}
.search-item-tips.tip { background: #fff3e0; color: #e65100;}
.search-item-tips.memory { background: #e8f5e9; color: #2e7d32;}
@media (max-width: 600px) {
            .btn { min-width: 100%;}
.search-box { flex-direction: column;}</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 安管人员考试学习</h1>
            <p>智能学习 · 语音朗读 · 模拟考试 · 错题本</p>
        </div>
        
        <div class="card" style="position: relative;">
            <div class="user-id" style="position: absolute; top: 10px; right: 16px; margin: 0; background: rgba(255,255,255,0.7); padding: 6px 12px; border-radius: 8px;">
                <div class="user-display" style="justify-content: flex-end;">
                    用户: <span id="user-id">加载中...</span>
                    <button class="btn-edit" onclick="editUserId()" title="修改用户名">✏️</button>
                </div>
            </div>
            <h2>📚 学习中心</h2>
            <div class="btn-group">
                <a href="study.php" class="btn btn-study">📖 智能学习</a>
                <a href="voice.php" class="btn btn-voice">🎧 语音朗读</a>
                <a href="exam.php" class="btn btn-exam">✍️ 模拟考试</a>
                <a href="wrongbook.php" class="btn btn-wrongbook">📋 错题本</a>
            </div>
        </div>
        
        <div class="card">
            <h2>🔍 搜题</h2>
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="输入关键词搜索题目..." autocomplete="off">
                <button class="search-btn" onclick="doSearch()">🔍 搜索</button>
            </div>
            <div class="search-filters" id="searchFilters">
                <button class="search-filter active" data-type="all" onclick="setSearchFilter(this)">全部</button>
                <button class="search-filter" data-type="单选题" onclick="setSearchFilter(this)">单选题</button>
                <button class="search-filter" data-type="多选题" onclick="setSearchFilter(this)">多选题</button>
                <button class="search-filter" data-type="判断题" onclick="setSearchFilter(this)">判断题</button>
            </div>
            <div class="search-info" id="searchInfo"></div>
            <div class="search-results" id="searchResults"></div>
        </div>
        
        <div class="card">
            <h2>📊 学习进度</h2>
            <div id="progress-area">
                <div class="loading">加载中...</div>
            </div>
        </div>
        
        <div class="user-id" style="text-align: center; margin-top: 15px;">
            <div class="user-actions" style="justify-content: center; gap: 10px;">
                <a href="export.php" class="export-link" id="exportLink">📥 导出</a>
                <a href="import.php" class="import-link">📤 导入</a>
            </div>
        </div>

    </div>

    <script>
        // 获取用户ID（优先用本地存储的，没有才从服务器获取）
        let userId = localStorage.getItem('safety_user_id');
        let allQuestions = []; // 缓存全部题目
        let searchFilter = 'all';
        
        function init() {
            console.log('[init] Starting...');
            console.log('[init] userId from localStorage:', userId);
            
            if (userId) {
                console.log('[init] Using existing userId:', userId);
                document.getElementById('user-id').textContent = userId;
                document.getElementById('exportLink').href = 'export.php?user_id=' + encodeURIComponent(userId);
                loadProgress(userId);
            } else {
                console.log('[init] No userId, fetching from API...');
                fetch('api/user.php')
                    .then(r => {
                        console.log('[init] API response status:', r.status);
                        return r.json();
                    })
                    .then(data => {
                        console.log('[init] API response data:', data);
                        userId = data.user_id;
                        localStorage.setItem('safety_user_id', userId);
                        document.getElementById('user-id').textContent = userId;
                        document.getElementById('exportLink').href = 'export.php?user_id=' + encodeURIComponent(userId);
                        loadProgress(userId);
                    })
                    .catch(err => {
                        console.error('[init] API fetch error:', err);
                        document.getElementById('user-id').textContent = '加载失败';
                        document.getElementById('progress-area').innerHTML = 
                            '<div class="error">无法连接服务器，请检查：<br>1. PHP服务器是否运行<br>2. 访问 api/user.php 查看是否返回JSON</div>';
                    });
            }
            // 预加载题目
            loadAllQuestions();
        }
        
        async function loadAllQuestions() {
            try {
                const res = await fetch('api/questions.php');
                const data = await res.json();
                if (data.success) allQuestions = data.data;
            } catch (e) {}
        }
        
        init();
        
        // === 搜题功能 ===
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
        
        function setSearchFilter(btn) {
            document.querySelectorAll('.search-filter').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            searchFilter = btn.dataset.type;
            doSearch();
        }
        
        function doSearch() {
            const keyword = searchInput.value.trim();
            const infoEl = document.getElementById('searchInfo');
            const resultsEl = document.getElementById('searchResults');
            
            if (!keyword) {
                infoEl.textContent = '';
                resultsEl.innerHTML = '';
                return;
            }
            
            if (allQuestions.length === 0) {
                infoEl.textContent = '题目加载中，请稍后再试...';
                return;
            }
            
            const lower = keyword.toLowerCase();
            let results = allQuestions.filter(q => {
                if (searchFilter !== 'all' && q.type !== searchFilter) return false;
                // 搜索范围：题目、选项、答案
                if (q.question.toLowerCase().includes(lower)) return true;
                const optText = Object.values(q.options).join(' ').toLowerCase();
                if (optText.includes(lower)) return true;
                const tipsText = Array.isArray(q.tips) ? q.tips.join(' ') : (q.tips || '');
                const memText = Array.isArray(q.memory_points) ? q.memory_points.join(' ') : (q.memory_points || '');
                if (tipsText.toLowerCase().includes(lower)) return true;
                if (memText.toLowerCase().includes(lower)) return true;
                return false;
            });
            
            infoEl.textContent = '找到 ' + results.length + ' 道相关题目';
            
            if (results.length === 0) {
                resultsEl.innerHTML = '<div style="text-align:center;color:#999;padding:20px;">未找到相关题目</div>';
                return;
            }
            
            // 最多显示50条
            const show = results.slice(0, 50);
            let html = '';
            show.forEach(q => {
                const hl = highlightText(q.question, keyword);
                let answerText = q.answer;
                if (q.type === '判断题') answerText = q.answer;
                else if (q.type === '单选题') answerText = q.answer + '. ' + (q.options[q.answer] || '');
                else if (q.type === '多选题') {
                    answerText = q.answer.split('').join('、');
                }
                
                let optionsHtml = '';
                Object.keys(q.options).forEach(key => {
                    const isCorrect = q.type === '多选题' ? q.answer.includes(key) : key === q.answer;
                    optionsHtml += '<div class="search-option' + (isCorrect ? ' correct' : '') + '">' + key + '. ' + highlightText(q.options[key], keyword) + '</div>';
                });
                
                let tipsHtml = '';
                const tipsStr = Array.isArray(q.tips) ? q.tips.join('；') : (q.tips || '');
                const memStr = Array.isArray(q.memory_points) ? q.memory_points.join('；') : (q.memory_points || '');
                if (tipsStr) tipsHtml += '<div class="search-item-tips tip">💡 ' + highlightText(tipsStr, keyword) + '</div>';
                if (memStr) tipsHtml += '<div class="search-item-tips memory">🧠 ' + highlightText(memStr, keyword) + '</div>';
                
                html += '<div class="search-item" onclick="toggleOptions(this)">' +
                    '<div class="search-item-head">' +
                        '<span class="search-item-num">第' + q.num + ' 题</span>' +
                        '<span class="search-item-type type-' + q.type + '">' + q.type + '</span>' +
                        '<span class="search-item-answer">答案：' + answerText + '</span>' +
                    '</div>' +
                    '<div class="search-item-q">' + hl + '</div>' +
                    '<div class="search-item-options">' + optionsHtml + tipsHtml + '</div>' +
                '</div>';
            });
            
            if (results.length > 50) {
                html += '<div style="text-align:center;color:#999;padding:10px;">仅显示前50条，共' + results.length + ' 条结果</div>';
            }
            resultsEl.innerHTML = html;
        }
        
        function highlightText(text, keyword) {
            if (!keyword) return text;
            const regex = new RegExp('(' + keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
            return text.replace(regex, '<mark>$1</mark>');
        }
        
        function toggleOptions(item) {
            const opts = item.querySelector('.search-item-options');
            opts.classList.toggle('show');
        }
        
        function loadProgress(userId) {
            console.log('[loadProgress] Loading progress for:', userId);
            fetch('api/progress.php?action=getAll&user_id=' + userId)
                .then(r => {
                    console.log('[loadProgress] API response status:', r.status);
                    return r.json();
                })
                .then(data => {
                    console.log('[loadProgress] API response data:', data);
                    if (data.success && data.data) {
                        renderProgress(data.data);
                        // 同步本地备份
                        localStorage.setItem('safety_all_progress', JSON.stringify(data.data));
                    } else {
                        console.error('[loadProgress] API returned error:', data);
                        // 尝试本地备份
                        const local = tryLoadLocalProgress();
                        if (local) {
                            renderProgress(local);
                        } else {
                            document.getElementById('progress-area').innerHTML = 
                                '<div class="error">加载进度失败：' + (data.error || '未知错误') + '</div>';
                        }
                    }
                })
                .catch(err => {
                    console.error('[loadProgress] Fetch error:', err);
                    // 尝试本地备份
                    const local = tryLoadLocalProgress();
                    if (local) {
                        renderProgress(local);
                    } else {
                        document.getElementById('progress-area').innerHTML = 
                            '<div class="error">加载进度失败，请确保服务器正在运行<br>错误详情：' + err.message + '</div>';
                    }
                });
        }

        function tryLoadLocalProgress() {
            try {
                // 优先从 all_progress 读取（由 getAll 同步的完整数据）
                let saved = localStorage.getItem('safety_all_progress');
                if (saved) {
                    const p = JSON.parse(saved);
                    if (p.study || p.voice || p.exam || p.wrongbook) return p;
                }
                // 合并各页面本地备份
                const result = {};
                ['study', 'voice', 'exam'].forEach(type => {
                    const local = localStorage.getItem('safety_' + type + '_progress');
                    if (local) {
                        try { result[type] = JSON.parse(local); } catch(e) {}
                    }
                });
                if (result.study || result.voice || result.exam) return result;
            } catch (e) {}
            return null;
        }
        
        function renderProgress(progress) {
            const names = { study: '智能学习', voice: '语音朗读', exam: '模拟考试' };
            const totalQuestions = 600;
            let html = '';
            let hasProgress = false;

            // 智能学习进度
            const studyData = progress.study;
            if (studyData) {
                hasProgress = true;
                const completed = studyData.completed || [];
                const currentIndex = studyData.currentIndex || 0;
                const currentRound = studyData.currentRound || 1;
                const progressPercent = Math.round((completed.length / totalQuestions) * 100);
                html += `
                    <div class="progress-item">
                        <div class="progress-title">📖 智能学习 ${currentRound > 1 ? `（第 ${currentRound} 轮）` : ''}</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${progressPercent}%"></div>
                        </div>
                        <div class="progress-info">
                            <span>当前第${currentIndex + 1} 题 / 共${totalQuestions} 题</span>
                            <span>已完成${completed.length} 题</span>
                        </div>
                    </div>`;
            }

            // 语音朗读进度
            const voiceData = progress.voice;
            if (voiceData) {
                hasProgress = true;
                const completed = voiceData.completed || [];
                const currentIndex = voiceData.currentIndex || 0;
                const progressPercent = Math.round((completed.length / totalQuestions) * 100);
                html += `
                    <div class="progress-item">
                        <div class="progress-title">🎧 语音朗读</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${progressPercent}%"></div>
                        </div>
                        <div class="progress-info">
                            <span>当前第${currentIndex + 1} 题 / 共${totalQuestions} 题</span>
                            <span>已完成${completed.length} 题</span>
                        </div>
                    </div>`;
            }

            // 模拟考试进度
            const examData = progress.exam;
            if (examData && examData.lastScore !== undefined) {
                hasProgress = true;
                const scoreClass = examData.passed ? 'color: #4CAF50' : 'color: #f44336';
                html += `
                    <div class="progress-item">
                        <div class="progress-title">✍️ 模拟考试（最近一次）</div>
                        <div style="font-size: 1.8rem; font-weight: 700; ${scoreClass}">${examData.lastScore} 分</div>
                        <div class="progress-info">
                            <span>正确 ${examData.lastCorrect || 0} 题 / 错误 ${examData.lastWrong || 0} 题</span>
                            <span>${examData.passed ? '✅ 通过' : '❌ 未通过'}</span>
                        </div>
                    </div>`;
            }

            // 错题本进度
            const wrongbookData = progress.wrongbook;
            if (wrongbookData) {
                // 统计所有证书下的错题
                let total = 0, mastered = 0, notMastered = 0;
                Object.keys(wrongbookData).forEach(cert => {
                    const certQuestions = wrongbookData[cert];
                    Object.keys(certQuestions).forEach(qNum => {
                        total++;
                        if (certQuestions[qNum].mastered) {
                            mastered++;
                        } else {
                            notMastered++;
                        }
                    });
                });
                if (total > 0) {
                    hasProgress = true;
                    const progressPercent = Math.round((mastered / total) * 100);
                    html += `
                        <div class="progress-item">
                            <div class="progress-title">📋 错题本</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${progressPercent}%"></div>
                            </div>
                            <div class="progress-info">
                                <span>共${total} 道错题</span>
                                <span>已掌握${mastered} 道 / 未掌握${notMastered} 道</span>
                            </div>
                        </div>`;
                }
            }

            if (!hasProgress) {
                html = '<p style="text-align:center;color:#666;">开始学习后这里会显示进度</p>';
            }

            document.getElementById('progress-area').innerHTML = html;
        }
    
        // 修改用户名
        function editUserId() {
            const display = document.getElementById('user-id').parentElement;
            const currentName = userId || '我的账号';
            display.innerHTML = '<input type="text" class="user-input" id="userInput" value="' + currentName + '" maxlength="20" placeholder="输入用户名...">' +
                '<button class="btn-save" onclick="saveUserId()">保存</button>' +
                '<button class="btn-cancel" onclick="cancelEdit()">取消</button>';
            document.getElementById('userInput').focus();
            document.getElementById('userInput').select();
        }
        
        function saveUserId() {
            const input = document.getElementById('userInput');
            const newId = input.value.trim();
            if (!newId) return;
            userId = newId;
            localStorage.setItem('safety_user_id', userId);
            location.reload();
        }
        
        function cancelEdit() {
            document.querySelector('.user-display').innerHTML = '用户: <span id="user-id">' + userId + '</span><button class="btn-edit" onclick="editUserId()" title="修改用户名">✏️</button>';
        }
    </script>
</body>
</html>
