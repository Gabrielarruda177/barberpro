@extends('layouts.app')

@section('title', 'Agenda do Dia')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --gold: #C9A84C; --gold-light: #E8C96A; --gold-dim: #8B6914;
        --dark: #0D0D0D; --dark-card: #141414; --dark-elevated: #1C1C1C;
        --dark-border: #2A2A2A; --text-primary: #F0EDE8;
        --text-muted: #6B6560; --text-dim: #9C9690;
        --c-ag: #5BA3D9; --c-ag-bg: rgba(45,111,163,0.12);
        --c-ok: #5DBF95; --c-ok-bg: rgba(61,139,104,0.12);
        --c-no: #D97070; --c-no-bg: rgba(139,51,51,0.12);
    }

    body { background:var(--dark); color:var(--text-primary); font-family:'DM Sans',sans-serif; }

    /* ─── PAGE HEADER ─── */
    .page-header {
        display:flex; justify-content:space-between; align-items:flex-end;
        margin-bottom:1.75rem; padding-bottom:1.25rem;
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
    .page-subtitle { font-size:0.78rem; color:var(--text-muted); margin-top:0.3rem; text-transform:capitalize; }

    /* ─── BUTTONS ─── */
    .btn-gold {
        background:var(--gold); color:#0D0D0D; border:none; padding:0.55rem 1.2rem;
        border-radius:7px; font-family:'DM Sans',sans-serif; font-weight:600; font-size:0.85rem;
        cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;
        text-decoration:none; transition:background 0.2s, transform 0.15s;
    }
    .btn-gold:hover { background:var(--gold-light); color:#0D0D0D; transform:translateY(-1px); }
    .btn-ghost {
        background:transparent; color:var(--text-dim); border:1px solid var(--dark-border);
        padding:0.5rem 1.1rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:500; font-size:0.85rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; text-decoration:none; transition:all 0.2s;
    }
    .btn-ghost:hover { border-color:var(--gold-dim); color:var(--gold); }

    /* ─── STAT CARDS ─── */
    .stats-row {
        display:grid; grid-template-columns:repeat(auto-fit, minmax(130px,1fr));
        gap:1rem; margin-bottom:1.5rem;
    }
    .stat-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:12px; padding:1rem 1.25rem;
        display:flex; flex-direction:column; gap:0.25rem;
    }
    .stat-card-label {
        font-size:0.63rem; font-weight:700; text-transform:uppercase;
        letter-spacing:0.09em; color:var(--text-muted);
    }
    .stat-card-value {
        font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700;
        color:var(--text-primary); line-height:1;
    }

    /* ─── APPOINTMENT LIST CARD ─── */
    .list-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:14px; overflow:hidden;
    }
    .list-card-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .list-card-title {
        font-family:'Playfair Display',serif; font-size:1rem; font-weight:700;
        color:var(--text-primary); margin:0;
    }
    .count-pill {
        font-size:0.7rem; font-weight:700;
        background:rgba(201,168,76,0.1); color:var(--gold);
        border:1px solid rgba(201,168,76,0.2);
        padding:0.2rem 0.65rem; border-radius:20px;
    }

    /* ─── APPOINTMENT ITEM ─── */
    .appointment-item {
        display:grid;
        grid-template-columns: 70px 1fr auto auto;
        align-items:center; gap:1rem;
        padding:1rem 1.5rem;
        border-bottom:1px solid rgba(255,255,255,0.04);
        border-left:3px solid transparent;
        transition:background 0.15s, border-color 0.15s;
    }
    .appointment-item:last-child { border-bottom:none; }
    .appointment-item:hover { background:rgba(201,168,76,0.025); }
    .appointment-item.status-agendado  { border-left-color: var(--c-ag); }
    .appointment-item.status-concluido { border-left-color: var(--c-ok); }
    .appointment-item.status-cancelado { border-left-color: var(--c-no); }

    /* Time column */
    .appt-time {
        text-align:center; padding-right:1rem;
        border-right:1px solid var(--dark-border);
    }
    .appt-time-label {
        font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em;
        color:var(--text-muted); font-weight:700; margin-bottom:0.05rem;
    }
    .appt-time-val {
        font-family:'Playfair Display',serif; font-size:1.05rem;
        font-weight:700; color:var(--gold); line-height:1;
    }

    /* Info column */
    .appt-client {
        font-weight:600; font-size:0.9rem; color:var(--text-primary); margin-bottom:0.15rem;
    }
    .appt-meta {
        font-size:0.75rem; color:var(--text-muted);
        display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;
    }
    .appt-meta-item { display:flex; align-items:center; gap:0.3rem; }
    .appt-meta-item i { font-size:0.65rem; color:var(--gold-dim); }

    /* Barber column */
    .appt-barber {
        font-size:0.78rem; color:var(--text-dim); font-weight:500;
        white-space:nowrap; display:flex; align-items:center; gap:0.35rem;
    }
    .appt-barber i { font-size:0.65rem; color:var(--gold-dim); }

    /* Status badge */
    .status-badge {
        display:inline-flex; align-items:center; gap:0.3rem;
        padding:0.2rem 0.6rem; border-radius:20px;
        font-size:0.67rem; font-weight:700; letter-spacing:0.04em; white-space:nowrap;
    }
    .status-badge::before {
        content:''; width:5px; height:5px; border-radius:50%;
    }
    .status-badge.status-agendado  { background:var(--c-ag-bg); color:var(--c-ag); }
    .status-badge.status-agendado::before  { background:var(--c-ag); }
    .status-badge.status-concluido { background:var(--c-ok-bg); color:var(--c-ok); }
    .status-badge.status-concluido::before { background:var(--c-ok); }
    .status-badge.status-cancelado { background:var(--c-no-bg); color:var(--c-no); }
    .status-badge.status-cancelado::before { background:var(--c-no); }

    /* Actions */
    .appt-actions { display:flex; align-items:center; gap:0.4rem; }
    .btn-icon {
        width:30px; height:30px; border-radius:6px;
        border:1px solid var(--dark-border); background:transparent;
        color:var(--text-muted); font-size:0.72rem; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        text-decoration:none; transition:all 0.18s;
    }
    .btn-icon:hover      { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.08); }
    .btn-icon.danger:hover { border-color:rgba(139,51,51,0.5); color:var(--c-no); background:rgba(139,51,51,0.12); }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        text-align:center; padding:4rem 2rem; color:var(--text-muted);
    }
    .empty-state i { font-size:2.5rem; color:var(--dark-border); display:block; margin-bottom:1rem; }
    .empty-state p { font-size:0.9rem; margin-bottom:1.5rem; }

    /* ─── DELETE MODAL ─── */
    .modal-overlay {
        position:fixed; inset:0; background:rgba(0,0,0,0.82);
        backdrop-filter:blur(8px);
        display:none; align-items:center; justify-content:center;
        z-index:1000; padding:1.5rem;
    }
    .modal-overlay.active { display:flex; }
    .modal-box {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:18px; width:100%; max-width:400px;
        box-shadow:0 32px 80px rgba(0,0,0,0.7);
        animation:modalIn 0.22s cubic-bezier(.34,1.3,.64,1);
        overflow:hidden;
    }
    @keyframes modalIn {
        from { opacity:0; transform:scale(0.93) translateY(18px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    .modal-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1.1rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .modal-title {
        font-family:'Playfair Display',serif; font-size:1rem; font-weight:700;
        color:var(--text-primary); margin:0; display:flex; align-items:center; gap:0.5rem;
    }
    .modal-close {
        width:28px; height:28px; border-radius:6px;
        border:1px solid var(--dark-border); background:transparent;
        color:var(--text-muted); cursor:pointer;
        display:flex; align-items:center; justify-content:center; font-size:0.78rem;
        transition:all 0.18s;
    }
    .modal-close:hover { border-color:var(--gold-dim); color:var(--gold); }
    .modal-body {
        padding:2rem 1.5rem 1.5rem; text-align:center;
        display:flex; flex-direction:column; align-items:center; gap:1rem;
    }
    .modal-icon-ring {
        width:68px; height:68px; border-radius:50%;
        background:rgba(139,51,51,0.12); border:2px solid rgba(139,51,51,0.25);
        display:flex; align-items:center; justify-content:center;
        font-size:1.6rem; color:var(--c-no);
        animation:pulseRed 2s ease-in-out infinite;
    }
    @keyframes pulseRed {
        0%,100% { box-shadow:0 0 0 0 rgba(217,112,112,0.2); }
        50%      { box-shadow:0 0 0 8px rgba(217,112,112,0); }
    }
    .modal-body h3 {
        font-family:'Playfair Display',serif; font-size:1.15rem; font-weight:700;
        color:var(--text-primary); margin:0;
    }
    .modal-client-pill {
        display:inline-flex; align-items:center; gap:0.4rem;
        background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2);
        color:var(--gold); padding:0.3rem 0.85rem; border-radius:20px;
        font-weight:600; font-size:0.85rem;
    }
    .modal-desc { font-size:0.82rem; color:var(--text-muted); line-height:1.6; margin:0; }
    .modal-footer {
        display:flex; justify-content:space-between; gap:0.6rem;
        padding:1rem 1.5rem; border-top:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .btn-danger-solid {
        background:rgba(139,51,51,0.85); color:#F0EDE8; border:1px solid rgba(139,51,51,0.5);
        padding:0.5rem 1.2rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:600; font-size:0.85rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; transition:background 0.2s;
    }
    .btn-danger-solid:hover { background:#A04040; }

    /* ─── TOAST ─── */
    .toast {
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:2000;
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        border-radius:10px; padding:0.9rem 1.25rem;
        display:flex; align-items:center; gap:0.65rem;
        font-size:0.83rem; color:var(--text-primary);
        box-shadow:0 8px 32px rgba(0,0,0,0.6);
        transform:translateY(120%); transition:transform 0.3s cubic-bezier(.34,1.3,.64,1);
        min-width:240px;
    }
    .toast.show { transform:translateY(0); }
    .toast.success { border-left:3px solid var(--c-ok); }
    .toast.success i { color:var(--c-ok); }

    @media(max-width:640px) {
        .appointment-item { grid-template-columns:60px 1fr; }
        .appt-barber, .appt-actions { display:none; }
        .appointment-item .appt-actions { display:flex; grid-column:2; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding:1.5rem; max-width:1100px;">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-calendar-day"></i>
                {{ $data->translatedFormat('d \d\e F') }}
            </h1>
            <div class="page-subtitle">{{ $data->translatedFormat('l, Y') }}</div>
        </div>
        <div style="display:flex; gap:0.6rem; align-items:center;">
            <a href="{{ route('agenda', ['data' => $data->format('Y-m')]) }}" class="btn-ghost">
                <i class="fas fa-arrow-left"></i> Agenda
            </a>
            <a href="{{ route('agendamentos.create') }}?data={{ $data->format('Y-m-d') }}" class="btn-gold">
                <i class="fas fa-plus"></i> Novo
            </a>
        </div>
    </div>

    {{-- STAT CARDS --}}
    @php
        $total     = $agendamentos->count();
        $agendados = $agendamentos->where('status', 'agendado')->count();
        $concluidos= $agendamentos->where('status', 'concluido')->count();
        $cancelados= $agendamentos->where('status', 'cancelado')->count();
        $faturamento = $agendamentos->where('status', 'concluido')
                        ->sum(fn($ag) => $ag->servico?->preco ?? $ag->valor ?? 0);
    @endphp

    @if($total > 0)
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-card-label">Total</div>
            <div class="stat-card-value">{{ $total }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Agendados</div>
            <div class="stat-card-value" style="color:var(--c-ag);">{{ $agendados }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Concluídos</div>
            <div class="stat-card-value" style="color:var(--c-ok);">{{ $concluidos }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-label">Cancelados</div>
            <div class="stat-card-value" style="color:var(--c-no);">{{ $cancelados }}</div>
        </div>
        <div class="stat-card" style="border-color:rgba(201,168,76,0.2);">
            <div class="stat-card-label">Faturamento</div>
            <div class="stat-card-value" style="color:var(--gold); font-size:1.15rem;">
                R$ {{ number_format($faturamento, 2, ',', '.') }}
            </div>
        </div>
    </div>
    @endif

    {{-- LIST CARD --}}
    <div class="list-card">
        <div class="list-card-header">
            <h2 class="list-card-title">Agendamentos do dia</h2>
            <span class="count-pill">
                {{ $total }} agendamento{{ $total !== 1 ? 's' : '' }}
            </span>
        </div>

        @forelse($agendamentos as $ag)
        <div class="appointment-item status-{{ $ag->status }}">

            {{-- Hora --}}
            <div class="appt-time">
                <div class="appt-time-label">Hora</div>
                <div class="appt-time-val">
                    {{ \Carbon\Carbon::parse($ag->horario)->format('H:i') }}
                </div>
            </div>

            {{-- Info --}}
            <div>
                <div class="appt-client">{{ $ag->nome_cliente }}</div>
                <div class="appt-meta">
                    <span class="appt-meta-item">
                        <i class="fas fa-cut"></i>
                        {{ $ag->servico?->nome ?? '—' }}
                    </span>
                    @if($ag->servico?->preco)
                    <span class="appt-meta-item">
                        <i class="fas fa-tag"></i>
                        R$ {{ number_format($ag->servico->preco, 2, ',', '.') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Barbeiro --}}
            <div class="appt-barber">
                <i class="fas fa-user-tie"></i>
                {{ $ag->barbeiro?->nome ?? '—' }}
            </div>

            {{-- Status + Ações --}}
            <div class="appt-actions">
                <span class="status-badge status-{{ $ag->status }}">
                    {{ ucfirst($ag->status) }}
                </span>
                <a href="{{ route('agendamentos.edit', $ag) }}" class="btn-icon" title="Editar">
                    <i class="fas fa-pen"></i>
                </a>
                <button class="btn-icon danger"
                        onclick="openDeleteModal({{ $ag->id }}, '{{ addslashes($ag->nome_cliente) }}')"
                        title="Mover para lixeira">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-calendar-day"></i>
            <p>Nenhum agendamento para este dia.</p>
            <a href="{{ route('agendamentos.create') }}?data={{ $data->format('Y-m-d') }}" class="btn-gold">
                <i class="fas fa-plus"></i> Criar Agendamento
            </a>
        </div>
        @endforelse
    </div>

</div>

{{-- MODAL DE EXCLUSÃO --}}
<div id="deleteModal" class="modal-overlay" onclick="if(event.target===this) closeDeleteModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle" style="color:var(--c-no);"></i>
                Confirmar Exclusão
            </h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-icon-ring"><i class="fas fa-trash"></i></div>
            <h3>Mover para lixeira?</h3>
            <div class="modal-client-pill">
                <i class="fas fa-user"></i>
                <span id="deleteClientName">—</span>
            </div>
            <p class="modal-desc">
                O agendamento será movido para a lixeira e poderá ser restaurado depois.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-ghost" onclick="closeDeleteModal()">
                <i class="fas fa-arrow-left"></i> Cancelar
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" id="deleteSubmitBtn" class="btn-danger-solid">
                    <i class="fas fa-trash"></i> Mover para lixeira
                </button>
            </form>
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="toast" class="toast success">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    window.openDeleteModal = (id, nome) => {
        document.getElementById('deleteClientName').textContent = nome;
        document.getElementById('deleteForm').action = `/agendamentos/${id}`;
        document.getElementById('deleteSubmitBtn').disabled = false;
        document.getElementById('deleteSubmitBtn').innerHTML = '<i class="fas fa-trash"></i> Mover para lixeira';
        document.getElementById('deleteModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeDeleteModal = () => {
        document.getElementById('deleteModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    document.getElementById('deleteForm').addEventListener('submit', function () {
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removendo...';
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });

    @if(session('success'))
        const t = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = @json(session('success'));
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3800);
    @endif
});
</script>
@endpush