<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Deploy | thonlay.store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .deploy-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 36px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            text-align: center;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        p.subtitle {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        input[type="password"] {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            color: #0f172a;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: all 0.15s ease;
        }

        input[type="password"]:focus {
            border-color: #0f172a;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        }

        .btn-deploy {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease;
        }

        .btn-deploy:hover:not(:disabled) {
            background: #1e293b;
        }

        .btn-deploy:active:not(:disabled) {
            transform: scale(0.99);
        }

        .btn-deploy:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Progress Area */
        .progress-box {
            display: none;
            margin-top: 24px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 10px;
        }

        .progress-bar-bg {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: #0f172a;
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .status-badge {
            display: none;
            margin-top: 20px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

    <div class="deploy-container">
        <h1>Deploy App</h1>
        <p class="subtitle">Enter password to update server</p>

        <form id="deployForm">
            <div class="form-group">
                <input type="password" id="password" placeholder="Password" required autofocus>
            </div>
            <button type="submit" class="btn-deploy" id="deployBtn">Deploy</button>
        </form>

        <div class="progress-box" id="progressBox">
            <div class="progress-header">
                <span id="progressStatus">Deploying...</span>
                <span id="progressPercent">0%</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" id="progressBar"></div>
            </div>
        </div>

        <div class="status-badge" id="statusBadge"></div>
    </div>

    <script>
        document.getElementById('deployForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const deployBtn = document.getElementById('deployBtn');
            const progressBox = document.getElementById('progressBox');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const progressStatus = document.getElementById('progressStatus');
            const statusBadge = document.getElementById('statusBadge');

            statusBadge.style.display = 'none';
            progressBox.style.display = 'block';
            deployBtn.disabled = true;

            let currentProgress = 0;
            progressBar.style.width = '0%';
            progressPercent.textContent = '0%';
            progressStatus.textContent = 'Deploying...';

            // Smooth percentage increment while server processes
            const interval = setInterval(() => {
                if (currentProgress < 90) {
                    currentProgress += Math.floor(Math.random() * 8) + 4;
                    if (currentProgress > 90) currentProgress = 90;
                    progressBar.style.width = currentProgress + '%';
                    progressPercent.textContent = currentProgress + '%';
                }
            }, 300);

            try {
                const response = await fetch('/deploy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();
                clearInterval(interval);

                if (response.ok && data.success) {
                    progressBar.style.width = '100%';
                    progressPercent.textContent = '100%';
                    progressStatus.textContent = 'Complete';

                    setTimeout(() => {
                        statusBadge.className = 'status-badge status-success';
                        statusBadge.textContent = '✓ Deployed Successfully';
                        statusBadge.style.display = 'block';
                    }, 400);
                } else {
                    clearInterval(interval);
                    progressBar.style.width = '0%';
                    statusBadge.className = 'status-badge status-error';
                    statusBadge.textContent = '✕ ' + (data.message || 'Deployment Failed');
                    statusBadge.style.display = 'block';
                }
            } catch (err) {
                clearInterval(interval);
                progressBar.style.width = '0%';
                statusBadge.className = 'status-badge status-error';
                statusBadge.textContent = '✕ Connection Error';
                statusBadge.style.display = 'block';
            } finally {
                deployBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
