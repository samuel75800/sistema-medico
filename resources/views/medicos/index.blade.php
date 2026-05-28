<x-layouts.app title="Médicos">
<style>
  .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
  .search-wrap { position:relative; flex:1; max-width:340px; }
  .search-wrap svg { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-dim); pointer-events:none; }
  .search-wrap .input { padding-left:40px; }
  .actions-cell { display:flex; gap:6px; justify-content:flex-end; }
  .avatar { width:34px; height:34px; border-radius:50%; background:var(--grad-primary); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; color:#fff; flex-shrink:0; }
  .name-cell { display:flex; align-items:center; gap:10px; font-weight:600; }
  .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .form-grid .full { grid-column:1/-1; }
  @media(max-width:600px){ .form-grid{grid-template-columns:1fr;} .form-grid .full{grid-column:1;} }
</style>

<div class="toolbar">
  <form method="GET" action="{{ route('medicos.index') }}" class="search-wrap">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" class="input" placeholder="Buscar médico..." value="{{ request('q') }}">
  </form>
  <button class="btn btn-primary ml-auto" onclick="openModal('modal-create')">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Novo médico
  </button>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Médico</th>
        <th>CRM</th>
        <th>Especialidade</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Consultas</th>
        <th style="text-align:right">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($medicos as $m)
      <tr>
        <td>
          <div class="name-cell">
            <div class="avatar">{{ mb_strtoupper(mb_substr($m->nome,0,1)) }}</div>
            {{ $m->nome }}
          </div>
        </td>
        <td><span class="mono" style="font-size:0.82rem; color:var(--accent);">{{ $m->crm }}</span></td>
        <td>{{ $m->especialidade }}</td>
        <td style="color:var(--text-muted)">{{ $m->email }}</td>
        <td style="color:var(--text-muted)">{{ $m->telefone }}</td>
        <td><span class="badge badge-blue">{{ $m->consultas_count }}</span></td>
        <td>
          <div class="actions-cell">
            <button class="btn btn-ghost btn-icon btn-sm" title="Editar"
              onclick="openEditModal({{ $m->toJson() }})">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <form method="POST" action="{{ route('medicos.destroy', $m) }}" style="display:inline">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-danger btn-icon btn-sm" title="Remover"
                onclick="confirmAction('Remover Dr. {{ addslashes($m->nome) }}? As consultas vinculadas também serão removidas.', () => this.closest('form').submit())">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
        {{ request('q') ? 'Nenhum médico encontrado para "'.request('q').'".' : 'Nenhum médico cadastrado ainda.' }}
      </td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div id="modal-create" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Novo médico</h3>
      <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
    </div>
    <form method="POST" action="{{ route('medicos.store') }}">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label>Nome completo *</label>
          <input type="text" name="nome" class="input" placeholder="Dr. Nome Sobrenome" required>
        </div>
        <div class="form-group">
          <label>CRM *</label>
          <input type="text" name="crm" class="input" placeholder="CRM-12345" required>
        </div>
        <div class="form-group">
          <label>Especialidade *</label>
          <select name="especialidade" class="input" required>
            <option value="">Selecione</option>
            @foreach(['Cardiologia','Pediatria','Ortopedia','Neurologia','Dermatologia','Ginecologia','Oncologia','Psiquiatria','Clínico Geral','Outra'] as $esp)
              <option value="{{ $esp }}">{{ $esp }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>E-mail *</label>
          <input type="email" name="email" class="input" placeholder="dr@hospital.com" required>
        </div>
        <div class="form-group">
          <label>Telefone *</label>
          <input type="text" name="telefone" class="input" placeholder="(00) 00000-0000" required>
        </div>
      </div>
      <div class="flex gap-sm" style="justify-content:flex-end; margin-top:24px;">
        <button type="button" class="btn btn-ghost" onclick="closeModal('modal-create')">Cancelar</button>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </div>
    </form>
  </div>
</div>

<div id="modal-edit" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Editar médico</h3>
      <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
    </div>
    <form method="POST" id="edit-form" action="">
      @csrf @method('PUT')
      <div class="form-grid">
        <div class="form-group full">
          <label>Nome completo *</label>
          <input type="text" name="nome" id="e-nome" class="input" required>
        </div>
        <div class="form-group">
          <label>CRM *</label>
          <input type="text" name="crm" id="e-crm" class="input" required>
        </div>
        <div class="form-group">
          <label>Especialidade *</label>
          <select name="especialidade" id="e-especialidade" class="input" required>
            @foreach(['Cardiologia','Pediatria','Ortopedia','Neurologia','Dermatologia','Ginecologia','Oncologia','Psiquiatria','Clínico Geral','Outra'] as $esp)
              <option value="{{ $esp }}">{{ $esp }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>E-mail *</label>
          <input type="email" name="email" id="e-email" class="input" required>
        </div>
        <div class="form-group">
          <label>Telefone *</label>
          <input type="text" name="telefone" id="e-telefone" class="input" required>
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
function openEditModal(m) {
  document.getElementById('e-nome').value      = m.nome;
  document.getElementById('e-crm').value       = m.crm;
  document.getElementById('e-email').value     = m.email;
  document.getElementById('e-telefone').value  = m.telefone;
  const esp = document.getElementById('e-especialidade');
  for (let o of esp.options) o.selected = o.value === m.especialidade;
  document.getElementById('edit-form').action  = '/medicos/' + m.id;
  openModal('modal-edit');
}
</script>

</x-layouts.app>