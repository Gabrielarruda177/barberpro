@extends('layouts.app')

@section('styles')
<style>
    /* ════════ ESTILO GERAL (HERDADO DO BARBEIRO) ════════ */
    :root {
        --bg-primary: #0a0a0a;
        --bg-secondary: #111;
        --bg-tertiary: #1a1a1a;
        --dark-elevated: #252525;
        --dark-border: #333;
        --text-primary: #f0f0f0;
        --text-muted: #999;
        --gold: #d4af37;
        --gold-dim: #b8941f;
        --danger: #e74c3c;
        --success: #2ecc71;
    }
    body { background:var(--bg-primary); color:var(--text-primary); font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; margin:0; }

    /* ════════ LAYOUT DA PÁGINA ════════ */
    .page-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:2rem 2rem 1rem 2rem; border-bottom:1px solid var(--dark-border);
    }
    .page-header h1 {
        font-size:1.8rem; font-weight:700; margin:0; display:flex; align-items:center; gap:0.75rem;
    }
    .page-header h1 i { color:var(--gold); }
    .service-count { color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem; }
    .btn-gold {
        background:linear-gradient(135deg, var(--gold), var(--gold-dim));
        color:var(--bg-primary); border:none; padding:0.75rem 1.5rem;
        border-radius:8px; font-weight:600; cursor:pointer;
        display:flex; align-items:center; gap:0.5rem;
        transition:transform 0.2s, box-shadow 0.2s;
    }
    .btn-gold:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(212, 175, 55, 0.4); }

    /* ════════ GRID DE SERVIÇOS ════════ */
    .services-grid {
        display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));
        gap:1.5rem; padding:2rem;
    }
    .service-card {
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        border-radius:12px; padding:1.5rem; position:relative;
        transition:transform 0.2s, box-shadow 0.2s; display:flex; flex-direction:column;
    }
    .service-card:hover {
        transform:translateY(-5px);
        box-shadow:0 10px 30px rgba(0,0,0,0.4);
        border-color:var(--gold-dim);
    }
    .service-card-bg {
        position:absolute; top:0; left:0; right:0; height:4px;
        background:linear-gradient(90deg, var(--gold), var(--gold-dim));
        border-radius:12px 12px 0 0;
    }
    .service-actions {
        position:absolute; top:1rem; right:1rem; display:flex; gap:0.5rem;
    }
    .btn-action {
        background:var(--bg-tertiary); border:1px solid var(--dark-border);
        color:var(--text-muted); width:36px; height:36px; border-radius:8px;
        display:flex; align-items:center; justify-content:center; cursor:pointer;
        transition:all 0.2s;
    }
    .btn-action:hover { background:var(--gold); color:var(--bg-primary); border-color:var(--gold); }
    .btn-action.danger:hover { background:var(--danger); border-color:var(--danger); }

    .service-name {
        font-size:1.4rem; font-weight:600; margin:0 0 0.5rem 0; padding-right:4rem;
    }
    .service-description {
        color:var(--text-muted); font-size:0.9rem; line-height:1.5; margin-bottom:1.5rem;
        flex-grow:1;
    }
    .service-details {
        display:flex; flex-direction:column; gap:0.75rem; font-size:0.9rem;
    }
    .service-detail {
        display:flex; align-items:center; gap:0.5rem; color:var(--text-primary);
    }
    .service-detail i { color:var(--gold); width:20px; text-align:center; }
    .service-price {
        font-size:1.25rem; font-weight:700; color:var(--gold);
        text-align:right; margin-top:0.5rem;
    }
    .status-toggle-form { margin-top:1rem; }
    .status-toggle {
        width:100%; padding:0.5rem 1rem; border-radius:20px; border:1px solid var(--dark-border);
        font-weight:600; cursor:pointer; transition:all 0.2s; font-size:0.85rem;
        display:flex; align-items:center; justify-content:center; gap:0.5rem;
    }
    .status-toggle.active { background:var(--success); border-color:var(--success); color:white; }
    .status-toggle.inactive { background:var(--bg-tertiary); color:var(--text-muted); border-color:var(--dark-border); }
    .status-toggle:hover { transform:scale(1.05); }

    /* ════════ ESTADO VAZIO ════════ */
    .empty-state {
        grid-column:1 / -1; text-align:center; padding:4rem 2rem;
        color:var(--text-muted);
    }
    .empty-state i { font-size:4rem; color:var(--dark-border); margin-bottom:1rem; }
    .empty-state p { font-size:1.1rem; margin-bottom:2rem; }

    /* ════════ MODAIS ════════ */
    .modal-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,0.75);
        display:flex; align-items:center; justify-content:center; z-index:1000;
        opacity:0; visibility:hidden; transition:opacity 0.3s, visibility 0.3s;
    }
    .modal-overlay.active { opacity:1; visibility:visible; }
    .modal-box {
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        border-radius:12px; width:90%; max-width:500px;
        max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.8);
        transform:scale(0.9); transition:transform 0.3s;
    }
    .modal-overlay.active .modal-box { transform:scale(1); }
    .modal-header {
        padding:1.5rem; border-bottom:1px solid var(--dark-border);
        display:flex; justify-content:space-between; align-items:center;
    }
    .modal-title {
        font-size:1.3rem; font-weight:600; margin:0; display:flex; align-items:center; gap:0.5rem;
    }
    .modal-close {
        background:none; border:none; color:var(--text-muted); font-size:1.5rem; cursor:pointer;
        padding:0.25rem; transition:color 0.2s;
    }
    .modal-close:hover { color:var(--text-primary); }
    .modal-body { padding:1.5rem; }
    .modal-footer {
        padding:1.5rem; border-top:1px solid var(--dark-border);
        display:flex; justify-content:flex-end; gap:1rem;
    }
    .form-group { margin-bottom:1.25rem; }
    .form-group label { display:block; margin-bottom:0.5rem; font-weight:500; color:var(--text-primary); }
    .input-wrap { position:relative; }
    .input-wrap i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--text-muted); }
    .has-icon { padding-left:2.75rem; }
    .form-control, .has-icon {
        width:100%; padding:0.75rem 1rem; background:var(--bg-secondary);
        border:1px solid var(--dark-border); border-radius:8px; color:var(--text-primary);
        font-size:1rem; transition:border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .has-icon:focus { outline:none; border-color:var(--gold); box-shadow:0 0 0 3px rgba(212, 175, 55, 0.2); }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .btn-ghost {
        background:transparent; color:var(--text-muted); border:1px solid var(--dark-border);
        padding:0.75rem 1.5rem; border-radius:8px; cursor:pointer; font-weight:500;
        transition:all 0.2s;
    }
    .btn-ghost:hover { background:var(--bg-tertiary); color:var(--text-primary); border-color:var(--text-muted); }
    .btn-danger-solid {
        background:var(--danger); color:white; border:none;
        padding:0.75rem 1.5rem; border-radius:8px; cursor:pointer; font-weight:500;
        transition:all 0.2s; display:flex; align-items:center; gap:0.5rem;
    }
    .btn-danger-solid:hover { background:#c0392b; }

    /* ════════ MODAL DE EXCLUSÃO ════════ */
    .delete-modal-box { max-width:400px; }
    .delete-modal-body { text-align:center; padding:2rem 1.5rem; }
    .delete-icon-ring {
        width:64px; height:64px; margin:0 auto 1.5rem;
        background:rgba(217, 112, 112, 0.15); border-radius:50%;
        display:flex; align-items:center; justify-content:center;
    }
    .delete-icon-ring i { font-size:1.5rem; color:#D97070; }
    .delete-modal-title { font-size:1.2rem; font-weight:600; margin:0 0 0.75rem 0; }
    .delete-service-pill {
        display:inline-flex; align-items:center; gap:0.5rem;
        background:var(--dark-elevated); padding:0.4rem 1rem;
        border-radius:20px; border:1px solid var(--dark-border);
        margin-bottom:1.5rem;
    }
    .delete-modal-desc {
        font-size:0.9rem; color:var(--text-muted); line-height:1.5;
    }

    /* ════════ TOAST ════════ */
    .toast {
        position:fixed; bottom:2rem; right:2rem;
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        padding:1rem 1.5rem; border-radius:8px;
        display:flex; align-items:center; gap:0.75rem;
        z-index:2000; transform:translateX(400px);
        transition:transform 0.3s ease-out;
        box-shadow:0 10px 30px rgba(0,0,0,0.5);
    }
    .toast.show { transform:translateX(0); }
    .toast.success { border-left:4px solid var(--gold); }
    .toast.error   { border-left:4px solid var(--danger); }
    .toast i { font-size:1.2rem; }
    .toast.success i { color:var(--gold); }
    .toast.error i   { color:var(--danger); }
    .toast span { color:var(--text-primary); font-weight:500; }

    /* Responsivo */
    @media (max-width: 640px) {
        .form-row { grid-template-columns:1fr; }
        .services-grid { grid-template-columns:1fr; padding:1rem; }
        .page-header { flex-direction:column; align-items:flex-start; gap:1rem; padding:1.5rem 1rem 1rem 1rem; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-cut"></i>
            Serviços
        </h1>
        <span class="service-count">{{ $servicos->count() }} cadastrado(s)</span>
    </div>
    <button class="btn-gold" onclick="openServiceModal()">
        <i class="fas fa-plus"></i> Novo Serviço
    </button>
</div>

<div class="services-grid">
    @forelse($servicos as $servico)
        <div class="service-card">
            <div class="service-card-bg"></div>
            <div class="service-actions">
                <button class="btn-action js-edit-service" data-id="{{ $servico->id }}" title="Editar">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="btn-action danger js-delete-service" data-id="{{ $servico->id }}" data-name="{{ $servico->nome }}" title="Excluir">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <h3 class="service-name">{{ $servico->nome }}</h3>
            <p class="service-description">{{ $servico->descricao }}</p>
            <div class="service-details">
                <div class="service-detail">
                    <i class="fas fa-clock"></i>
                    <span>{{ $servico->duracao_minutos }} minutos</span>
                </div>
                <div class="service-detail">
                    <i class="fas fa-tag"></i>
                    <span>R$ {{ number_format($servico->preco, 2, ',', '.') }}</span>
                </div>
            </div>
            <div class="service-price">R$ {{ number_format($servico->preco, 2, ',', '.') }}</div>
            <form class="status-toggle-form" action="{{ route('servicos.toggle-status', $servico) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="status-toggle {{ $servico->ativo ? 'active' : 'inactive' }}">
                    <i class="fas fa-{{ $servico->ativo ? 'check' : 'times' }}"></i>
                    {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                </button>
            </form>
        </div>
    @empty
        <div class="empty-state">
            <i class="fas fa-cut"></i>
            <p>Nenhum serviço cadastrado ainda.</p>
            <button class="btn-gold" onclick="openServiceModal()">
                <i class="fas fa-plus"></i> Adicionar Primeiro Serviço
            </button>
        </div>
    @endforelse
</div>

{{-- ════════ MODAL: CRIAR / EDITAR ════════ --}}
<div id="serviceModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i id="modalIcon" class="fas fa-cut"></i>
                <span id="serviceModalTitle">Novo Serviço</span>
            </h2>
            <button class="modal-close" onclick="closeServiceModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="serviceForm" action="{{ route('servicos.store') }}" method="POST">
            @csrf
            <input type="hidden" id="serviceId" name="id">
            <input type="hidden" id="serviceMethod" name="_method" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label for="nome">Nome do Serviço</label>
                    <div class="input-wrap">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="nome" name="nome" class="has-icon"
                               placeholder="Ex: Corte Masculino" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3" class="form-control"
                              placeholder="Descreva os detalhes do serviço..." required></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="duracao_minutos">Duração (min)</label>
                        <input type="number" id="duracao_minutos" name="duracao_minutos" class="form-control"
                               placeholder="30" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço (R$)</label>
                        <input type="text" id="preco" name="preco" class="form-control"
                               placeholder="35,00" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeServiceModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn-gold" id="serviceSubmitBtn">
                    <i class="fas fa-save"></i>
                    <span id="serviceSubmitText">Criar Serviço</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════ MODAL: EXCLUIR ════════ --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal-box delete-modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle" style="color:#D97070;"></i>
                Confirmar Exclusão
            </h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="delete-modal-body">
            <div class="delete-icon-ring">
                <i class="fas fa-trash"></i>
            </div>
            <p class="delete-modal-title">Excluir serviço?</p>
            <div class="delete-service-pill">
                <i class="fas fa-cut"></i>
                <span id="deleteServiceName">—</span>
            </div>
            <p class="delete-modal-desc">
                Esta ação é <strong style="color:var(--text-primary);">irreversível</strong>.
                Agendamentos futuros que utilizam este serviço podem ser afetados.
            </p>
        </div>

        <div class="modal-footer" style="justify-content:space-between;">
            <button type="button" class="btn-ghost" onclick="closeDeleteModal()">
                <i class="fas fa-arrow-left"></i> Cancelar
            </button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" id="deleteSubmitBtn" class="btn-danger-solid">
                    <i class="fas fa-trash"></i> Sim, excluir
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── HELPERS ─────────────────────────────────────────── */
    const openModal  = id => { document.getElementById(id).classList.add('active');    document.body.style.overflow='hidden'; };
    const closeModal = id => { document.getElementById(id).classList.remove('active'); document.body.style.overflow='';       };

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) closeModal(overlay.id);
        });
    });

    function showToast(msg, type = 'success') {
        const t    = document.getElementById('toast');
        const icon = document.getElementById('toastIcon');
        document.getElementById('toastMsg').textContent = msg;
        t.className = `toast ${type}`;
        icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3800);
    }

    /* ── OPEN / CLOSE MODALS ─────────────────────────────── */
    window.openServiceModal = () => {
        document.getElementById('serviceModalTitle').textContent = 'Novo Serviço';
        document.getElementById('serviceSubmitText').textContent  = 'Criar Serviço';
        document.getElementById('modalIcon').className           = 'fas fa-plus';
        document.getElementById('serviceForm').reset();
        document.getElementById('serviceForm').action = '{{ route('servicos.store') }}';
        document.getElementById('serviceMethod').value = 'POST';
        const btn = document.getElementById('serviceSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> <span>Criar Serviço</span>';
        openModal('serviceModal');
        setTimeout(() => document.getElementById('nome').focus(), 260);
    };

    window.closeServiceModal = () => closeModal('serviceModal');
    window.closeDeleteModal = () => closeModal('deleteModal');

    /* ── EDIT SERVICE ─────────────────────────────────────── */
    window.editService = id => {
        document.getElementById('serviceModalTitle').textContent = 'Carregando...';
        document.getElementById('modalIcon').className = 'fas fa-spinner fa-spin';
        openModal('serviceModal');

        fetch(`/servicos/${id}/json`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            document.getElementById('serviceModalTitle').textContent = 'Editar Serviço';
            document.getElementById('serviceSubmitText').textContent  = 'Salvar Alterações';
            document.getElementById('modalIcon').className           = 'fas fa-pen';

            document.getElementById('serviceId').value        = data.id;
            document.getElementById('nome').value            = data.nome ?? '';
            document.getElementById('descricao').value      = data.descricao ?? '';
            document.getElementById('duracao_minutos').value = data.duracao_minutos ?? '';
            document.getElementById('preco').value           = (data.preco ?? '').replace('.', ',');

            document.getElementById('serviceForm').action = `/servicos/${id}`;
            document.getElementById('serviceMethod').value = 'PUT';

            const btn = document.getElementById('serviceSubmitBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span id="serviceSubmitText">Salvar Alterações</span>';
        })
        .catch(err => {
            closeServiceModal();
            showToast('Erro ao carregar dados do serviço.', 'error');
            console.error(err);
        });
    };

    /* ── DELETE MODAL ────────────────────────────────────── */
    window.openDeleteModal = (id, name) => {
        document.getElementById('deleteServiceName').textContent = name;
        document.getElementById('deleteForm').action = `/servicos/${id}`;
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash"></i> Sim, excluir';
        openModal('deleteModal');
    };

    /* ── EVENT DELEGATION ───────────────────────────────── */
    document.querySelector('.services-grid').addEventListener('click', e => {
        const editBtn   = e.target.closest('.js-edit-service');
        const deleteBtn = e.target.closest('.js-delete-service');
        if (editBtn)   editService(editBtn.dataset.id);
        if (deleteBtn) openDeleteModal(deleteBtn.dataset.id, deleteBtn.dataset.name);
    });

    /* ── FORM SUBMIT FEEDBACK ────────────────────────────── */
    document.getElementById('serviceForm').addEventListener('submit', function () {
        const btn = document.getElementById('serviceSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    });

    document.getElementById('deleteForm').addEventListener('submit', function () {
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Excluindo...';
    });

    /* ── ESC ─────────────────────────────────────────────── */
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (document.getElementById('serviceModal').classList.contains('active')) closeServiceModal();
        if (document.getElementById('deleteModal').classList.contains('active'))  closeDeleteModal();
    });

    /* ── SESSION FLASH ───────────────────────────────────── */
    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
});
</script>
@endpush