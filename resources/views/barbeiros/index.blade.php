@extends('layouts.app')

@section('title', 'Barbeiros')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --gold: #C9A84C; --gold-light: #E8C96A; --gold-dim: #8B6914;
        --dark: #0D0D0D; --dark-card: #141414; --dark-elevated: #1C1C1C;
        --dark-border: #2A2A2A; --text-primary: #F0EDE8;
        --text-muted: #6B6560; --text-dim: #9C9690;
    }

    body { background:var(--dark); color:var(--text-primary); font-family:'DM Sans',sans-serif; }

    /* PAGE HEADER */
    .page-header {
        display:flex; justify-content:space-between; align-items:flex-end;
        margin-bottom:2rem; padding-bottom:1.25rem;
        border-bottom:1px solid var(--dark-border); position:relative;
    }
    .page-header::after {
        content:''; position:absolute; bottom:-1px; left:0;
        width:80px; height:2px; background:var(--gold);
    }
    .page-title {
        font-family:'Playfair Display',serif; font-size:1.85rem; font-weight:700;
        color:var(--text-primary); letter-spacing:-0.02em; margin:0;
        display:flex; align-items:center; gap:0.6rem;
    }
    .page-title i { color:var(--gold); }

    /* COUNTER PILL */
    .barber-count {
        font-size:0.72rem; font-weight:700;
        background:rgba(201,168,76,0.12); color:var(--gold);
        border:1px solid rgba(201,168,76,0.2); padding:0.2rem 0.65rem;
        border-radius:20px; margin-left:0.5rem; vertical-align:middle;
    }

    /* BUTTONS */
    .btn-gold {
        background:var(--gold); color:#0D0D0D; border:none; padding:0.6rem 1.3rem;
        border-radius:7px; font-family:'DM Sans',sans-serif; font-weight:600; font-size:0.875rem;
        cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;
        text-decoration:none; transition:background 0.2s, transform 0.15s;
    }
    .btn-gold:hover { background:var(--gold-light); color:#0D0D0D; transform:translateY(-1px); }
    .btn-ghost {
        background:transparent; color:var(--text-dim); border:1px solid var(--dark-border);
        padding:0.5rem 1.1rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:500; font-size:0.875rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; transition:border-color 0.2s, color 0.2s;
    }
    .btn-ghost:hover { border-color:var(--gold-dim); color:var(--gold); }
    .btn-danger-solid {
        background:rgba(139,51,51,0.9); color:#F0EDE8; border:1px solid #8B3333;
        padding:0.5rem 1.1rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:600; font-size:0.875rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; transition:background 0.2s, transform 0.15s;
    }
    .btn-danger-solid:hover { background:#A04040; transform:translateY(-1px); }

    /* GRID */
    .barbers-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(270px, 1fr));
        gap:1.25rem;
    }

    /* BARBER CARD */
    .barber-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:16px; overflow:hidden; position:relative;
        transition:border-color 0.25s, transform 0.22s, box-shadow 0.25s;
        display:flex; flex-direction:column;
    }
    .barber-card:hover {
        border-color:var(--gold-dim); transform:translateY(-4px);
        box-shadow:0 12px 40px rgba(0,0,0,0.5);
    }
    /* top gold line on hover */
    .barber-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:2px;
        background:linear-gradient(90deg, var(--gold-dim), var(--gold-light));
        opacity:0; transition:opacity 0.25s; z-index:1;
    }
    .barber-card:hover::before { opacity:1; }

    /* BACKGROUND PATTERN subtle */
    .barber-card-bg {
        position:absolute; top:0; left:0; right:0; height:100px;
        background:radial-gradient(ellipse at 80% 0%, rgba(201,168,76,0.06) 0%, transparent 70%);
        pointer-events:none;
    }

    /* ACTION BUTTONS */
    .barber-actions {
        position:absolute; top:0.85rem; right:0.85rem;
        display:flex; gap:0.3rem; opacity:0; transition:opacity 0.2s; z-index:2;
    }
    .barber-card:hover .barber-actions { opacity:1; }
    .btn-action {
        width:30px; height:30px; display:flex; align-items:center; justify-content:center;
        border-radius:6px; border:1px solid var(--dark-border);
        color:var(--text-muted); background:rgba(20,20,20,0.9);
        font-size:0.72rem; cursor:pointer; transition:all 0.18s;
        backdrop-filter:blur(4px);
    }
    .btn-action:hover        { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.12); }
    .btn-action.danger:hover { border-color:#8B3333; color:#D97070; background:rgba(139,51,51,0.15); }

    /* CARD TOP */
    .barber-card-top {
        padding:1.75rem 1.5rem 1rem;
        display:flex; flex-direction:column; align-items:center;
        text-align:center; gap:0.85rem; position:relative; z-index:1;
    }

    /* AVATAR */
    .barber-avatar {
        width:80px; height:80px; border-radius:50%;
        border:2px solid var(--dark-border); background:var(--dark-elevated);
        display:flex; align-items:center; justify-content:center;
        overflow:hidden; flex-shrink:0;
        transition:border-color 0.25s, box-shadow 0.25s;
        position:relative;
    }
    .barber-card:hover .barber-avatar {
        border-color:var(--gold-dim);
        box-shadow:0 0 0 4px rgba(201,168,76,0.08);
    }
    .barber-avatar img { width:100%; height:100%; object-fit:cover; }
    .barber-avatar-initials {
        font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700;
        color:var(--gold); letter-spacing:-0.02em;
    }

    /* Active indicator dot */
    .avatar-status {
        position:absolute; bottom:3px; right:3px;
        width:12px; height:12px; border-radius:50%;
        background:#3D8B68; border:2px solid var(--dark-card);
    }

    .barber-name {
        font-family:'Playfair Display',serif; font-size:1.1rem; font-weight:700;
        color:var(--text-primary); line-height:1.2;
    }

    /* DIVIDER */
    .barber-divider { height:1px; background:var(--dark-border); margin:0 1.5rem; }

    /* DETAILS */
    .barber-details {
        padding:1rem 1.5rem 1.5rem;
        display:flex; flex-direction:column; gap:0.7rem;
        flex:1;
    }
    .barber-detail {
        display:flex; align-items:flex-start; gap:0.65rem;
        font-size:0.82rem; color:var(--text-dim);
    }
    .barber-detail i {
        width:16px; text-align:center; font-size:0.72rem;
        color:var(--gold-dim); flex-shrink:0; margin-top:2px;
    }
    .barber-detail span { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

    /* SPECIALTY TAGS */
    .specialty-tags { display:flex; flex-wrap:wrap; gap:0.3rem; }
    .specialty-tag {
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        color:var(--text-dim); padding:0.15rem 0.55rem;
        border-radius:20px; font-size:0.67rem; font-weight:500;
        transition:border-color 0.2s, color 0.2s;
    }
    .barber-card:hover .specialty-tag { border-color:rgba(201,168,76,0.15); }

    /* SCHEDULE BADGE */
    .schedule-badge {
        display:inline-flex; align-items:center; gap:0.4rem;
        background:rgba(201,168,76,0.08); border:1px solid rgba(201,168,76,0.18);
        color:var(--gold); padding:0.3rem 0.75rem; border-radius:20px;
        font-size:0.72rem; font-weight:600; align-self:center; margin-top:0.1rem;
    }
    .schedule-badge i { font-size:0.62rem; }

    /* EMPTY STATE */
    .empty-state {
        grid-column:1/-1; text-align:center; padding:5rem 2rem;
        background:var(--dark-card); border:1px dashed var(--dark-border); border-radius:16px;
    }
    .empty-state i { font-size:3rem; color:var(--dark-border); display:block; margin-bottom:1rem; }
    .empty-state p { font-size:0.9rem; color:var(--text-muted); margin-bottom:1.5rem; }

    /* ════════ MODALS ════════ */
    .modal-overlay {
        display:none; position:fixed; inset:0; z-index:1000;
        background:rgba(0,0,0,0.82); backdrop-filter:blur(8px);
        align-items:center; justify-content:center; padding:1.5rem;
    }
    .modal-overlay.active { display:flex; }

    .modal-box {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:18px; width:100%; max-width:480px;
        box-shadow:0 32px 80px rgba(0,0,0,0.7);
        animation:modalIn 0.22s cubic-bezier(.34,1.3,.64,1);
        overflow:hidden;
    }
    @keyframes modalIn {
        from { opacity:0; transform:scale(0.93) translateY(20px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }

    .modal-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1.25rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated); position:relative;
    }
    /* Gold accent line on modal header */
    .modal-header::after {
        content:''; position:absolute; bottom:0; left:1.5rem;
        width:40px; height:2px; background:var(--gold);
    }
    .modal-title {
        font-family:'Playfair Display',serif; font-size:1.05rem; font-weight:700;
        color:var(--text-primary); margin:0; display:flex; align-items:center; gap:0.5rem;
    }
    .modal-title i { color:var(--gold); font-size:0.95rem; }
    .modal-close {
        width:32px; height:32px; border-radius:7px;
        border:1px solid var(--dark-border); background:transparent;
        color:var(--text-muted); cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        font-size:0.82rem; transition:all 0.18s;
    }
    .modal-close:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.08); }

    .modal-body { padding:1.5rem; display:flex; flex-direction:column; gap:1.1rem; }

    .form-group { display:flex; flex-direction:column; gap:0.4rem; }
    .form-group label {
        font-size:0.63rem; font-weight:700; text-transform:uppercase;
        letter-spacing:0.09em; color:var(--text-muted);
        display:flex; align-items:center; gap:0.35rem;
    }
    .form-group label .label-hint {
        font-weight:400; text-transform:none; letter-spacing:0;
        font-size:0.62rem; color:var(--text-muted); opacity:0.7;
    }
    .input-wrap { position:relative; }
    .input-wrap i {
        position:absolute; left:0.9rem; top:50%; transform:translateY(-50%);
        font-size:0.72rem; color:var(--text-muted); pointer-events:none;
    }
    .form-group input {
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        color:var(--text-primary); border-radius:8px; padding:0.6rem 0.9rem;
        font-family:'DM Sans',sans-serif; font-size:0.875rem; width:100%;
        transition:border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input.has-icon { padding-left:2.4rem; }
    .form-group input:focus {
        outline:none; border-color:var(--gold-dim);
        box-shadow:0 0 0 3px rgba(201,168,76,0.1);
    }
    .form-group input::placeholder { color:var(--text-muted); opacity:0.6; }

    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }

    .modal-footer {
        display:flex; justify-content:flex-end; gap:0.6rem;
        padding:1rem 1.5rem; border-top:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }

    /* DELETE MODAL */
    .delete-modal-box { max-width:420px; }
    .delete-modal-body {
        padding:2.25rem 1.5rem 1.5rem; text-align:center;
        display:flex; flex-direction:column; align-items:center; gap:1rem;
    }
    .delete-icon-ring {
        width:72px; height:72px; border-radius:50%;
        background:rgba(139,51,51,0.1); border:2px solid rgba(139,51,51,0.25);
        display:flex; align-items:center; justify-content:center;
        font-size:1.7rem; color:#D97070;
        animation:pulseRed 2s ease-in-out infinite;
    }
    @keyframes pulseRed {
        0%,100% { box-shadow:0 0 0 0 rgba(139,51,51,0.2); }
        50%      { box-shadow:0 0 0 8px rgba(139,51,51,0); }
    }
    .delete-modal-title {
        font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:700;
        color:var(--text-primary); margin:0;
    }
    .delete-modal-desc { font-size:0.83rem; color:var(--text-muted); line-height:1.65; margin:0; }
    .delete-barber-pill {
        display:inline-flex; align-items:center; gap:0.4rem;
        background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.22);
        color:var(--gold); padding:0.3rem 0.85rem; border-radius:20px;
        font-weight:600; font-size:0.85rem;
    }

    /* TOAST */
    .toast {
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:2000;
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        border-radius:10px; padding:0.9rem 1.25rem;
        display:flex; align-items:center; gap:0.65rem;
        font-size:0.84rem; color:var(--text-primary);
        box-shadow:0 8px 32px rgba(0,0,0,0.5);
        transform:translateY(120%); transition:transform 0.3s cubic-bezier(.34,1.3,.64,1);
        min-width:260px;
    }
    .toast.show { transform:translateY(0); }
    .toast.success { border-left:3px solid #5DBF95; }
    .toast.error   { border-left:3px solid #D97070; }
    .toast.success i { color:#5DBF95; }
    .toast.error   i { color:#D97070; }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding:1.5rem; max-width:1400px;">

    {{-- HEADER --}}
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-tie"></i>
            Barbeiros
            @if($barbeiros->isNotEmpty())
                <span class="barber-count">{{ $barbeiros->count() }}</span>
            @endif
        </h1>
        <button class="btn-gold" onclick="openBarberModal()">
            <i class="fas fa-plus"></i> Adicionar Barbeiro
        </button>
    </div>

    {{-- GRID --}}
    <div class="barbers-grid">
        @forelse($barbeiros as $barbeiro)
        @php
            $initials = collect(explode(' ', $barbeiro->nome))
                ->filter()->map(fn($w) => strtoupper($w[0]))->take(2)->implode('');
        @endphp
        <div class="barber-card">
            <div class="barber-card-bg"></div>

            {{-- Ações (aparecem no hover) --}}
            <div class="barber-actions">
                <button class="btn-action js-edit-barber"
                        data-id="{{ $barbeiro->id }}"
                        title="Editar">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="btn-action danger js-delete-barber"
                        data-id="{{ $barbeiro->id }}"
                        data-name="{{ $barbeiro->nome }}"
                        title="Excluir">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            {{-- Avatar + nome --}}
            <div class="barber-card-top">
                <div class="barber-avatar">
                    @php $avatarPath = public_path('images/barbeiros/' . $barbeiro->id . '.jpg'); @endphp
                    @if(file_exists($avatarPath))
                        <img src="{{ asset('images/barbeiros/' . $barbeiro->id . '.jpg') }}" alt="{{ $barbeiro->nome }}">
                    @else
                        <span class="barber-avatar-initials">{{ $initials }}</span>
                    @endif
                    <span class="avatar-status" title="Ativo"></span>
                </div>
                <div class="barber-name">{{ $barbeiro->nome }}</div>
            </div>

            <div class="barber-divider"></div>

            {{-- Detalhes --}}
            <div class="barber-details">
                <div class="barber-detail">
                    <i class="fas fa-phone"></i>
                    <span>{{ $barbeiro->telefone }}</span>
                </div>

                @if($barbeiro->especialidades)
                @php $tags = array_map('trim', explode(',', $barbeiro->especialidades)); @endphp
                <div class="barber-detail">
                    <i class="fas fa-cut"></i>
                    <div class="specialty-tags">
                        @foreach($tags as $tag)
                            <span class="specialty-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div style="display:flex; justify-content:center; margin-top:auto; padding-top:0.5rem;">
                    <span class="schedule-badge">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($barbeiro->inicio_trabalho)->format('H:i') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($barbeiro->fim_trabalho)->format('H:i') }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-user-tie"></i>
            <p>Nenhum barbeiro cadastrado ainda.</p>
            <button class="btn-gold" onclick="openBarberModal()">
                <i class="fas fa-plus"></i> Adicionar Primeiro Barbeiro
            </button>
        </div>
        @endforelse
    </div>
</div>

{{-- ════════ MODAL: CRIAR / EDITAR ════════ --}}
<div id="barberModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i id="modalIcon" class="fas fa-user-tie"></i>
                <span id="barberModalTitle">Novo Barbeiro</span>
            </h2>
            <button class="modal-close" onclick="closeBarberModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="barberForm" action="{{ route('barbeiros.store') }}" method="POST">
            @csrf
            <input type="hidden" id="barberId" name="id">
            <input type="hidden" id="barberMethod" name="_method" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label for="nome">Nome completo</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nome" name="nome" class="has-icon"
                               placeholder="Ex: Carlos Silva" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone / WhatsApp</label>
                    <div class="input-wrap">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="telefone" name="telefone" class="has-icon"
                               placeholder="(11) 99999-9999" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="especialidades">
                        Especialidades
                        <span class="label-hint">— separadas por vírgula</span>
                    </label>
                    <div class="input-wrap">
                        <i class="fas fa-cut"></i>
                        <input type="text" id="especialidades" name="especialidades" class="has-icon"
                               placeholder="Cortes clássicos, Barba, Pigmentação" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="inicio_trabalho">
                            <i class="fas fa-sun" style="color:var(--gold-dim); font-size:0.6rem;"></i>
                            Início do turno
                        </label>
                        <input type="time" id="inicio_trabalho" name="inicio_trabalho" required>
                    </div>
                    <div class="form-group">
                        <label for="fim_trabalho">
                            <i class="fas fa-moon" style="color:var(--gold-dim); font-size:0.6rem;"></i>
                            Fim do turno
                        </label>
                        <input type="time" id="fim_trabalho" name="fim_trabalho" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeBarberModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn-gold" id="barberSubmitBtn">
                    <i class="fas fa-save"></i>
                    <span id="barberSubmitText">Criar Barbeiro</span>
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
            <p class="delete-modal-title">Excluir barbeiro?</p>
            <div class="delete-barber-pill">
                <i class="fas fa-user"></i>
                <span id="deleteBarberName">—</span>
            </div>
            <p class="delete-modal-desc">
                Esta ação é <strong style="color:var(--text-primary);">irreversível</strong>.
                Todos os agendamentos futuros vinculados a este barbeiro serão afetados.
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

{{-- TOAST --}}
<div id="toast" class="toast">
    <i id="toastIcon" class="fas fa-check-circle"></i>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ── HELPERS ─────────────────────────────────────────── */
    const openModal  = id => { document.getElementById(id).classList.add('active');    document.body.style.overflow='hidden'; };
    const closeModal = id => { document.getElementById(id).classList.remove('active'); document.body.style.overflow='';       };

    // Fechar ao clicar no overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.classList.contains('active') && overlay.classList.remove('active') || (document.body.style.overflow = '');
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
    window.openBarberModal = () => {
        document.getElementById('barberModalTitle').textContent = 'Novo Barbeiro';
        document.getElementById('barberSubmitText').textContent  = 'Criar Barbeiro';
        document.getElementById('modalIcon').className           = 'fas fa-user-plus';
        document.getElementById('barberForm').reset();
        document.getElementById('barberForm').action = '{{ route('barbeiros.store') }}';
        document.getElementById('barberMethod').value = 'POST';
        const btn = document.getElementById('barberSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> <span>Criar Barbeiro</span>';
        openModal('barberModal');
        setTimeout(() => document.getElementById('nome').focus(), 260);
    };

    window.closeBarberModal = () => closeModal('barberModal');
    window.closeDeleteModal = () => closeModal('deleteModal');

    /* ── EDIT BARBER ─────────────────────────────────────── */
    window.editBarber = id => {
        document.getElementById('barberModalTitle').textContent = 'Carregando...';
        document.getElementById('modalIcon').className = 'fas fa-spinner fa-spin';
        openModal('barberModal');

        fetch(`/barbeiros/${id}/json`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            document.getElementById('barberModalTitle').textContent = 'Editar Barbeiro';
            document.getElementById('barberSubmitText').textContent  = 'Salvar Alterações';
            document.getElementById('modalIcon').className           = 'fas fa-pen';

            document.getElementById('barberId').value        = data.id;
            document.getElementById('nome').value            = data.nome ?? '';
            document.getElementById('telefone').value        = data.telefone ?? '';
            document.getElementById('especialidades').value  = data.especialidades ?? '';
            document.getElementById('inicio_trabalho').value = (data.inicio_trabalho ?? '').substring(0, 5);
            document.getElementById('fim_trabalho').value    = (data.fim_trabalho ?? '').substring(0, 5);

            document.getElementById('barberForm').action = `/barbeiros/${id}`;
            document.getElementById('barberMethod').value = 'PUT';

            const btn = document.getElementById('barberSubmitBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span id="barberSubmitText">Salvar Alterações</span>';
        })
        .catch(err => {
            closeBarberModal();
            showToast('Erro ao carregar dados do barbeiro.', 'error');
            console.error(err);
        });
    };

    /* ── DELETE MODAL ────────────────────────────────────── */
    window.openDeleteModal = (id, name) => {
        document.getElementById('deleteBarberName').textContent = name;
        document.getElementById('deleteForm').action = `/barbeiros/${id}`;
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash"></i> Sim, excluir';
        openModal('deleteModal');
    };

    /* ── EVENT DELEGATION para cards dinâmicos ───────────── */
    document.querySelector('.barbers-grid').addEventListener('click', e => {
        const editBtn   = e.target.closest('.js-edit-barber');
        const deleteBtn = e.target.closest('.js-delete-barber');
        if (editBtn)   editBarber(editBtn.dataset.id);
        if (deleteBtn) openDeleteModal(deleteBtn.dataset.id, deleteBtn.dataset.name);
    });

    /* ── FORM SUBMIT FEEDBACK ────────────────────────────── */
    document.getElementById('barberForm').addEventListener('submit', function () {
        const btn = document.getElementById('barberSubmitBtn');
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
        if (document.getElementById('barberModal').classList.contains('active'))  closeBarberModal();
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