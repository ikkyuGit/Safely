<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>导入学习数据 - 安管人员考试</title>
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
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        .upload-area.dragover {
            border-color: #667eea;
            background: #e8eaf6;
        }
        .upload-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .upload-text {
            color: #666;
            margin-bottom: 10px;
        }
        .upload-hint {
            font-size: 0.85rem;
            color: #999;
        }
        #fileInput {
            display: none;
        }
        .file-name {
            margin-top: 15px;
            padding: 10px;
            background: #e8eaf6;
            border-radius: 8px;
            color: #667eea;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }
        .info-list {
            list-style: none;
            color: #666;
        }
        .info-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-list li:last-child {
            border-bottom: none;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📤 导入学习数据</h1>
            <p>从备份文件恢复学习进度</p>
        </div>

        <?php
        $msg = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['importFile'])) {
            $file = $_FILES['importFile'];
            
            if ($file['error'] === 0 && $file['type'] === 'application/json') {
                $content = file_get_contents($file['tmp_name']);
                $data = json_decode($content, true);
                
                if ($data !== null) {
                    file_put_contents('study_data.json', $content);
                    $msg = "✅ 导入成功！学习数据已恢复";
                    $msgType = "success";
                } else {
                    $msg = "❌ JSON 格式错误，无法导入";
                    $msgType = "error";
                }
            } else {
                $msg = "❌ 请上传合法的 JSON 备份文件";
                $msgType = "error";
            }
        }
        ?>

        <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msg ?>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>📋 导入说明</h2>
            <ul class="info-list">
                <li>✅ 支持导入之前导出的 JSON 格式备份文件</li>
                <li>✅ 错题本会智能合并，不会重复添加</li>
                <li>✅ 学习进度会恢复到导入时的状态</li>
                <li>⚠️ 导入后当前进度会被覆盖，请谨慎操作</li>
            </ul>
        </div>
        
        <div class="card">
            <h2>📁 选择备份文件</h2>
            <form method="POST" enctype="multipart/form-data" id="importForm">
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📂</div>
                    <div class="upload-text">点击或拖拽文件到此处</div>
                    <div class="upload-hint">支持 .json 格式的学习进度备份文件</div>
                    <div class="file-name" id="fileName" style="display: none;"></div>
                </div>
                <input type="file" name="importFile" id="fileInput" accept=".json,application/json">
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        📥 开始导入
                    </button>
                    <a href="index.php" class="btn btn-secondary">取消</a>
                </div>
            </form>
        </div>
        
        <div style="text-align: center;">
            <a href="index.php" class="back-btn">← 返回首页</a>
        </div>
    </div>
    
    <script>
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const submitBtn = document.getElementById('submitBtn');
        
        uploadArea.addEventListener('click', () => fileInput.click());
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
        
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                handleFileSelect(fileInput.files[0]);
            }
        });
        
        function handleFileSelect(file) {
            if (!file.name.endsWith('.json')) {
                alert('请选择 JSON 格式的备份文件');
                fileInput.value = '';
                return;
            }
            fileName.textContent = '已选择: ' + file.name;
            fileName.style.display = 'block';
            submitBtn.disabled = false;
        }
    </script>
</body>
</html>
