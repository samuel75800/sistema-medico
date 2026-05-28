<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar — Hospital</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .login-bg {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
    }
    .orb-1 {
      position: absolute; width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(102,126,234,0.15) 0%, transparent 70%);
      top: -200px; left: -200px;
      animation: float-orb 8s ease-in-out infinite;
    }
    .orb-2 {
      position: absolute; width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(118,75,162,0.12) 0%, transparent 70%);
      bottom: -150px; right: -150px;
      animation: float-orb 10s ease-in-out infinite reverse;
    }
    .orb-3 {
      position: absolute; width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(15,216,80,0.06) 0%, transparent 70%);
      top: 50%; right: 20%;
      animation: float-orb 12s ease-in-out infinite 2s;
    }
    @keyframes float-orb {
      0%, 100% { transform: translate(0, 0); }
      33%  { transform: translate(30px, -20px); }
      66%  { transform: translate(-20px, 15px); }
    }

    .login-card {
      position: relative; z-index: 1;
      width: 100%; max-width: 420px;
      background: var(--bg-card);
      border: 1px solid var(--border-hover);
      border-radius: var(--r-xl);
      padding: 40px;
      box-shadow: var(--shadow-lg), 0 0 80px rgba(102,126,234,0.08);
      overflow: hidden;
    }
    .login-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: var(--grad-primary);
    }

    .login-logo {
      display: flex; align-items: center; gap: 12px; margin-bottom: 32px;
    }
    .login-logo-icon {
      width: 44px; height: 44px; border-radius: var(--r-md);
      background: var(--grad-primary);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem;
      box-shadow: 0 0 24px var(--accent-glow);
    }
    .login-logo-text {
      font-size: 1.4rem; font-weight: 800; color: var(--text);
    }
    .login-logo-sub {
      font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;
    }

    .login-title {
      font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;
      background: var(--grad-primary);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .login-sub {
      font-size: 0.875rem; color: var(--text-muted); margin-bottom: 28px;
    }

    .login-fields { display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }

    .btn-login {
      width: 100%; padding: 13px;
      background: var(--grad-primary);
      color: #fff; border: none;
      border-radius: var(--r-md);
      font-family: 'Outfit', sans-serif;
      font-size: 0.95rem; font-weight: 700;
      cursor: none; transition: all var(--t-normal);
      box-shadow: var(--shadow-accent);
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px rgba(102,126,234,0.5);
    }
    .btn-login:active { transform: scale(0.98); }

    .error-box {
      display: flex; align-items: center; gap: 10px;
      padding: 12px 16px;
      background: var(--danger-soft);
      border: 1px solid var(--danger);
      border-radius: var(--r-md);
      font-size: 0.875rem; color: var(--danger);
      margin-bottom: 16px;
    }

    .login-footer {
      text-align: center; margin-top: 20px;
      font-size: 0.78rem; color: var(--text-dim);
    }
  </style>
</head>
<body>

<div id="cursor-dot"></div>
<div id="cursor-ring"></div>

<div class="login-bg">
  <div class="orb-1"></div>
  <div class="orb-2"></div>
  <div class="orb-3"></div>
</div>

<div class="login-card page-enter">

  <div class="login-logo">
    <div class="login-logo-icon">🏥</div>
    <div>
      <div class="login-logo-text">Hospital</div>
      <div class="login-logo-sub">Sistema de gestão</div>
    </div>
  </div>

  <h2 class="login-title">Bem-vindo de volta</h2>
  <p class="login-sub">Acesso restrito a profissionais autorizados.</p>

  @if($errors->any())
  <div class="error-box">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    {{ $errors->first() }}
  </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}" autocomplete="off">
    @csrf
    <div class="login-fields">

      <div class="form-group">
        <label for="email">E-mail</label>
        <div class="input-icon-wrap">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
          </svg>
          <input type="email" id="email" name="email" class="input"
                 placeholder="seu@email.com"
                 value="{{ old('email') }}"
                 autocomplete="off" required autofocus>
        </div>
      </div>

      <div class="form-group">
        <label for="password">Senha</label>
        <div class="input-icon-wrap">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
          <input type="password" id="password" name="password" class="input"
                 placeholder="••••••••"
                 autocomplete="new-password" required>
        </div>
      </div>

    </div>

    <button type="submit" class="btn-login">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
      </svg>
      Entrar
    </button>
  </form>

  <p class="login-footer">Área restrita — somente pessoal autorizado.</p>
</div>

<script src="{{ asset('assets/js/global.js') }}"></script>
</body>
</html>