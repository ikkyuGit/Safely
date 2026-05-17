# 🛡️ 安管人员考试学习系统

建筑施工企业**安管人员（A/B/C证）** 考试学习平台。纯 PHP + JSON 文件存储，无需数据库，即开即用。

---

## 📋 功能概览

### 主页面 (`index.php`)
- 四大功能入口：智能学习、模拟考试、AI 语音学习、错题本
- 学习进度面板：实时显示各模块已完成/总进度，滚动进度条
- 首页全局搜题：按题目编号/关键词搜索，支持题目类型筛选
- 用户名自定义编辑

### 📖 智能学习 (`study.php`)
- **按轮次**顺序刷题（共 600 道题，选择/判断/多选）
- 自动进度记忆：当前题号、已完成列表、轮次
- 答题后即时显示答案与解析（tips + memory_points）
- 一键加入/移出错题本
- 多轮复习：完成一轮后自动进入下一轮

### ✍️ 模拟考试 (`exam.php`)
- **计时考试**：共 100 题（单选 50 + 判断 30 + 多选 20），限时 60 分钟
- 题目随机打乱
- 提交后自动计分（≥70 分为通过）
- 错题一键批量导入错题本
- 查看详细答题报告

### 🎧 AI 语音学习 (`voice.php`)
- **TTS 语音朗读**题目与答案，适合通勤/睡前学习
- iOS 优化：meta 标签防休眠、屏幕常亮提醒
- 上一题/下一题/暂停/继续控制
- 朗读完成自动前进
- 进度独立追踪

### 📋 错题本 (`wrongbook.php`)
- 按证书分类管理错题
- 支持按题型/关键字筛选
- 标记「已掌握」/「未掌握」
- 清空某个证书或全部错题
- 掌握进度统计

### 📤 数据导入导出 (`import.php` / `export.php`)
- 导出为 JSON 或 CSV 格式
- 导入 JSON 备份恢复学习进度
- 错题本智能合并（不重复添加）

---

## 🗂️ 目录结构

```
safety/
├── index.php              # 首页（功能入口 + 搜题 + 进度展示）
├── study.php              # 智能学习
├── exam.php               # 模拟考试
├── voice.php              # AI 语音学习
├── wrongbook.php          # 错题本
├── import.php             # 数据导入
├── export.php             # 数据导出
├── README.md              # 本文件
│
├── api/
│   ├── config.php         # 配置（进度文件路径、题库路径）
│   ├── progress.php       # 进度 CRUD API
│   ├── questions.php      # 题库读取 API
│   └── user.php           # 用户 ID 获取/创建
│
├── data/
│   ├── progress.json      # 📌 统一进度数据存储（所有用户）
│   └── questions.json     # 题库数据（600 道题）
│
└── safety5.14.zip         # 原始备份压缩包
```

---

## ⚙️ 部署说明

### 环境要求

| 要求 | 最低版本 |
|------|---------|
| PHP | 7.4+（推荐 8.0+） |
| Web 服务器 | Apache / Nginx |
| 文件权限 | `data/` 目录需 777 可读写 |

### 部署步骤

#### 1️⃣ Apache 部署（推荐）

```
# 将整个 safety/ 目录放到 Web 根目录
cp -r safety/ /var/www/html/safety/

# 设置 data/ 目录可写
chmod 777 /var/www/html/safety/data/
chmod 777 /var/www/html/safety/data/progress.json
chmod 777 /var/www/html/safety/data/questions.json
```

访问 `http://你的IP/safety/` 即可使用。

#### 2️⃣ Nginx + PHP-FPM 部署

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/safety;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
# 设置可写权限
chmod 777 /path/to/safety/data/
chmod 777 /path/to/safety/data/progress.json
```

#### 3️⃣ PHP 内置服务器（开发/测试用）

```bash
cd /path/to/safety
php -S 0.0.0.0:8080
```

访问 `http://localhost:8080`

### 部署要点

- ✅ **无数据库依赖** — 所有数据存于 `data/progress.json`，结构简单
- ✅ **无需额外扩展** — 纯原生 PHP，无需 Composer
- ✅ **多用户自动识别** — 浏览器 Cookie 分配唯一用户 ID
- ⚠️ **data/ 目录必须可写** — 否则进度无法保存
- ⚠️ **生产环境用 Nginx/Apache** — PHP 内置服务器性能有限，仅用于测试

---

## 🔧 系统设计

### 数据存储

采用 **JSON 文件**替代数据库，核心文件：

- **`data/progress.json`** — 统一进度数据库，结构为 `{ 用户ID → { study, exam, voice, wrongbook } }`
- **`data/questions.json`** — 题库，含题型、选项、答案、解析（tips）、记忆口诀（memory_points）

所有 API 读写均使用**文件锁（flock）** 保证并发安全。

### API 接口

所有 API 通过 `api/progress.php` 提供的 `action` 参数操作：

| action | 说明 | 方法 |
|--------|------|------|
| `get` | 获取指定类型的进度 | GET |
| `getAll` | 获取所有进度 | GET |
| `save` | 保存进度数据 | POST |
| `recordAnswer` | 记录答题结果 | POST |
| `clear` | 清除进度 | POST |
| `addWrong` | 加入错题本 | POST |
| `removeWrong` | 移出错题本 | POST |
| `markMastered` | 标记已掌握 | POST |
| `getWrongbook` | 获取错题本 | GET |
| `clearWrongbook` | 清空错题本 | POST |
| `stats` | 获取统计 | GET |

### 用户识别

首次访问时自动生成 **16 位唯一用户 ID**（`u_` 前缀 + MD5 哈希），存入 Cookie 有效期 365 天，首页支持用户自定义别名。

---

## 🏗️ 技术栈

- **后端**：PHP 7.4+（无框架）
- **前端**：纯 HTML + CSS + JavaScript（无前端框架）
- **存储**：JSON 文件（`data/progress.json` + `data/questions.json`）
- **并发**：flock() 文件锁
- **TTS**：浏览器原生 SpeechSynthesis API

---

## 📝 许可

自用学习系统，无特殊许可限制。
