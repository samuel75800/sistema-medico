<x-layouts.app title="Dashboard">

<style>
  .stats-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 16px; margin-bottom: 32px;
  }
  .stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 24px; position: relative; overflow: hidden;
    transition: border-color var(--t-normal), transform var(--t-normal);
  }
  .stat-card:hover { border-color: var(--border-hover); transform: translateY(-2px); }
  .stat-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  }
  .stat-medicos::before   { background: var(--grad-primary); }
  .stat-pacientes::before { background: var(--grad-teal); }
  .stat-consultas::before { background: var(--grad-rose); }

  .stat-icon {
    width: 44px; height: 44px; border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 16px;
  }
  .stat-medicos   .stat-icon { background: var(--accent-soft); }
  .stat-pacientes .stat-icon { background: rgba(15,216,80,0.1); }
  .stat-consultas .stat-icon { background: rgba(245,87,108,0.1); }

  .stat-value {
    font-size: 2.2rem; font-weight: 800; line-height: 1;
    margin-bottom: 4px;
  }
  .stat-medicos   .stat-value { background: var(--grad-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
  .stat-pacientes .stat-value { background: var(--grad-teal);    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
  .stat-consultas .stat-value { background: var(--grad-rose);    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

  .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; }

  .dash-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }

  .section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
  .section-title { font-size: 0.85rem; font-weight: 700; color: var(--text); text-transform: uppercase; letter-spacing: 0.08em; display: flex; align-items: center; gap: 8px; }
  .section-title .pill { font-size: 0.7rem; background: var(--accent-soft); color: var(--accent); padding: 2px 8px; border-radius: 99px; }

  .appt-list { display: flex; flex-direction: column; gap: 8px; }
  .appt-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-md); transition: all var(--t-fast);
  }
  .appt-item:hover { border-color: var(--border-hover); background: rgba(102,126,234,0.04); }
  .appt-date {
    min-width: 48px; background: var(--accent-soft); border-radius: var(--r-sm);
    padding: 6px 8px; text-align: center; flex-shrink: 0;
  }
  .appt-day { font-size: 1.2rem; font-weight: 800; color: var(--accent); line-height: 1; }
  .appt-mon { font-size: 0.62rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.05em; }
  .appt-info { flex: 1; min-width: 0; }
  .appt-name { font-weight: 600; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .appt-meta { font-size: 0.78rem; color: var(--text-muted); margin-top: 2px; }
  .appt-time { font-size: 0.82rem; font-weight: 700; color: var(--text-muted); flex-shrink: 0; font-family: 'JetBrains Mono', monospace; }

  .quick-list { display: flex; flex-direction: column; gap: 10px; }
  .quick-card {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 18px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--r-lg); text-decoration: none; color: var(--text);
    transition: all var(--t-normal); position: relative; overflow: hidden;
  }
  .quick-card:hover { border-color: var(--border-hover); transform: translateX(4px); }
  .quick-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; border-radius: 0 2px 2px 0;
    opacity: 0; transition: opacity var(--t-normal);
  }
  .quick-card:hover::before { opacity: 1; }
  .q-medicos::before   { background: var(--grad-primary); }
  .q-pacientes::before { background: var(--grad-teal); }
  .q-consultas::before { background: var(--grad-rose); }
  .quick-icon { width: 40px; height: 40px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
  .q-medicos   .quick-icon { background: var(--accent-soft); }
  .q-pacientes .quick-icon { background: rgba(15,216,80,0.1); }
  .q-consultas .quick-icon { background: rgba(245,87,108,0.1); }
  .quick-label { font-weight: 700; font-size: 0.9rem; }
  .quick-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }
  .quick-arrow { margin-left: auto; color: var(--text-dim); transition: transform var(--t-fast), color var(--t-fast); flex-shrink: 0; }
  .quick-card:hover .quick-arrow { transform: translateX(4px); color: var(--accent); }

  .empty-box { text-align: center; padding: 40px; background: var(--bg-card); border: 1px dashed var(--border); border-radius: var(--r-lg); color: var(--text-muted); font-size: 0.875rem; }

  @media(max-width:1024px) { .stats-grid { grid-template-columns: repeat(2,1fr); } .dash-grid { grid-template-columns: 1fr; } }
  @media(max-width:600px)  { .stats-grid { grid-template-columns: 1fr; } }
</style>

<?php
  $months = ['','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
?>

<div class="stats-grid">
  <div class="stat-card stat-medicos">
    <div class="stat-icon">👨‍⚕️</div>
    <div class="stat-value">{{ $total_medicos }}</div>
    <div class="stat-label">Médicos</div>
  </div>
  <div class="stat-card stat-pacientes">
    <div class="stat-icon">🧑‍🤝‍🧑</div>
    <div class="stat-value">{{ $total_pacientes }}</div>
    <div class="stat-label">Pacientes</div>
  </div>
  <div class="stat-card stat-consultas">
    <div class="stat-icon">📋</div>
    <div class="stat-value">{{ $hoje_consultas }}</div>
    <div class="stat-label">Consultas hoje</div>
  </div>
</div>

<div class="dash-grid">
  <div>
    <div class="section-head">
      <div class="section-title">
        Próximas consultas
        @if($proximas->count())
          <span class="pill">{{ $proximas->count() }}</span>
        @endif
      </div>
      <a href="{{ route('consultas.index') }}" class="btn btn-ghost btn-sm">Ver todas</a>
    </div>

    @if($proximas->isEmpty())
      <div class="empty-box">Nenhuma consulta agendada.</div>
    @else
      <div class="appt-list">
        @foreach($proximas as $c)
        <div class="appt-item">
          <div class="appt-date">
            <div class="appt-day">{{ $c->data->format('d') }}</div>
            <div class="appt-mon">{{ $months[$c->data->format('n')] }}</div>
          </div>
          <div class="appt-info">
            <div class="appt-name">{{ $c->paciente->nome }}</div>
            <div class="appt-meta">Dr. {{ $c->medico->nome }} · {{ $c->medico->especialidade }}</div>
          </div>
          <div class="appt-time">{{ substr($c->horario, 0, 5) }}</div>
        </div>
        @endforeach
      </div>
    @endif
  </div>

  <div>
    <div class="section-head">
      <div class="section-title">Acesso rápido</div>
    </div>
    <div class="quick-list">
      <a href="{{ route('medicos.index') }}" class="quick-card q-medicos">
        <div class="quick-icon">👨‍⚕️</div>
        <div><div class="quick-label">Médicos</div><div class="quick-sub">{{ $total_medicos }} cadastrados</div></div>
        <svg class="quick-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="{{ route('pacientes.index') }}" class="quick-card q-pacientes">
        <div class="quick-icon">🧑‍🤝‍🧑</div>
        <div><div class="quick-label">Pacientes</div><div class="quick-sub">{{ $total_pacientes }} cadastrados</div></div>
        <svg class="quick-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="{{ route('consultas.index') }}" class="quick-card q-consultas">
        <div class="quick-icon">📋</div>
        <div><div class="quick-label">Consultas</div><div class="quick-sub">{{ $hoje_consultas }} hoje</div></div>
        <svg class="quick-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</div>

</x-layouts.app>