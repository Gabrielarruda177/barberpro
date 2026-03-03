<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarberPro — @yield('title', 'Autenticação')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg:        #0A0A08;
            --bg-card:   #111110;
            --bg-input:  #181816;
            --border:    #232320;
            --border-hi: #3A3A34;
            --text:      #EDE9E0;
            --text-muted:#6A6660;
            --text-dim:  #9A9690;
            --gold:      #C9A84C;
            --gold-lt:   #E2C06A;
            --gold-dk:   #7A5E18;
            --gold-glow: rgba(201,168,76,0.18);
            --danger:    #C46060;
            --success:   #5C9E6E;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        /* ─── LEFT PANEL ─── */
        .panel-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
            background: #0D0C0A;
        }

        /* Grain texture overlay */
        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: 0.6;
            pointer-events: none;
            z-index: 1;
        }

        /* Gold diagonal accent */
        .panel-left::after {
            content: '';
            position: absolute;
            top: -60px; right: -1px;
            width: 2px; height: 130%;
            background: linear-gradient(180deg,
                transparent 0%,
                rgba(201,168,76,0.08) 20%,
                rgba(201,168,76,0.35) 50%,
                rgba(201,168,76,0.08) 80%,
                transparent 100%
            );
            z-index: 2;
        }

        .left-top { position: relative; z-index: 3; }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dk) 100%);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: var(--bg);
            box-shadow: 0 4px 20px rgba(201,168,76,0.3);
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem; font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--text);
        }

        /* Decorative quote block */
        .left-center {
            position: relative;
            z-index: 3;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 0;
        }

        .decorative-line {
            width: 40px; height: 2px;
            background: linear-gradient(90deg, var(--gold), transparent);
            margin-bottom: 2rem;
        }

        .hero-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.2rem, 3.5vw, 3.2rem);
            font-weight: 600;
            line-height: 1.15;
            color: var(--text);
            margin-bottom: 1.5rem;
        }

        .hero-text em {
            font-style: italic;
            color: var(--gold);
        }

        .hero-sub {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.75;
            max-width: 320px;
        }

        /* Stats bar */
        .stats-row {
            position: relative;
            z-index: 3;
            display: flex;
            gap: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }

        .stat-item {}
        .stat-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem; font-weight: 700;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 0.2rem;
        }
        .stat-label {
            font-size: 0.72rem; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--text-muted);
        }

        /* Floating bokeh circles */
        .bokeh {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        .bokeh-1 {
            width: 280px; height: 280px;
            top: 15%; left: -80px;
            background: radial-gradient(circle, rgba(201,168,76,0.06) 0%, transparent 70%);
            animation: drift1 12s ease-in-out infinite;
        }
        .bokeh-2 {
            width: 200px; height: 200px;
            bottom: 20%; right: 30px;
            background: radial-gradient(circle, rgba(201,168,76,0.04) 0%, transparent 70%);
            animation: drift2 16s ease-in-out infinite;
        }

        @keyframes drift1 {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }
        @keyframes drift2 {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(20px) scale(0.95); }
        }

        /* ─── RIGHT PANEL ─── */
        .panel-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: var(--bg-card);
            position: relative;
        }

        /* Subtle top gold line */
        .panel-right::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-glow), transparent);
        }

        .back-link {
            position: absolute;
            top: 1.75rem; left: 2rem;
            display: inline-flex; align-items: center; gap: 0.45rem;
            color: var(--text-muted); text-decoration: none;
            font-size: 0.8rem; font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--gold); }
        .back-link i { font-size: 0.7rem; }

        .auth-box {
            width: 100%;
            max-width: 380px;
        }

        /* Flash alerts */
        .alert {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.85rem 1rem; border-radius: 10px;
            font-size: 0.83rem; margin-bottom: 1.5rem;
            animation: fadeDown 0.3s ease;
        }
        .alert-success {
            background: rgba(92,158,110,0.1);
            border: 1px solid rgba(92,158,110,0.25);
            color: var(--success);
        }
        .alert-danger {
            background: rgba(196,96,96,0.1);
            border: 1px solid rgba(196,96,96,0.25);
            color: var(--danger);
        }
        @keyframes fadeDown {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Form header */
        .form-eyebrow {
            font-size: 0.68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.14em;
            color: var(--gold); margin-bottom: 0.5rem;
        }

        .form-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--text); margin-bottom: 0.4rem;
            line-height: 1.2;
        }

        .form-sub {
            font-size: 0.84rem; color: var(--text-muted);
            margin-bottom: 2rem;
        }

        /* Inputs */
        .field { margin-bottom: 1.15rem; }

        .field-label {
            display: block;
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: var(--text-dim); margin-bottom: 0.5rem;
        }

        .input-shell {
            position: relative;
            display: flex; align-items: center;
        }

        .input-shell .icon-left {
            position: absolute; left: 1rem;
            color: var(--text-muted); font-size: 0.8rem;
            pointer-events: none; transition: color 0.2s;
            z-index: 1;
        }

        .input-shell input {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.82rem 1rem 0.82rem 2.6rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .input-shell input::placeholder { color: var(--text-muted); opacity: 0.5; }

        .input-shell input:focus {
            outline: none;
            border-color: var(--gold-dk);
            background: #1A1916;
            box-shadow: 0 0 0 3px var(--gold-glow);
        }

        .input-shell:focus-within .icon-left { color: var(--gold); }

        .input-shell input.has-error { border-color: var(--danger); }

        .toggle-pw {
            position: absolute; right: 0.85rem;
            background: none; border: none; padding: 0.25rem;
            color: var(--text-muted); cursor: pointer;
            font-size: 0.78rem; transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--gold); }

        .field-error {
            font-size: 0.75rem; color: var(--danger);
            display: flex; align-items: center; gap: 0.3rem;
            margin-top: 0.4rem;
        }
        .field-error i { font-size: 0.65rem; }

        /* Row: remember + forgot */
        .form-extras {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.4rem;
        }

        .check-label {
            display: flex; align-items: center; gap: 0.5rem;
            cursor: pointer; font-size: 0.82rem; color: var(--text-muted);
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            appearance: none;
            width: 16px; height: 16px;
            border: 1px solid var(--border-hi);
            border-radius: 4px;
            background: var(--bg-input);
            cursor: pointer;
            position: relative;
            transition: border-color 0.2s, background 0.2s;
            flex-shrink: 0;
        }
        .check-label input[type="checkbox"]:checked {
            background: var(--gold);
            border-color: var(--gold);
        }
        .check-label input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 5px;
            width: 4px; height: 8px;
            border: 2px solid var(--bg);
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        .forgot-link {
            font-size: 0.82rem; font-weight: 600;
            color: var(--gold); text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: var(--gold-lt); }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 0.92rem;
            background: linear-gradient(135deg, var(--gold-lt) 0%, var(--gold) 50%, var(--gold-dk) 100%);
            background-size: 200% auto;
            border: none; border-radius: 10px;
            color: #0A0A08;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem; font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: background-position 0.4s, transform 0.2s, box-shadow 0.2s;
            position: relative; overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .btn-submit:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(201,168,76,0.35);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Divider */
        .form-divider {
            display: flex; align-items: center; gap: 0.85rem;
            margin: 1.5rem 0;
        }
        .form-divider span {
            font-size: 0.75rem; color: var(--text-muted); white-space: nowrap;
        }
        .form-divider::before,
        .form-divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* Footer */
        .form-footer {
            text-align: center;
            font-size: 0.84rem; color: var(--text-muted);
        }
        .form-footer a {
            color: var(--gold); text-decoration: none; font-weight: 700;
            transition: color 0.2s;
        }
        .form-footer a:hover { color: var(--gold-lt); }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ═══ LEFT PANEL ═══ --}}
    <div class="panel-left">
        <div class="bokeh bokeh-1"></div>
        <div class="bokeh bokeh-2"></div>

        <div class="left-top">
            <div class="brand-mark">
                <div class="brand-icon">
                    <i class="fas fa-cut"></i>
                </div>
                <span class="brand-name">BarberPro</span>
            </div>
        </div>

        <div class="left-center">
            <div class="decorative-line"></div>
            <h2 class="hero-text">
                A arte do corte<br>
                começa com <em>precisão.</em>
            </h2>
            <p class="hero-sub">
                Gerencie sua barbearia com elegância. Agendamentos, 
                barbeiros e serviços em um único lugar.
            </p>
        </div>

        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-num">98%</div>
                <div class="stat-label">Satisfação</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">12k+</div>
                <div class="stat-label">Atendimentos</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">5★</div>
                <div class="stat-label">Avaliação</div>
            </div>
        </div>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="panel-right">

        <a href="{{ route('dashboard') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Voltar ao painel
        </a>

        <div class="auth-box">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form content injected here --}}
            @yield('content')

        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const btn   = input.parentElement.querySelector('.toggle-pw');
            const icon  = btn.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(a => {
                a.style.transition = 'opacity 0.4s, transform 0.4s';
                a.style.opacity = '0';
                a.style.transform = 'translateY(-8px)';
                setTimeout(() => a.remove(), 400);
            });
        }, 4500);
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>