@extends('layouts.app')

@section('title', 'Lixeira')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --gold: #C9A84C; --gold-light: #E4C76B; --gold-dim: #8B6914;
        --gold-glow: rgba(201,168,76,0.10);
        --dark: #0D0D0D; --dark-card: #141414; --dark-elevated: #1C1C1C;
        --dark-border: #262626; --text-primary: #F0EDE8;
        --text-muted: #6B6560; --text-dim: #9C9690;
        --red: #D97070; --red-bg: rgba(139,51,51,0.12); --red-border: rgba(139,51,51,0.28);
        --green: #4CAF7D; --green-bg: rgba(76,175,125,0.10); --green-border: rgba(76,175,125,0.25);
    }

    body { background:var(--dark); color:var(--text-primary); font-family:'DM Sans',sans-serif; }

    .page-header {
        display:flex; justify-content:space-between; align-items:flex-end;
        margin-bottom:2rem; padding-bottom:1.25rem;
        border-bottom:1px solid var(--dark-border); position:relative;
    }
    .page-header::after {
        content:''; position:absolute; bottom:-1px; left:0;
        width:60px; height:2px; background:var(--red);
    }
    .page-title {
        font-family:'Playfair Display',serif; font-size:1.85rem; font-weight:700;
        color:var(--text-primary); letter-spacing:-0.02em; margin:0;
        display:flex; align-items:center; gap:0.6rem;
    }
    .page-title i { color:var(--red); }
    .page-subtitle { font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem; }

    .warning-banner {
        display:flex; align-items:center; gap:0.85rem;
        background:var(--red-bg); border:1px solid var(--red-border);
        border-radius:10px; padding:0.85rem 1.25rem;
        font-size:0.82rem; color:var(--red); margin-bottom:1.5rem;
    }
    .warning-banner i { font-size:1rem; flex-shrink:0; }
    .warning-banner strong { color:var(--text-primary); }

    .btn-ghost {
        background:transparent; color:var(--text-dim); border:1px solid var(--dark-border);
        padding:0.55rem 1.1rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:500; font-size:0.85rem; cursor:pointer;
        display:inline-flex; align-items:center; gap:0.4rem;
        text-decoration:none; transition:all 0.2s;
    }
    .btn-ghost:hover { border-color:var(--gold-dim); color:var(--gold); }

    .table-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:14px; overflow:hidden;
    }
    .table-card-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .table-card-title {
        font-family:'Playfair Display',serif; font-size:0.95rem; font-weight:700;
        color:var(--text-primary); margin:0;
    }
    .count-pill {
        font-size:0.68rem; font-weight:700;
        background:var(--red-bg); color:var(--red);
        border:1px solid var(--red-border);
        padding:0.18rem 0.6rem; border-radius:20px;
    }

    .lixeira-table { width:100%; border-collapse:collapse; }
    .lixeira-table thead th {
        padding:0.75rem 1.25rem;
        font-size:0.62rem; font-weight:700; text-transform:uppercase;
        letter-spacing:0.09em; color:var(--text-muted);
        border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated); text-align:left; white-space:nowrap;
    }
    .lixeira-table tbody td {
        padding:0.9rem 1.25rem;
        border-bottom:1px solid rgba(255,255,255,0.04);
        font-size:0.855rem; vertical-align:middle;
    }
    .lixeira-table tbody tr:last-child td { border-bottom:none; }
    .lixeira-table tbody tr { transition:background 0.15s; }
    .lixeira-table tbody tr:hover td { background:rgba(255,255,255,0.02); }

    .cell-client-name { font-weight:600; color:var(--text-primary); }
    .cell-date {
        font-family:'Playfair Display',serif; font-size:0.9rem;
        font-weight:600; color:var(--text-dim); white-space:nowrap;
    }
    .cell-time {
        font-family:'Playfair Display',serif; font-size:0.95rem;
        font-weight:700; color:var(--gold);
    }
    .cell-price {
        font-family:'Playfair Display',serif; font-size:0.95rem;
        font-weight:700; color:var(--gold); white-space:nowrap;
    }
    .deleted-badge {
        display:inline-flex; align-items:center; gap:0.3rem;
        background:var(--red-bg); border:1px solid var(--red-border);
        color:var(--red); padding:0.18rem 0.55rem; border-radius:6px;
        font-size:0.65rem; font-weight:700; white-space:nowrap;
    }

    .action-wrap { display:flex; gap:0.35rem; align-items:center; }
    .btn-restore {
        display:inline-flex; align-items:center; gap:0.35rem;
        padding:0.35rem 0.8rem; border-radius:7px;
        background:var(--green-bg); border:1px solid var(--green-border);
        color:var(--green); font-size:0.72rem; font-weight:600;
        cursor:pointer; transition:all 0.18s; white-space:nowrap;
        font-family:'DM Sans',sans-serif;
    }
    .btn-restore:hover { background:rgba(76,175,125,0.2); }
    .btn-delete-perm {
        width:30px; height:30px; border-radius:7px;
        border:1px solid var(--dark-border); background:transparent;
        color:var(--text-muted); font-size:0.72rem; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        transition:all 0.18s;
    }
    .btn-delete-perm:hover {
        border-color:var(--red-border); color:var(--red); background:var(--red-bg);
    }

    .empty-state { text-align:center; padding:5rem 2rem; }
    .empty-icon-ring {
        width:80px; height:80px; border-radius:50%; margin:0 auto 1.25rem;
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        display:flex; align-items:center; justify-content:center;
        font-size:1.75rem; color:var(--dark-border);
    }
    .empty-state h3 {
        font-family:'Playfair Display',serif; font-size:1.1rem; font-weight:700;
        color:var(--text-primary); margin-bottom:0.5rem;
    }
    .empty-state p { font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem; }

    .pagination-wrap {
        padding:1rem 1.5rem; border-top:1px solid var(--dark-border);
        display:flex; justify-content:center;
    }
    .pagination-wrap .pagination { display:flex; gap:0.3rem; list-style:none; margin:0; }
    .pagination-wrap .page-link {
        display:flex; align-items:center; justify-content:center;
        min-width:32px; height:32px; padding:0 0.6rem;
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        border-radius:7px; color:var(--text-dim); text-decoration:none;
        font-size:0.8rem; font-weight:500; transition:all 0.2s;
    }
    .pagination-wrap .page-link:hover { border-color:var(--gold-dim); color:var(--gold); background:var(--gold-glow); }
    .pagination-wrap .page-item.active .page-link { background:var(--gold); border-color:var(--gold); color:#0D0D0D; }
    .pagination-wrap .page-item.disabled .page-link { opacity:0.4; pointer-events:none; }

    .modal-overlay {
        position:fixed; inset:0; z-index:1000;
        background:rgba(0,0,0,0.85); backdrop-filter:blur(8px);
        display:none; align-items:center; justify-content:center; padding:1.5rem;
    }
    .modal-overlay.active { display:flex; }
    .modal-box {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:18px; width:100%; max-width:400px; overflow:hidden;
        box-shadow:0 40px 100px rgba(0,0,0,0.8);
        animation:modalIn 0.22s cubic-bezier(.34,1.3,.64,1);
    }
    @keyframes modalIn {
        from { opacity:0; transform:scale(0.93) translateY(18px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    .modal-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1.1rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated); position:relative;
    }
    .modal-header::after {
        content:''; position:absolute; bottom:0; left:1.5rem;
        width:36px; height:2px; background:var(--red);
    }
    .modal-title {
        font-family:'Playfair Display',serif; font-size:1rem; font-weight:700;
        color:var(--text-primary); margin:0; display:flex; align-items:center; gap:0.5rem;
    }
    .modal-close {
        width:28px; height:28px; border-radius:6px;
        border:1px solid var(--dark-border); background:transparent;
        color:var(--text-muted); cursor:pointer;
        display:flex; align-items:center; justify-content:center; font-size:0.75rem;
        transition:all 0.18s;
    }
    .modal-close:hover { border-color:var(--gold-dim); color:var(--gold); }
    .modal-body {
        padding:2rem 1.5rem 1.5rem; text-align:center;
        display:flex; flex-direction:column; align-items:center; gap:1rem;
    }
    .modal-icon-ring {
        width:68px; height:68px; border-radius:50%;
        background:var(--red-bg); border:2px solid var(--red-border);
        display:flex; align-items:center; justify-content:center;
        font-size:1.6rem; color:var(--red);
        animation:pulseRed 2s ease-in-out infinite;
    }
    @keyframes pulseRed {
        0%,100% { box-shadow:0 0 0 0 rgba(217,112,112,0.2); }
        50%      { box-shadow:0 0 0 8px rgba(217,112,112,0); }
    }
    .modal-h3 {
        font-family:'Playfair Display',serif; font-size:1.15rem; font-weight:700;
        color:var(--text-primary); margin:0;
    }
    .modal-pill {
        display:inline-flex; align-items:center; gap:0.4rem;
        background:var(--gold-glow); border:1px solid rgba(201,168,76,0.2);
        color:var(--gold); padding:0.3rem 0.85rem; border-radius:20px;
        font-weight:600; font-size:0.85rem;
    }
    .modal-desc { font-size:0.82rem; color:var(--text-muted); line-height:1.65; margin:0; }
    .modal-footer {
        display:flex; justify-content:space-between; gap:0.6rem;
        padding:1rem 1.5rem; border-top:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .btn-danger-solid {
        background:rgba(139,51,51,0.85); color:#F0EDE8; border:1px solid var(--red-border);
        padding:0.5rem 1.2rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:600; font-size:0.85rem; cursor:pointer;
        display:inline-flex; align-items:center; gap:0.4rem; transition:background 0.2s;
    }
    .btn-danger-solid:hover { background:#A04040; }
</style>
@endsection

@section('content')
<div class="container-fluid" style="padding:1.5rem; max-width:1300px;">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-trash-alt"></i>
                Lixeira
            </h1>
            <div class="page-subtitle">
                Agendamentos removidos — podem ser restaurados a qualquer momento
            </div>
        </div>
        <a href="{{ route('agendamentos.index') }}" class="btn-ghost">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    {{-- WARNING BANNER --}}
    @if($agendamentos->count() > 0)
    <div class="warning-banner">
        <i class="fas fa-exclamation-triangle"></i>
        <span>
            Ao <strong>excluir permanentemente</strong>, o agendamento é removido para sempre e não poderá ser recuperado.
        </span>
    </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2 class="table-card-title">Itens na lixeira</h2>
            @if($agendamentos->count() > 0)
                <span class="count-pill">
                    {{ $agendamentos->total() }} item{{ $agendamentos->total() !== 1 ? 's' : '' }}
                </span>
            @endif
        </div>

        @if($agendamentos->count() > 0)
        <div style="overflow-x:auto;">
            <table class="lixeira-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Barbeiro</th>
                        <th>Serviço</th>
                        <th>Valor</th>
                        <th>Removido em</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendamentos as $agendamento)
                    <tr>
                        <td>
                            <div class="cell-client-name">
                                {{ $agendamento->nome_cliente ?? $agendamento->cliente }}
                            </div>
                        </td>
                        <td>
                            <span class="cell-date">
                                {{ \Carbon\Carbon::parse($agendamento->data)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <span class="cell-time">
                                {{ \Carbon\Carbon::parse($agendamento->horario ?? $agendamento->hora)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.83rem;color:var(--text-dim);">
                                <i class="fas fa-user-tie" style="font-size:0.65rem;color:var(--gold-dim);"></i>
                                {{ $agendamento->barbeiro?->nome ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.83rem;color:var(--text-dim);">
                                {{ $agendamento->servico?->nome ?? '—' }}
                            </div>
                        </td>
                        <td>
                            <span class="cell-price">
                                R$ {{ number_format($agendamento->valor ?? 0, 2, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="deleted-badge">
                                <i class="fas fa-clock" style="font-size:0.6rem;"></i>
                                {{ $agendamento->deleted_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-wrap" style="justify-content:flex-end;">
                                <form action="{{ route('agendamentos.restore', $agendamento->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-restore">
                                        <i class="fas fa-undo"></i> Restaurar
                                    </button>
                                </form>
                                <button class="btn-delete-perm"
                                        onclick="openDeleteModal({{ $agendamento->id }}, '{{ addslashes($agendamento->nome_cliente ?? $agendamento->cliente) }}')"
                                        title="Excluir permanentemente">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($agendamentos->hasPages())
        <div class="pagination-wrap">
            {{ $agendamentos->links() }}
        </div>
        @endif

        @else
        <div class="empty-state">
            <div class="empty-icon-ring">
                <i class="fas fa-check"></i>
            </div>
            <h3>Lixeira vazia</h3>
            <p>Nenhum agendamento foi removido ainda.</p>
            <a href="{{ route('agendamentos.index') }}" class="btn-ghost">
                <i class="fas fa-calendar-check"></i> Ver Agendamentos
            </a>
        </div>
        @endif
    </div>

</div>

{{-- MODAL EXCLUIR PERMANENTE --}}
<div id="deletePermModal" class="modal-overlay" onclick="if(event.target===this)closeDeleteModal()">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle" style="color:var(--red);"></i>
                Exclusão Permanente
            </h2>
            <button class="modal-close" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-icon-ring"><i class="fas fa-skull"></i></div>
            <p class="modal-h3">Excluir para sempre?</p>
            <div class="modal-pill">
                <i class="fas fa-user"></i>
                <span id="deletePermName">—</span>
            </div>
            <p class="modal-desc">
                Esta ação é <strong style="color:var(--text-primary);">permanente e irreversível</strong>.
                O agendamento será completamente removido do sistema.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-ghost" onclick="closeDeleteModal()">
                <i class="fas fa-arrow-left"></i> Cancelar
            </button>
            <form id="deletePermForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" id="deletePermBtn" class="btn-danger-solid">
                    <i class="fas fa-times"></i> Excluir permanentemente
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    window.openDeleteModal = (id, nome) => {
        document.getElementById('deletePermName').textContent = nome;
        document.getElementById('deletePermForm').action = `/agendamentos/${id}/deletar`;
        const btn = document.getElementById('deletePermBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-times"></i> Excluir permanentemente';
        document.getElementById('deletePermModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeDeleteModal = () => {
        document.getElementById('deletePermModal').classList.remove('active');
        document.body.style.overflow = '';
    };

    document.getElementById('deletePermForm').addEventListener('submit', function () {
        const btn = document.getElementById('deletePermBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Removendo...';
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });
});
</script>
@endpush