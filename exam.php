<?php
// exam.php - 模拟考试
session_start();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>模拟考试 - 安管人员考试</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            padding: 15px;
        }
        .container { max-width: 900px; margin: 0 auto; }
        .header {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
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
        .stats-bar {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
        }
        .stat-box { text-align: center; }
        .stat-num { font-size: 28px; font-weight: bold; }
        .stat-label { font-size: 12px; opacity: 0.8; }
        .stat-num.correct { color: #4CAF50; }
        .stat-num.wrong { color: #f44336; }
        #timer { color: #fff; }
        #timer.warning { color: #ff9800; }
        #timer.danger { color: #f44336; }
        .question-card {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .q-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .q-num { font-size: 24px; font-weight: bold; color: #1a1a2e; }
        .q-type {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
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
            border-left: 4px solid #0f3460;
        }
        .q-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
        .q-option {
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.2s;
        }
        .q-option:hover { border-color: #0f3460; background: #f0f0f0; }
        .q-option.selected {
            border-color: #667eea;
            background: #e8eaf6;
            font-weight: bold;
        }
        .q-option.correct {
            border-color: #4CAF50;
            background: #e8f5e9;
            font-weight: bold;
        }
        .q-option.wrong {
            border-color: #f44336;
            background: #ffebee;
        }
        .q-option.disabled { cursor: default; }
        .q-answer {
            text-align: center;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 12px;
            font-size: 18px;
            color: #1976D2;
            margin-bottom: 20px;
            display: none;
        }
        .controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
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
        .btn-success { background: #4CAF50; color: #fff; }
        .btn-success:hover { background: #43A047; }
        .btn-warning { background: linear-gradient(135deg, #f7971e, #ffd200); color: #333; }
        .btn-warning:hover { transform: translateY(-2px); }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
        .review-banner {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: #fff;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .your-answer {
            text-align: center;
            padding: 15px;
            background: #ffebee;
            border-radius: 12px;
            color: #c62828;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .tip-box {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            font-size: 15px;
            line-height: 1.6;
        }
        .tip-box.tips { background: #fff3e0; color: #e65100; }
        .tip-box.memory { background: #e8f5e9; color: #2e7d32; }
        .result-card {
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .result-score {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .result-score .sub { font-size: 14px; color: #666; font-weight: normal; }
        .result-score.pass { color: #4CAF50; }
        .result-score.fail { color: #f44336; }
        .loading { text-align: center; padding: 50px; color: #666; }
        .cert-tabs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .cert-tabs .cert-btn {
            padding: 10px 20px;
            border: 2px solid #667eea;
            border-radius: 25px;
            background: #fff;
            color: #667eea;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }
        .cert-tabs .cert-btn:hover { background: #e8eaf6; }
        .cert-tabs .cert-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .exam-desc { font-size: 13px; color: #888; margin-top: 4px; text-align: center; }
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
    <div class="container">
        <div class="header" style="position: relative;">
            <a href="index.php" class="back-btn">← 返回</a>
            <h1>✍️ 模拟考试</h1>
            <p class="subtitle" id="examDesc">单选40题、多选10题、判断10题、简答3题无、案例2题无、满分150，90分合格</p>

        </div>

        <div class="cert-tabs" id="certTabs">
            <button class="cert-btn" data-cert="A" onclick="selectCert('A')">安全员A证</button>
            <button class="cert-btn" data-cert="B" onclick="selectCert('B')">安全员B证</button>
            <button class="cert-btn active" data-cert="C" onclick="selectCert('C')">安全员C证</button>
        </div>
        <div class="stats-bar">
            <div class="stat-box">
                <div class="stat-num" id="timer">--:--</div>
                <div class="stat-label">剩余时间</div>
            </div>
            <div class="stat-box">
                <div class="stat-num" id="currentNum">0</div>
                <div class="stat-label">当前题号</div>
            </div>
            <div class="stat-box">
                <div class="stat-num" id="totalNum">0</div>
                <div class="stat-label">总题数</div>
            </div>
            <div class="stat-box">
                <div class="stat-num correct" id="correctCount">0</div>
                <div class="stat-label">正确</div>
            </div>
            <div class="stat-box">
                <div class="stat-num wrong" id="wrongCount">0</div>
                <div class="stat-label">错误</div>
            </div>
        </div>
        <div class="controls" id="topControls">
            <button class="btn btn-secondary" onclick="startExam()">🔄 重新抽题</button>
            <button class="btn btn-warning" id="wrongbookBtn" onclick="toggleWrongbookMode()">📋 优先错题</button>
        </div>
        <div class="question-card" id="questionCard">
            <div class="loading">加载中...</div>
        </div>
        <div class="controls" id="bottomControls" style="display: none;">
            <button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>
            <button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>
            <button class="btn btn-success" onclick="submitExam()">📤 交卷</button>
        </div>
        <div class="result-card" id="resultCard" style="display: none;"></div>
        <div class="keyboard-hint">
            <kbd>←</kbd> 上一题 | <kbd>→</kbd> 下一题 | <kbd>1-9</kbd> 选择选项 | <kbd>Enter</kbd> 交卷
        </div>
    </div>
    <script>
        let allQuestions = [];
        let examQuestions = [];
        let currentIndex = 0;
        let answers = {};
        let submitted = false;
        let reviewMode = false;
        let reviewQuestions = [];
        let reviewIndex = 0;
        let currentCert = 'C';  // 默认C证
        let timerInterval = null;
        let timeLeft = 0;  // 秒
        let wrongbook = {};  // { cert: { num: { added_at, mastered, correct_count } } }
        let wrongbookMode = false;  // 是否优先错题模式
        let wrongbookLoaded = false;  // 错题本是否已加载

        const examConfigs = {
            'A': {
                label: '安全员A证考试',
                desc: '单选68题、多选16题、判断16题、满分100，60分合格',
                single: 68, multiple: 16, judge: 16, caseCount: 0, essayCount: 0,
                maxScore: 100, passScore: 60, duration: 90,
                scoring: { single: 1, multiple: 1, judge: 1, essay: 0, case: 0 }
            },
            'B': {
                label: '安全员B证考试',
                desc: '单选28题、多选24题、判断24题、案例6题无，满分100，60分合格',
                single: 28, multiple: 24, judge: 24, caseCount: 6, essayCount: 0,
                maxScore: 100, passScore: 60, duration: 90,
                scoring: { single: 1, multiple: 1.5, judge: 0.5, essay: 0, case: 4 }
            },
            'C': {
                label: '安全员C证考试',
                desc: '单选40题、多选10题、判断10题、简答3题无、案例2题无、满分150，90分合格',
                single: 40, multiple: 10, judge: 10, caseCount: 2, essayCount: 3,
                maxScore: 150, passScore: 90, duration: 90,
                scoring: { single: 1, multiple: 2, judge: 1, essay: 10, case: 20 }
            }
        };

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

        function selectCert(cert) {
            currentCert = cert;
            localStorage.setItem('safety_exam_cert', cert);
            document.querySelectorAll('.cert-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.cert === cert);
            });
            const cfg = examConfigs[cert];
            document.getElementById('examDesc').textContent = cfg.desc;
            document.getElementById('totalNum').textContent = cfg.single + cfg.multiple + cfg.judge + cfg.essayCount + cfg.caseCount;
        }

        async function loadQuestions() {
            try {
                const res = await fetch('api/questions.php');
                const data = await res.json();
                if (data.success) {
                    allQuestions = data.data;
                    // 加载上次考试进度
                    await loadProgress();
                    startExam();
                }
            } catch (e) {
                document.getElementById('questionCard').innerHTML = '<div class="loading">加载失败，请刷新重试</div>';
            }
        }

        async function loadProgress() {
            let waited = 0;
            while (!userId && waited < 3000) {
                await new Promise(r => setTimeout(r, 50));
                waited += 50;
            }
            if (!userId) return;
            let loaded = false;
            try {
                const res = await fetch('api/progress.php?action=get&type=exam&user_id=' + userId, { credentials: 'include' });
                const data = await res.json();
                if (data.success && data.data) {
                    const saved = (typeof data.data === 'string') ? JSON.parse(data.data) : data.data;
                    if (saved.lastScore !== undefined) {
                        document.getElementById('correctCount').textContent = saved.lastCorrect || 0;
                        document.getElementById('wrongCount').textContent = saved.lastWrong || 0;
                        loaded = true;
                    }
                }
            } catch (e) {
                console.warn('加载考试进度网络错误:', e);
            }
            if (!loaded) {
                try {
                    const local = JSON.parse(localStorage.getItem('safety_exam_progress'));
                    if (local && local.lastScore !== undefined) {
                        document.getElementById('correctCount').textContent = local.lastCorrect || 0;
                        document.getElementById('wrongCount').textContent = local.lastWrong || 0;
                    }
                } catch (e) {}
            }
        }

        async function saveProgress(examResult) {
            if (!userId) return;
            // 本地备份
            localStorage.setItem('safety_exam_progress', JSON.stringify(examResult));
            try {
                const res = await fetch('api/progress.php?user_id=' + userId, {
                    method: 'POST',
                    credentials: 'include',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=save&type=exam&data=' + encodeURIComponent(JSON.stringify(examResult))
                });
                const result = await res.json();
                if (!result.success) console.warn('考试进度保存失败:', result);
            } catch (e) {
                console.warn('考试进度保存网络错误:', e);
            }
        }

        async function loadWrongbook() {
            if (!userId) return;
            try {
                const res = await fetch('api/progress.php?action=getWrongbook&user_id=' + userId, { credentials: 'include' });
                const data = await res.json();
                if (data.success && data.data) {
                    wrongbook = data.data;
                    wrongbookLoaded = true;
                }
            } catch (e) {}
        }

        function toggleWrongbookMode() {
            wrongbookMode = !wrongbookMode;
            const btn = document.getElementById('wrongbookBtn');
            if (wrongbookMode) {
                btn.textContent = '📋 优先错题 (开)';
                btn.style.background = 'linear-gradient(135deg, #f7971e, #ffd200)';
                // 加载错题本数据
                loadWrongbook().then(() => {
                    const wbCount = wrongbook[currentCert] ? Object.keys(wrongbook[currentCert]).length : 0;
                    if (wbCount > 0) {
                        alert('已开启优先错题模式！当前' + currentCert + '证有 ' + wbCount + ' 道错题。');
                    } else {
                        alert('当前' + currentCert + '证暂无错题，将使用随机抽题。');
                    }
                });
            } else {
                btn.textContent = '📋 优先错题';
                btn.style.background = '';
            }
        }

        function startExam() {
            const cfg = examConfigs[currentCert];
            // 按题型分类抽题
            let singles = allQuestions.filter(q => q.type === '单选题');
            let multiples = allQuestions.filter(q => q.type === '多选题');
            let judges = allQuestions.filter(q => q.type === '判断题');
            
            // 随机打乱各类型题目
            singles = singles.sort(() => Math.random() - 0.5);
            multiples = multiples.sort(() => Math.random() - 0.5);
            judges = judges.sort(() => Math.random() - 0.5);
            
            let selectedSingles, selectedMultiples, selectedJudges;
            
            // 优先错题模式：从错题本中抽取部分题目
            if (wrongbookMode && wrongbook[currentCert]) {
                const wbNums = Object.keys(wrongbook[currentCert]).map(Number);
                const wbSingles = singles.filter(q => wbNums.includes(q.num));
                const wbMultiples = multiples.filter(q => wbNums.includes(q.num));
                const wbJudges = judges.filter(q => wbNums.includes(q.num));
                
                // 错题本题目占比约50%，其余随机
                const wrongbookRatio = 0.5;
                const wrongbookSingleCount = Math.min(wbSingles.length, Math.ceil(cfg.single * wrongbookRatio));
                const wrongbookMultipleCount = Math.min(wbMultiples.length, Math.ceil(cfg.multiple * wrongbookRatio));
                const wrongbookJudgeCount = Math.min(wbJudges.length, Math.ceil(cfg.judge * wrongbookRatio));
                
                // 剩余题目从随机池抽取
                const randomSingleCount = cfg.single - wrongbookSingleCount;
                const randomMultipleCount = cfg.multiple - wrongbookMultipleCount;
                const randomJudgeCount = cfg.judge - wrongbookJudgeCount;
                
                // 从各池中移除已选中的错题本题目
                const remainingSingles = singles.filter(q => !wbNums.includes(q.num) || wbSingles.includes(q));
                const remainingMultiples = multiples.filter(q => !wbNums.includes(q.num) || wbMultiples.includes(q));
                const remainingJudges = judges.filter(q => !wbNums.includes(q.num) || wbJudges.includes(q));
                
                // 重新打乱剩余题目
                const shuffledRemainingSingles = remainingSingles.sort(() => Math.random() - 0.5);
                const shuffledRemainingMultiples = remainingMultiples.sort(() => Math.random() - 0.5);
                const shuffledRemainingJudges = remainingJudges.sort(() => Math.random() - 0.5);
                
                selectedSingles = [...wbSingles.slice(0, wrongbookSingleCount), ...shuffledRemainingSingles.slice(0, randomSingleCount)];
                selectedMultiples = [...wbMultiples.slice(0, wrongbookMultipleCount), ...shuffledRemainingMultiples.slice(0, randomMultipleCount)];
                selectedJudges = [...wbJudges.slice(0, wrongbookJudgeCount), ...shuffledRemainingJudges.slice(0, randomJudgeCount)];
            } else {
                // 抽取各类型题目
                selectedSingles = singles.slice(0, cfg.single);
                selectedMultiples = multiples.slice(0, cfg.multiple);
                selectedJudges = judges.slice(0, cfg.judge);
            }
            
            // 案例题从剩余的多选题中抽取
            const caseStart = cfg.multiple;
            const cases = multiples.slice(caseStart, caseStart + cfg.caseCount);
            
            // 简答题从剩余的单选题中抽取
            const essayStart = cfg.single;
            const essays = singles.slice(essayStart, essayStart + cfg.essayCount);
            
            examQuestions = [
                ...selectedSingles,
                ...selectedMultiples,
                ...selectedJudges,
                ...essays,
                ...cases
            ];
            // 保持顺序：单选→多选→判断→案例，不随机打乱
            currentIndex = 0;
            answers = {};
            submitted = false;
            reviewMode = false;
            reviewQuestions = [];
            document.getElementById('totalNum').textContent = examQuestions.length;
            document.getElementById('currentNum').textContent = 1;
            document.getElementById('correctCount').textContent = 0;
            document.getElementById('wrongCount').textContent = 0;
            document.getElementById('topControls').style.display = 'flex';
            document.getElementById('bottomControls').style.display = 'flex';
            document.getElementById('resultCard').style.display = 'none';
            document.getElementById('bottomControls').innerHTML =
                '<button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>' +
                '<button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>' +
                '<button class="btn btn-success" onclick="submitExam()">📤 交卷</button>';
            showQuestion(0);
            startTimer(cfg.duration || 90);
        }

        function startTimer(minutes) {
            if (timerInterval) clearInterval(timerInterval);
            timeLeft = (minutes || 90) * 60;
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('时间到！系统将自动交卷。');
                    submitExam();
                }
                const timerEl = document.getElementById('timer');
                if (timeLeft <= 300) {
                    timerEl.className = 'stat-num danger';
                } else if (timeLeft <= 600) {
                    timerEl.className = 'stat-num warning';
                }
            }, 1000);
        }

        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        function updateTimerDisplay() {
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            document.getElementById('timer').textContent =
                String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }

        function showQuestion(index) {
            if (index >= examQuestions.length || index < 0) return;
            const q = examQuestions[index];
            const userAnswer = answers[q.num];

            let optionsHtml = '';
            const optionKeys = Object.keys(q.options);
            for (const key of optionKeys) {
                const text = q.options[key];
                let className = 'q-option';
                if (submitted) {
                    className += ' disabled';
                    const isCorrect = q.type === '多选题' ? q.answer.includes(key) : q.type === '判断题' ? q.options[key] === q.answer : key === q.answer;
                    const isSelected = userAnswer && (q.type === '多选题' ? userAnswer.includes(key) : q.type === '判断题' ? q.options[key] === userAnswer : userAnswer === key);
                    if (isCorrect) className += ' correct';
                    else if (isSelected && !isCorrect) className += ' wrong';
                } else if (userAnswer) {
                    if (q.type === '多选题') {
                        if (userAnswer.includes(key)) className += ' selected';
                    } else {
                        if (userAnswer === key) className += ' selected';
                    }
                }
                optionsHtml += '<div class="' + className + '" onclick="selectOption(\'' + key + '\')">' + key + '. ' + text + '</div>';
            }

            let answerHtml = '';
            if (submitted) {
                let answerText = q.answer;
                if (q.type === '多选题') {
                    answerText = q.answer.split('').join('、');
                } else if (q.type === '判断题') {
                    answerText = q.answer;
                } else {
                    answerText = q.answer + '. ' + (q.options[q.answer] || '');
                }
                answerHtml = '<div class="q-answer">答案：' + answerText + '</div>';
            }

            document.getElementById('questionCard').innerHTML =
                '<div class="q-header">' +
                    '<span class="q-num">第 ' + (index + 1) + ' 题</span>' +
                    '<span class="q-type">' + q.type + '</span>' +
                '</div>' +
                '<div class="q-text">' + q.question + '</div>' +
                '<div class="q-options">' + optionsHtml + '</div>' +
                answerHtml;

            currentIndex = index;
            document.getElementById('currentNum').textContent = index + 1;
        }

        function selectOption(key) {
            if (submitted) return;
            const q = examQuestions[currentIndex];
            if (q.type === '多选题') {
                if (!answers[q.num]) answers[q.num] = '';
                if (answers[q.num].includes(key)) {
                    answers[q.num] = answers[q.num].replace(key, '');
                } else {
                    answers[q.num] += key;
                }
            } else {
                answers[q.num] = key;
            }
            showQuestion(currentIndex);
        }

        function nextQuestion() {
            if (reviewMode) {
                if (reviewIndex < reviewQuestions.length - 1) showReviewQuestion(reviewIndex + 1);
                return;
            }
            if (currentIndex < examQuestions.length - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            }
        }

        function prevQuestion() {
            if (reviewMode) {
                if (reviewIndex > 0) showReviewQuestion(reviewIndex - 1);
                return;
            }
            if (currentIndex > 0) {
                currentIndex--;
                showQuestion(currentIndex);
            }
        }

        function reviewWrongAnswers() {
            reviewMode = true;
            reviewQuestions = [];
            reviewIndex = 0;
            for (const q of examQuestions) {
                const userAnswer = answers[q.num] || '';
                let isCorrect = false;
                if (q.type === '多选题') {
                    isCorrect = userAnswer.split('').sort().join('') === q.answer.split('').sort().join('');
                } else if (q.type === '判断题') {
                    isCorrect = q.options[userAnswer] === q.answer;
                } else {
                    isCorrect = userAnswer === q.answer;
                }
                if (!isCorrect) reviewQuestions.push(q);
            }
            document.getElementById('resultCard').style.display = 'none';
            document.getElementById('topControls').style.display = 'none';
            document.getElementById('bottomControls').style.display = 'flex';
            document.getElementById('bottomControls').innerHTML =
                '<button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>' +
                '<button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>' +
                '<button class="btn btn-danger" onclick="exitReview()">🚪 退出复盘</button>';
            showReviewQuestion(0);
        }

        function showReviewQuestion(index) {
            if (index >= reviewQuestions.length) return;
            const q = reviewQuestions[index];
            const userAnswer = answers[q.num] || '';

            let optionsHtml = '';
            const optionKeys = Object.keys(q.options);
            for (const key of optionKeys) {
                const text = q.options[key];
                let className = 'q-option disabled';
                const isCorrect = q.type === '多选题' ? q.answer.includes(key) : q.type === '判断题' ? q.options[key] === q.answer : key === q.answer;
                const isSelected = q.type === '多选题' ? userAnswer.includes(key) : q.type === '判断题' ? q.options[key] === userAnswer : userAnswer === key;
                if (isCorrect) className += ' correct';
                else if (isSelected && !isCorrect) className += ' wrong';
                optionsHtml += '<div class="' + className + '">' + key + '. ' + text + '</div>';
            }

            let answerText = q.answer;
            if (q.type === '多选题') answerText = q.answer.split('').join('、');
            else if (q.type === '判断题') answerText = q.answer;
            else answerText = q.answer + '. ' + (q.options[q.answer] || '');

            let userAnswerText = userAnswer || '未作答';
            if (q.type === '判断题') userAnswerText = userAnswer;
            else if (q.type === '多选题' && userAnswer) userAnswerText = userAnswer.split('').join('、');

            let tipsHtml = '';
            if (q.tips) tipsHtml += '<div class="tip-box tips">💡 技巧：' + q.tips + '</div>';
            if (q.memory_points) tipsHtml += '<div class="tip-box memory">🧠 记忆点：' + q.memory_points + '</div>';

            document.getElementById('questionCard').innerHTML =
                '<div class="review-banner">📋 错题复盘 ' + (index + 1) + ' / ' + reviewQuestions.length + '</div>' +
                '<div class="q-header">' +
                    '<span class="q-num">第 ' + (index + 1) + ' 题</span>' +
                    '<span class="q-type">' + q.type + '</span>' +
                '</div>' +
                '<div class="q-text">' + q.question + '</div>' +
                '<div class="q-options">' + optionsHtml + '</div>' +
                '<div class="q-answer" style="display:block;">答案：' + answerText + '</div>' +
                '<div class="your-answer">❌ 你的答案：' + userAnswerText + '</div>' +
                tipsHtml;

            reviewIndex = index;
        }

        function exitReview() {
            reviewMode = false;
            reviewQuestions = [];
            document.getElementById('resultCard').style.display = 'block';
            document.getElementById('topControls').style.display = 'flex';
            document.getElementById('bottomControls').style.display = 'none';
            // 恢复底部按钮
            document.getElementById('bottomControls').innerHTML =
                '<button class="btn btn-secondary" onclick="prevQuestion()">⬅️ 上一题</button>' +
                '<button class="btn btn-primary" onclick="nextQuestion()">➡️ 下一题</button>' +
                '<button class="btn btn-success" onclick="submitExam()">📤 交卷</button>';
            stopTimer();
        }


        // 根据题号区间计算分数
        function getQuestionScore(index, cert) {
            const cfg = examConfigs[cert];
            const s = cfg.scoring;

            // A证：全部1分
            if (cert === 'A') return 1;

            // B证：单选1-28(1分)，多选29-52(1.5分)，判断53-76(0.5分)，案例77-82(4分)
            if (cert === 'B') {
                if (index < 28) return s.single;
                if (index < 52) return s.multiple;
                if (index < 76) return s.judge;
                return s.case;
            }

            // C证：单选1-40(1分)，多选41-50(2分)，判断51-60(1分)，简答61-63(10分)，案例64-65(20分)
            if (cert === 'C') {
                if (index < 40) return s.single;
                if (index < 50) return s.multiple;
                if (index < 60) return s.judge;
                if (index < 63) return s.essay;
                return s.case;
            }

            return 1;
        }

        function submitExam() {
            if (!confirm('确定交卷？')) return;
            submitted = true;
            stopTimer();

            const cfg = examConfigs[currentCert];
            let actualScore = 0;
            let correct = 0;
            let wrong = 0;

            for (let i = 0; i < examQuestions.length; i++) {
                const q = examQuestions[i];
                const userAnswer = answers[q.num] || '';
                let isCorrect = false;

                if (q.type === '多选题') {
                    const sortedUser = userAnswer.split('').sort().join('');
                    const sortedCorrect = q.answer.split('').sort().join('');
                    isCorrect = sortedUser === sortedCorrect;
                } else {
                    isCorrect = userAnswer === q.answer;
                }

                if (isCorrect) {
                    correct++;
                    // 按题号区间计算分数
                    const score = getQuestionScore(i, currentCert);
                    actualScore += score;
                } else {
                    wrong++;
                }
            }

            // 直接显示实际得分
            const showScore = Math.round(actualScore * 10) / 10;
            const showTotal = cfg.maxScore;
            const passed = showScore >= cfg.passScore;
            const passLine = passed ? '🎉 恭喜通过！' : '💪 继续加油！';

            document.getElementById('correctCount').textContent = correct;
            document.getElementById('wrongCount').textContent = wrong;

            document.getElementById('resultCard').style.display = 'block';
            document.getElementById('resultCard').innerHTML =
                '<div class="result-score ' + (passed ? 'pass' : 'fail') + '">' + showScore + '<span class="sub"> / ' + showTotal + '分</span></div>' +
                '<p>正确 ' + correct + ' 题，错误 ' + wrong + ' 题</p>' +
                '<p style="margin-top: 10px; color: #666;">合格线：' + cfg.passScore + '分 ' + passLine + '</p>' +
                (wrong > 0 ? '<div style="margin-top:15px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap;"><button class="btn btn-warning" onclick="reviewWrongAnswers()">📋 复盘错题 (' + wrong + '题)</button><button class="btn btn-primary" onclick="startExam()">再做一套</button></div>'
                    : '<button class="btn btn-primary" style="margin-top: 20px;" onclick="startExam()">再做一套</button>');

            document.getElementById('bottomControls').style.display = 'none';
            showQuestion(currentIndex);

            saveProgress({
                lastScore: showScore,
                lastCorrect: correct,
                lastWrong: wrong,
                lastTotal: examQuestions.length,
                lastCert: currentCert,
                lastDate: new Date().toISOString(),
                passed: passed
            });

            // 更新错题本：错题加入，对题移除
            updateWrongbookAfterExam();
        }

        async function updateWrongbookAfterExam() {
            if (!userId) return;
            for (let i = 0; i < examQuestions.length; i++) {
                const q = examQuestions[i];
                const userAnswer = answers[q.num] || '';
                let isCorrect = false;
                if (q.type === '多选题') {
                    const sortedUser = userAnswer.split('').sort().join('');
                    const sortedCorrect = q.answer.split('').sort().join('');
                    isCorrect = sortedUser === sortedCorrect;
                } else {
                    isCorrect = userAnswer === q.answer;
                }
                try {
                    const fd = new FormData();
                    fd.append('cert', currentCert);
                    fd.append('question_num', q.num);
                    if (isCorrect) {
                        // 答对了，从错题本移除
                        await fetch('api/progress.php?action=removeWrong&user_id=' + userId, {
                            method: 'POST',
                            credentials: 'include',
                            body: fd
                        });
                    } else {
                        // 答错了，加入错题本
                        await fetch('api/progress.php?action=addWrong&user_id=' + userId, {
                            method: 'POST',
                            credentials: 'include',
                            body: fd
                        });
                    }
                } catch (e) {}
            }
            // 重新加载错题本数据
            await loadWrongbook();
        }

        document.addEventListener('keydown', e => {
            if (submitted) return;
            if (e.key === 'ArrowLeft') prevQuestion();
            if (e.key === 'ArrowRight') nextQuestion();
            // 数字键快速选择
            if (e.key >= '1' && e.key <= '4') {
                const keys = ['A', 'B', 'C', 'D'];
                selectOption(keys[parseInt(e.key) - 1]);
            }
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
            if (submitted) return;
            if (diffX < 0) nextQuestion();
            else prevQuestion();
        }, { passive: true });

        // 恢复上次证书选择
        const savedCert = localStorage.getItem('safety_exam_cert');
        if (savedCert && examConfigs[savedCert]) selectCert(savedCert);

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
