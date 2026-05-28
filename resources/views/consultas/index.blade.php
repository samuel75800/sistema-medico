<x-layouts.app title="Consultas">
<style>
  .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
  .search-wrap { position:relative; flex:1; max-width:300px; }
  .search-wrap svg { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-dim); pointer-events:none; }
  .search-wrap .input { padding-left:40px; }
  .filter-tabs { display:flex; gap:4px; background:var(--bg-input); border:1px solid var(--border); border-radius:var(--r-md); padding:3px; }
  .filter-tab { padding:6px 14px; border-radius:calc(var(--r-md) - 2px); font-size:0.8125rem; font-weight:500; color:var(--text-muted); text-decoration:none; transition:all var(--t-fast); white-space:nowrap; }
  .filter-tab:hover { color:var(--accent); }
  .filter-tab.active { background:var(--bg-card); color:var(--accent); font-weight:600; box-shadow:var(--shadow-sm); border:1px solid var(--border-hover); }
  .actions-cell { display:flex; gap:6px; justify-content:flex-end; }
  .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .form-grid .full { grid-column:1/-1; }
  @media(max-width:600px){ .form-grid{grid-template-columns:1fr;} .form-grid .full{grid-column:1;} .filter-tabs{width:100%;} }
</style>

@php
  $status_map = [
    'agendada'  => ['label'=>'Agendada',  'badge'=>'badge-blue'],
    'concluida' => ['label'=>'Concluída', 'badge'=>'badge-green'],
    'cancelada' => ['label'=>'Cancelada', 'badge'=>'badge-rose'],
  ];
  $filter = request('status','all');
@endphp

<div class="toolbar">
  <form method="GET" action="{{ route('consultas.index') }}" class="search-wrap">
    <input type="hidden" name="status" value="{{ $filter }}">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" class="input" placeholder="Buscar..." value="{{ request('q') }}">
  </form>

  <div class="filter-tabs">
    @foreach(['all'=>'Todas','agendada'=>'Agendadas','concluida'=>'Concluídas','cancelada'=>'Canceladas'] as $val=>$lbl)
      <a href="{{ route('consultas.index', ['status'=>$val, 'q'=>request('q')]) }}"
         class="filter-tab {{ $filter===$val ? 'active' : '' }}">{{ $lbl }}</a>
    @endforeach
  </div>

  <button class="btn btn-primary ml-auto" onclick="openModal('modal-create')">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nova consulta
  </button>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Paciente</th>
        <th>Médico</th>
        <th>Especialidade</th>
        <th>Data</th>
        <th>Horário</th>
        <th>Status</th>
        <th>Observações</th>
        <th style="text-align:right">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($consultas as $c)
      @php $sc = $status_map[$c->status] ?? ['label'=>$c->status,'badge'=>'badge-muted']; @endphp
      <tr>
        <td style="font-weight:600">{{ $c->paciente->nome }}</td>
        <td>{{ $c->medico->nome }}</td>
        <td><span class="badge badge-purple">{{ $c->medico->especialidade }}</span></td>
        <td class="mono" style="font-size:0.82rem">{{ $c->data->format('d/m/Y') }}</td>
        <td class="mono" style="font-size:0.82rem">{{ substr($c->horario,0,5) }}</td>
        <td><span class="badge {{ $sc['badge'] }}">{{ $sc['label'] }}</span></td>
        <td style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--text-muted)">
          {{ $c->observacoes ?? '—' }}
        </td>
        <td>
          <div class="actions-cell">
            <button class="btn btn-ghost btn-icon btn-sm" title="Editar"
              onclick="openEditModal({{ $c->toJson() }})">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <form method="POST" action="{{ route('consultas.destroy', $c) }}" style="display:inline">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-danger btn-icon btn-sm" title="Remover"
                onclick="confirmAction('Remover esta consulta?', () => this.closest('form').submit())">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center; padding:48px; color:var(--text-muted);">Nenhuma consulta encontrada.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div id="modal-create" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Nova consulta</h3>
      <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
    </div>
    <form method="POST" action="{{ route('consultas.store') }}">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label>Médico *</label>
          <select name="medico_id" class="input" required>
            <option value="">Selecione o médico</option>
            @foreach($medicos as $m)
              <option value="{{ $m->id }}">{{ $m->nome }} — {{ $m->especialidade }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group full">
          <label>Paciente *</label>
          <select name="paciente_id" class="input" required>
            <option value="">Selecione o paciente</option>
            @foreach($pacientes as $p)
              <option value="{{ $p->id }}">{{ $p->nome }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Data *</label>
          <input type="date" name="data" class="input" required>
        </div>
        <div class="form-group">
          <label>Horário *</label>
          <input type="time" name="horario" class="input" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" class="input">
            <option value="agendada">Agendada</option>
            <option value="concluida">Concluída</option>
            <option value="cancelada">Cancelada</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Observações</label>
          <textarea name="observacoes" class="input" placeholder="Informações adicionais..."></textarea>
        </div>
      </div>
      <div class="flex gap-sm" style="justify-content:flex-end; margin-top:24px;">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-create')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Agendar</button>
      </div>
    </form>
  </div>
</div>

<div id="modal-edit" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Editar consulta</h3>
      <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
    </div>
    <form method="POST" id="edit-form" action="">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group full">
          <label>Médico *</label>
          <select name="medico_id" id="e-medico" class="input" required>
            @foreach($medicos as $m)
              <option value="{{ $m->id }}">{{ $m->nome }} — {{ $m->especialidade }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group full">
          <label>Paciente *</label>
          <select name="paciente_id" id="e-paciente" class="input" required>
            @foreach($pacientes as $p)
              <option value="{{ $p->id }}">{{ $p->nome }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Data *</label>
          <input type="date" name="data" id="e-data" class="input" required>
        </div>
        <div class="form-group">
          <label>Horário *</label>
          <input type="time" name="horario" id="e-horario" class="input" required>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" id="e-status" class="input">
            <option value="agendada">Agendada</option>
            <option value="concluida">Concluída</option>
            <option value="cancelada">Cancelada</option>
          </select>
        </div>
        <div class="form-group full">
          <label>Observações</label>
          <textarea name="observacoes" id="e-obs" class="input"></textarea>
        </div>
      </div>
      <div class="flex gap-sm" style="justify-content:flex-end; margin-top:24px;">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-edit')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(c) {
  document.getElementById('e-data').value    = c.data ? c.data.split('T')[0] : '';
  document.getElementById('e-horario').value = c.horario ? c.horario.substring(0,5) : '';
  document.getElementById('e-obs').value     = c.observacoes ?? '';
  for (let o of document.getElementById('e-medico').options)   o.selected = o.value == c.medico_id;
  for (let o of document.getElementById('e-paciente').options) o.selected = o.value == c.paciente_id;
  for (let o of document.getElementById('e-status').options)   o.selected = o.value === c.status;
  document.getElementById('edit-form').action = '/consultas/' + c.id;
  openModal('modal-edit');
}
</script>

</x-layouts.app>