<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SMK At Kausar</title>
    <meta name="description" content="Masuk ke Sistem Informasi Akademik SMK At Kausar">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0c29;
            overflow: hidden;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 70%, #533483 100%);
        }

        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.35;
            animation: floatOrb 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: #4e73df; top: -150px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #1cc88a; bottom: -100px; right: -80px; animation-delay: -3s; }
        .orb-3 { width: 300px; height: 300px; background: #e74a3b; top: 50%; left: 40%; animation-delay: -5s; }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -40px) scale(1.05); }
            66%       { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ===== GRID LAYOUT ===== */
        .login-wrapper {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
        }

        /* ===== LEFT PANEL — BRANDING ===== */
        .brand-panel {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px;
            animation: slideInLeft 0.8s ease-out both;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .brand-logo {
            width: 120px; height: 120px; border-radius: 32px;
            background: linear-gradient(135deg, #f6c23e, #e74a3b);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 32px;
            box-shadow: 0 20px 60px rgba(246,194,62,0.4);
            animation: pulse 3s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 20px 60px rgba(246,194,62,0.4); }
            50%       { box-shadow: 0 20px 80px rgba(246,194,62,0.6); }
        }

        .brand-title {
            font-size: 3rem; font-weight: 900; color: white;
            text-align: center; line-height: 1.1;
            margin-bottom: 16px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        .brand-title span { color: #f6c23e; }

        .brand-subtitle {
            font-size: 1.1rem; color: rgba(255,255,255,0.65);
            text-align: center; max-width: 380px; line-height: 1.7;
            margin-bottom: 48px;
        }

        .feature-list { display: flex; flex-direction: column; gap: 16px; width: 100%; max-width: 360px; }
        .feature-item {
            display: flex; align-items: center; gap: 16px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 16px 20px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .feature-item:hover { background: rgba(255,255,255,0.12); transform: translateX(4px); }
        .feature-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .feature-text strong { display: block; color: white; font-size: 0.9rem; font-weight: 600; }
        .feature-text span   { color: rgba(255,255,255,0.55); font-size: 0.78rem; }

        /* ===== RIGHT PANEL — LOGIN CARD ===== */
        .login-panel {
            background: rgba(255,255,255,0.04);
            border-left: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            display: flex; align-items: center; justify-content: center;
            padding: 40px 48px;
            animation: slideInRight 0.8s ease-out 0.2s both;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .login-card { width: 100%; max-width: 380px; }

        .login-heading {
            margin-bottom: 36px;
        }
        .login-heading h1 {
            font-size: 1.9rem; font-weight: 800; color: white;
            margin-bottom: 8px;
        }
        .login-heading p { color: rgba(255,255,255,0.5); font-size: 0.9rem; }

        /* ===== FORM ===== */
        .form-group { margin-bottom: 20px; }

        .input-label {
            display: block; margin-bottom: 8px;
            font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.04em; text-transform: uppercase;
        }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.3); font-size: 0.9rem; pointer-events: none;
            transition: color 0.2s;
        }
        .input-field {
            width: 100%; padding: 14px 16px 14px 44px;
            background: rgba(255,255,255,0.07);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            color: white; font-size: 0.95rem; font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.25s ease;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.25); }
        .input-field:focus {
            background: rgba(255,255,255,0.11);
            border-color: #4e73df;
            box-shadow: 0 0 0 4px rgba(78,115,223,0.2);
        }
        .input-field:focus ~ .input-icon { color: #4e73df; }
        .input-field.is-error { border-color: #e74a3b; }

        .toggle-password {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: rgba(255,255,255,0.3);
            cursor: pointer; font-size: 0.9rem; padding: 4px;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: rgba(255,255,255,0.7); }

        /* Error messages */
        .error-msg {
            display: block; margin-top: 6px;
            color: #fc8181; font-size: 0.78rem;
        }
        .session-status {
            background: rgba(28,200,138,0.15);
            border: 1px solid rgba(28,200,138,0.4);
            border-radius: 10px; padding: 10px 14px;
            color: #1cc88a; font-size: 0.85rem;
            margin-bottom: 20px;
        }

        /* Remember me */
        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #4e73df; cursor: pointer;
        }
        .remember-label span { color: rgba(255,255,255,0.55); font-size: 0.85rem; }
        .forgot-link {
            color: #4e73df; font-size: 0.85rem; font-weight: 500;
            text-decoration: none; transition: color 0.2s;
        }
        .forgot-link:hover { color: #6d8fe8; text-decoration: underline; }

        /* Submit Button */
        .btn-login {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none; border-radius: 14px;
            color: white; font-size: 1rem; font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer; position: relative; overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(78,115,223,0.4);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(78,115,223,0.55);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login .btn-ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0); animation: ripple 0.6s linear;
        }
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0;
        }
        .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.1); }
        .divider-text { color: rgba(255,255,255,0.3); font-size: 0.78rem; }

        /* Role badges */
        .role-badges {
            display: grid; grid-template-columns: repeat(3,1fr); gap: 8px;
        }
        .role-badge {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; padding: 10px 6px;
            text-align: center; font-size: 0.75rem;
            color: rgba(255,255,255,0.55);
        }
        .role-badge i { display: block; font-size: 1.1rem; margin-bottom: 4px; }
        .role-badge.admin i { color: #4e73df; }
        .role-badge.guru i { color: #1cc88a; }
        .role-badge.siswa i { color: #36b9cc; }

        /* Footer text */
        .login-footer {
            text-align: center; margin-top: 28px;
            color: rgba(255,255,255,0.3); font-size: 0.75rem;
        }

        /* ===== FLOATING DOTS ===== */
        .dots-bg {
            position: fixed; inset: 0; z-index: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 900px) {
            .login-wrapper { grid-template-columns: 1fr; }
            .brand-panel { display: none; }
            .login-panel {
                border-left: none;
                min-height: 100vh;
                background: transparent;
                padding: 32px 24px;
            }
            body { overflow-y: auto; }
        }
    </style>
</head>
<body>
    <div class="bg-scene">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="dots-bg"></div>

    <div class="login-wrapper">

        {{-- ===== LEFT: BRANDING ===== --}}
        <div class="brand-panel">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap" style="font-size:3rem;color:white;"></i>
            </div>
            <h1 class="brand-title">SMK<br><span>At Kausar</span></h1>
            <p class="brand-subtitle">
                Sistem Informasi Akademik terintegrasi untuk pengelolaan absensi, nilai, dan data siswa secara digital.
            </p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon" style="background:rgba(78,115,223,0.3);">
                        <i class="fas fa-clipboard-check" style="color:#4e73df;"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Absensi Digital</strong>
                        <span>Rekap kehadiran siswa real-time</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon" style="background:rgba(28,200,138,0.3);">
                        <i class="fas fa-star" style="color:#1cc88a;"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Manajemen Nilai</strong>
                        <span>Input nilai & cetak raport PDF</span>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon" style="background:rgba(246,194,62,0.3);">
                        <i class="fas fa-chart-bar" style="color:#f6c23e;"></i>
                    </div>
                    <div class="feature-text">
                        <strong>Dashboard Analitik</strong>
                        <span>Grafik & laporan akademik lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT: LOGIN FORM ===== --}}
        <div class="login-panel">
            <div class="login-card">
                <div class="login-heading">
                    <h1>Selamat Datang 👋</h1>
                    <p>Masuk ke sistem dengan akun Anda</p>
                </div>

                {{-- Session Status --}}
                @if(session('status'))
                <div class="session-status">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="input-label" for="email">Alamat Email</label>
                        <div class="input-wrap">
                            <input
                                id="email" type="email" name="email"
                                class="input-field {{ $errors->has('email') ? 'is-error' : '' }}"
                                value="{{ old('email') }}"
                                placeholder="nama@sekolah.id"
                                required autofocus autocomplete="username"
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                        <span class="error-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label class="input-label" for="password">Password</label>
                        <div class="input-wrap">
                            <input
                                id="password" type="password" name="password"
                                class="input-field {{ $errors->has('password') ? 'is-error' : '' }}"
                                placeholder="••••••••"
                                required autocomplete="current-password"
                            >
                            <i class="fas fa-lock input-icon"></i>
                            <button type="button" class="toggle-password" onclick="togglePwd()" id="toggleBtn">
                                <i class="fas fa-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                        <span class="error-msg"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="remember-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Ingat saya</span>
                        </label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
                    </button>
                </form>

                {{-- Divider --}}
                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">Akses untuk</span>
                    <div class="divider-line"></div>
                </div>

                <div class="role-badges">
                    <div class="role-badge admin">
                        <i class="fas fa-user-shield"></i>
                        Administrator
                    </div>
                    <div class="role-badge guru">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Guru
                    </div>
                    <div class="role-badge siswa">
                        <i class="fas fa-user-graduate"></i>
                        Siswa
                    </div>
                </div>

                <div class="login-footer">
                    &copy; {{ date('Y') }} SMK At Kausar — Sistem Informasi Akademik
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePwd() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        // Ripple effect on login button
        document.getElementById('loginBtn').addEventListener('click', function(e) {
            const btn    = this;
            const circle = document.createElement('span');
            const diameter = Math.max(btn.clientWidth, btn.clientHeight);
            const rect   = btn.getBoundingClientRect();
            circle.style.width = circle.style.height = diameter + 'px';
            circle.style.left  = (e.clientX - rect.left  - diameter/2) + 'px';
            circle.style.top   = (e.clientY - rect.top   - diameter/2) + 'px';
            circle.classList.add('btn-ripple');
            btn.querySelector('.btn-ripple')?.remove();
            btn.appendChild(circle);
        });

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            btn.disabled  = true;
        });

        // Focus glow on input icon
        document.querySelectorAll('.input-field').forEach(function(input) {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('.input-icon');
                if (icon) icon.style.color = '#4e73df';
            });
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('.input-icon');
                if (icon) icon.style.color = 'rgba(255,255,255,0.3)';
            });
        });
    </script>
</body>
</html>
