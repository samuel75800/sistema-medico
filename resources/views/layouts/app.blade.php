<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Hospital' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
</head>
<body>

<div id="cursor-dot"></div>
<div id="cursor-ring"></div>
<div id="toast-container" aria-live="polite"></div>

@if(session('success'))
  <span data-session-toast="{{ session('success') }}" data-session-type="success" hidden></span>
@endif
@if(session('error'))
  <span data-session-toast="{{ session('error') }}" data-session-type="error" hidden></span>
@endif

<header class="navbar">
  <a href="{{ route('dashboard') }}" class="navbar-brand">
    <div class="brand-icon">🏥</div>
    Hospital
  </a>

  <nav>
    <ul class="navbar-nav">
      <li>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="{{ route('medicos.index') }}" class="{{ request()->routeIs('medicos.*') ? 'active' : '' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Médicos</span>
        </a>
      </li>
      <li>
        <a href="{{ route('pacientes.index') }}" class="{{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Pacientes</span>
        </a>
      </li>
      <li>
        <a href="{{ route('consultas.index') }}" class="{{ request()->routeIs('consultas.*') ? 'active' : '' }}">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span>Consultas</span>
        </a>
      </li>

      <li style="width:1px; height:20px; background:var(--border); margin:0 6px;"></li>

      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Sair</span>
          </button>
        </form>
      </li>
    </ul>
  </nav>
</header>

<div class="page-header">
  <div class="container">
    <h1>
      <span class="accent-line"></span>
      {{ $title ?? 'Dashboard' }}
    </h1>
  </div>
</div>

<main class="container" style="padding-top:32px; padding-bottom:60px;">
  {{ $slot }}
</main>

<footer style="border-top:1px solid var(--border); padding:18px 0; background:var(--bg-card);">
  <div class="container flex items-center justify-between" style="flex-wrap:wrap; gap:12px;">
    <div style="display:flex; align-items:center; gap:8px;">
      <div class="brand-icon" style="width:24px; height:24px; font-size:0.75rem; border-radius:6px; background:var(--grad-primary); display:flex; align-items:center; justify-content:center;">🏥</div>
      <span style="font-weight:700; font-size:0.9rem;">Hospital</span>
      <span style="font-size:0.75rem; color:var(--text-muted); margin-left:4px; padding-left:8px; border-left:1px solid var(--border);">Sistema de gestão hospitalar</span>
    </div>
    <span style="font-size:0.75rem; color:var(--text-dim);">&copy; {{ date('Y') }} Hospital</span>
  </div>
</footer>

<script src="{{ asset('assets/js/global.js') }}"></script>
</body>
</html>