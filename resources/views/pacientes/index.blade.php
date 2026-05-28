<x-layouts.app title="Pacientes">
<style>
  .toolbar { display:flex; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
  .search-wrap { position:relative; flex:1; max-width:340px; }
  .search-wrap svg { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:var(--text-dim); pointer-events:none; }
  .search-wrap .input { padding-left:40px; }
  .actions-cell { display:flex; gap:6px; justify-content:flex-end; }
  .avatar { width:34px; height:34px; border-radius:50%; background:var(--grad-teal); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; color:#fff; flex-shrink:0; }
  .name-cell { display:flex; align-items:center; gap:10px; font-weight:600; }
  .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  .form-grid .full { grid-column:1/-1; }
  @media(max-width:600px){ .form-grid{grid-template-columns:1fr;} .form-grid .full{grid-column:1;} }
</style>

<div class="toolbar">
  <form method="GET" action="{{ route('pacientes.index') }}" class="search-wrap">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" class="input" placeholder="Buscar paciente..." value="{{ request('q') }}">
  </form>
  <button class="btn btn-primary ml-auto" onclick="openModal('modal-create')">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Novo paciente
  </button>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Paciente</th>
        <th>E-mail</th>
        <th>Telefone</th>
        <th>Nascimento</th>
        <th>Tipo sanguíneo</th>
        <th>Consultas</th>
        <th style="text-align:right">Ações</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pacientes as $p)
      <tr>
        <td>
          <div class="name-cell">
            <div class="avatar">{{ mb_strtoupper(mb_substr($p->nome,0,1)) }}</div>
            {{ $p->nome }}
          </div>
        </td>
        <td style="color:var(--text-muted)">{{ $p->email }}</td>
        <td style="color:var(--text-muted)">{{ $p->telefone }}</td>
        <td>{{ $p->nascimento ? $p->nascimento->format('d/m/Y') : '—' }}</td>
        <td>
          @if($p->tipo_sanguineo)
            <span class="badge badge-rose">{{ $p->tipo_sanguineo }}</span>
          @else
            <span style="color:var(--text-dim)">—</span>
          @endif
        </td>
        <td><span class="badge badge-green">{{ $p->consultas_count }}</span></td>
        <td>
          <div class="actions-cell">
            <button class="btn btn-ghost btn-icon btn-sm" title="Editar"
              onclick="openEditModal({{ $p->toJson() }})">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <form method="POST" action="{{ route('pacientes.destroy', $p) }}" style="display:inline">
              @csrf @method('DELETE')
              <button type="button" class="btn btn-danger btn-icon btn-sm" title="Remover"
                onclick="confirmAction('Remover {{ addslashes($p->nome) }}? As consultas vinculadas também serão removidas.', () => this.closest('form').submit())">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
        {{ request('q') ? 'Nenhum paciente encontrado para "'.request('q').'".' : 'Nenhum paciente cadastrado ainda.' }}
      </td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div id="modal-create" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3>Novo paciente</h3>
      <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
    </div>
    <form method="POST" action="{{ route('pacientes.store') }}">
      @csrf
      <div class="form-grid">
        <div class="form-group full">
          <label>Nome completo *</label>
          <input type="text" name="nome" class="input" placeholder="Nome do paciente" required>
        </div>
        <div class="form-group">
          <label>E-mail *</label>
          <input type="email" name="email" class="input" placeholder="email@exemplo.com" required>
        </div>
        <div class="form-group">
          <label>Telefone *</label>
          <input type="text" name="telefone" class="input" placeholder="(00) 00000-0000" required>
        </div>
        <div class="form-group">
          <label>Data de nascimento</label>
          <input type="date" name="nascimento" class="input">
        </div>
        <div class="form-group">
          <label>Tipo sanguíneo</label>
          <select name="tipo_sanguineo" class="input">
            <option value="">Selecione</option>
            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
              <option value="{{ $tipo }}">{{ $tipo }}</option>
            @endforeach
          </select>
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
      <h3>Editar paciente</h3>
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
          <label>E-mail *</label>
          <input type="email" name="email" id="e-email" class="input" required>
        </div>
        <div class="form-group">
          <label>Telefone *</label>
          <input type="text" name="telefone" id="e-telefone" class="input" required>
        </div>
        <div class="form-group">
          <label>Data de nascimento</label>
          <input type="date" name="nascimento" id="e-nascimento" class="input">
        </div>
        <div class="form-group">
          <label>Tipo sanguíneo</label>
          <select name="tipo_sanguineo" id="e-tipo" class="input">
            <option value="">Selecione</option>
            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
              <option value="{{ $tipo }}">{{ $tipo }}</option>
            @endforeach
          </select>
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
function openEditModal(p) {
  document.getElementById('e-nome').value      = p.nome;
  document.getElementById('e-email').value     = p.email;
  document.getElementById('e-telefone').value  = p.telefone;
  document.getElementById('e-nascimento').value = p.nascimento ? p.nascimento.split('T')[0] : '';
  const tipo = document.getElementById('e-tipo');
  for (let o of tipo.options) o.selected = o.value === p.tipo_sanguineo;
  document.getElementById('edit-form').action = '/pacientes/' + p.id;
  openModal('modal-edit');
}
</script>

</x-layouts.app>