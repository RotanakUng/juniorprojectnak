<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Transcent Profumo · Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --accent-gold: #334155;
            --accent-gold-light: rgba(71, 85, 105, 0.08);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #f5f5f5;
            color: #111111;
        }

        /* ── Left Panel (Decorative) ── */
        .login-brand-panel {
            background: #111111;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        .login-brand-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -30%;
            width: 160%;
            height: 160%;
            background: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.06) 0%, transparent 50%),
                        radial-gradient(circle at 70% 70%, rgba(255,255,255,0.04) 0%, transparent 40%);
            pointer-events: none;
        }

        .login-brand-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 40px,
                rgba(255,255,255,0.015) 40px,
                rgba(255,255,255,0.015) 80px
            );
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .brand-logo {
            width: 88px;
            height: 88px;
            border-radius: 24px;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.15);
            display: grid;
            place-items: center;
            font-size: 38px;
            font-weight: 900;
            color: #fff;
            margin: 0 auto 32px;
            backdrop-filter: blur(10px);
        }

        .brand-content h1 {
            font-size: 2.4rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .brand-content p {
            color: rgba(255,255,255,0.5);
            font-size: 1.05rem;
            line-height: 1.6;
            font-weight: 500;
        }

        .brand-features {
            margin-top: 48px;
            display: grid;
            gap: 20px;
            text-align: left;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            backdrop-filter: blur(4px);
        }

        .brand-feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,0.08);
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .brand-feature-text {
            display: grid;
            gap: 2px;
        }

        .brand-feature-text strong {
            color: #fff;
            font-size: 0.92rem;
            font-weight: 700;
        }

        .brand-feature-text span {
            color: rgba(255,255,255,0.4);
            font-size: 0.82rem;
            font-weight: 500;
        }

        .brand-footer {
            position: absolute;
            bottom: 32px;
            left: 0;
            right: 0;
            text-align: center;
            color: rgba(255,255,255,0.2);
            font-size: 0.82rem;
            font-weight: 500;
            z-index: 1;
        }

        /* ── Right Panel (Form) ── */
        .login-form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px;
            background: #fff;
        }

        .login-card {
            width: min(400px, 100%);
        }

        .login-header {
            margin-bottom: 36px;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .form-group {
            display: grid;
            gap: 8px;
            margin-bottom: 22px;
        }

        .form-group label {
            font-size: 0.88rem;
            color: #374151;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color .2s ease;
        }

        .input-wrapper .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: grid;
            place-items: center;
            border-radius: 6px;
            transition: all .2s ease;
        }

        .input-wrapper .toggle-password:hover {
            color: #111111;
            background: rgba(0,0,0,0.04);
        }

        .form-group input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            color: #111827;
            font-size: 15px;
            padding: 0 48px 0 48px;
            height: 54px;
            outline: none;
            font-family: var(--font);
            transition: all .2s ease;
        }

        .form-group input:focus {
            border-color: #111111;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .form-group input:focus + .input-icon,
        .form-group input:focus ~ .input-icon {
            color: #111111;
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #111111;
            cursor: pointer;
            border-radius: 4px;
        }

        .remember-row label {
            font-size: 0.88rem;
            color: #4b5563;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 54px;
            padding: 0 18px;
            border: none;
            border-radius: 14px;
            background: #111111;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            font-family: var(--font);
            transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.01em;
        }

        .btn-login:hover {
            background: #333333;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-login:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .btn-login svg {
            width: 18px;
            height: 18px;
        }

        .error-msg {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 24px;
            animation: shakeX 0.4s ease;
        }

        .error-msg span {
            font-size: 1.15rem;
        }

        @keyframes shakeX {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
            color: #cbd5e1;
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .login-footer {
            text-align: center;
            color: #94a3b8;
            font-size: 0.82rem;
            font-weight: 500;
            margin-top: 32px;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            body {
                grid-template-columns: 1fr;
            }
            .login-brand-panel {
                display: none;
            }
            .login-form-panel {
                padding: 32px 24px;
                min-height: 100vh;
            }
            .login-header {
                text-align: center;
            }
            .login-header::before {
                content: '';
                display: block;
                width: 56px;
                height: 56px;
                border-radius: 16px;
                background: #111111;
                color: #fff;
                margin: 0 auto 20px;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Ctext x='50%25' y='55%25' text-anchor='middle' dominant-baseline='middle' font-family='Inter,sans-serif' font-size='20' font-weight='800' fill='white'%3ET%3C/text%3E%3C/svg%3E");
            }
        }
    </style>
</head>
<body>
    {{-- Left: Brand Panel --}}
    <div class="login-brand-panel">
        <div class="brand-content">
            <div class="brand-logo" style="padding: 0; overflow: hidden; border: none; background: transparent;">
                <img src="{{ asset('431219605_1100922654446504_1462438396502192723_n.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
            </div>
            <h1>Transcent Profumo</h1>
            <p>Your premium point-of-sale system for managing orders, products, and your team.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon">📦</div>
                    <div class="brand-feature-text">
                        <strong>Order Management</strong>
                        <span>Create, track, and manage all orders</span>
                    </div>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">🧴</div>
                    <div class="brand-feature-text">
                        <strong>Product Catalog</strong>
                        <span>Manage your product inventory</span>
                    </div>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">📊</div>
                    <div class="brand-feature-text">
                        <strong>Sales Tracking</strong>
                        <span>Monitor daily sales and exports</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="brand-footer">© {{ date('Y') }} Transcent Profumo. All rights reserved.</div>
    </div>

    {{-- Right: Login Form --}}
    <div class="login-form-panel">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome back</h2>
                <p>Enter your credentials to access the dashboard</p>
            </div>

            @if ($errors->any())
                <div class="error-msg">
                    <span>⚠️</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="off" placeholder="Enter your username">
                        <svg class="input-icon" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; width:18px; height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                        <svg class="input-icon" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); pointer-events:none; color:#94a3b8; width:18px; height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <svg id="eyeIconOpen" style="width:18px; height:18px; display:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg id="eyeIconClosed" style="width:18px; height:18px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-row">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </button>
            </form>

            <div class="login-footer">
                Secured by Transcent Profumo POS
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const iconOpen = document.getElementById('eyeIconOpen');
            const iconClosed = document.getElementById('eyeIconClosed');

            if(toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if(type === 'text') {
                        iconClosed.style.display = 'none';
                        iconOpen.style.display = 'block';
                    } else {
                        iconClosed.style.display = 'block';
                        iconOpen.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
