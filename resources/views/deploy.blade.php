<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>One-Click Web Deploy | thonlay.store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.75);
            --border-color: rgba(255, 255, 255, 0.1);
            --accent-glow: rgba(99, 102, 241, 0.25);
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --terminal-bg: #030712;
            --success: #10b981;
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .deploy-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            width: 100%;
            max-width: 680px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 40px var(--accent-glow);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #818cf8;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--success);
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        p.subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="password"], input[type="text"] {
            width: 100%;
            background: rgba(31, 41, 55, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 14px 18px;
            color: #ffffff;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.2s ease;
        }

        input[type="password"]:focus, input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            background: rgba(31, 41, 55, 0.9);
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.85rem;
            padding: 4px 8px;
        }

        .btn-deploy {
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        .btn-deploy:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.6);
        }

        .btn-deploy:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .terminal-container {
            margin-top: 24px;
            display: none;
        }

        .terminal-header {
            background: #111827;
            border: 1px solid var(--border-color);
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .terminal-dots {
            display: flex;
            gap: 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-red { background: #ef4444; }
        .dot-yellow { background: #f59e0b; }
        .dot-green { background: #10b981; }

        .terminal-body {
            background: var(--terminal-bg);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            color: #38bdf8;
            max-height: 280px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
            line-height: 1.5;
        }

        .status-message {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            display: none;
            font-weight: 500;
        }

        .status-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .status-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="deploy-card">
        <div class="header">
            <div class="badge">
                <span class="badge-dot"></span> Server Ready (thonlay.store)
            </div>
            <h1>Web Deployment Hub</h1>
            <p class="subtitle">Enter the deployment password to sync and update the production server automatically.</p>
        </div>

        <form id="deployForm">
            @csrf
            <div class="form-group">
                <label for="password">Deployment Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter deployment password..." required autofocus>
                    <button type="button" class="toggle-pw" onclick="togglePassword()">Show</button>
                </div>
            </div>

            <button type="submit" class="btn-deploy" id="deployBtn">
                <span id="btnIcon">🚀</span>
                <span id="btnText">Deploy Production Update</span>
                <div class="spinner" id="btnSpinner"></div>
            </button>
        </form>

        <div id="statusMsg" class="status-message"></div>

        <div class="terminal-container" id="terminalBox">
            <div class="terminal-header">
                <div class="terminal-dots">
                    <span class="dot dot-red"></span>
                    <span class="dot dot-yellow"></span>
                    <span class="dot dot-green"></span>
                </div>
                <span>Server Log Output</span>
            </div>
            <div class="terminal-body" id="terminalLog">Initializing deployment sequence...</div>
        </div>

        <div class="footer-note">
            Junior Project Nak &bull; Automatic Git Pull, Assets Build & Server Cache Update
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.toggle-pw');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = 'Hide';
            } else {
                input.type = 'password';
                btn.textContent = 'Show';
            }
        }

        document.getElementById('deployForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const deployBtn = document.getElementById('deployBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnSpinner = document.getElementById('btnSpinner');
            const statusMsg = document.getElementById('statusMsg');
            const terminalBox = document.getElementById('terminalBox');
            const terminalLog = document.getElementById('terminalLog');

            // Reset states
            statusMsg.style.display = 'none';
            terminalBox.style.display = 'block';
            terminalLog.textContent = '⏳ Executing deployment pipeline...\n[1/5] Pulling latest git repository...\n[2/5] Checking composer packages...\n[3/5] Building frontend assets...\n[4/5] Executing database migrations...\n[5/5] Refreshing caches & restarting web services...\n\nPlease wait a few seconds...\n';

            deployBtn.disabled = true;
            btnText.textContent = 'Deploying...';
            btnIcon.style.display = 'none';
            btnSpinner.style.display = 'block';

            try {
                const response = await fetch('{{ route("deploy.run") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password: password })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    statusMsg.className = 'status-message status-success';
                    statusMsg.textContent = '✅ Success! Server deployed and reloaded.';
                    statusMsg.style.display = 'block';
                    terminalLog.textContent += '\n================ DEPLOYMENT LOG ================\n' + data.output;
                } else {
                    statusMsg.className = 'status-message status-error';
                    statusMsg.textContent = '❌ ' + (data.message || 'Deployment failed.');
                    statusMsg.style.display = 'block';
                    if (data.output) {
                        terminalLog.textContent += '\n================ ERROR LOG ================\n' + data.output;
                    }
                }
            } catch (err) {
                statusMsg.className = 'status-message status-error';
                statusMsg.textContent = '❌ Network error or server timeout. Please check your connection.';
                statusMsg.style.display = 'block';
                terminalLog.textContent += '\n================ ERROR ================\n' + err.message;
            } finally {
                deployBtn.disabled = false;
                btnText.textContent = 'Deploy Production Update';
                btnIcon.style.display = 'inline';
                btnSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>
