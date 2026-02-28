@extends('layouts.app')

@section('title', 'Serviços')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --gold: #C9A84C;
        --gold-light: #E4C76B;
        --gold-dim: #8B6914;
        --gold-glow: rgba(201,168,76,0.1);
        --dark: #0D0D0D;
        --dark-card: #141414;
        --dark-elevated: #1C1C1C;
        --dark-border: #262626;
        --text-primary: #F0EDE8;
        --text-muted: #6B6560;
        --text-dim: #9C9690;
        --green: #4CAF7D;
        --green-bg: rgba(76,175,125,0.1);
        --green-border: rgba(76,175,125,0.25);
        --red: #D97070;
        --red-bg: rgba(139,51,51,0.12);
        --red-border: rgba(139,51,51,0.3);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: var(--dark); color: var(--text-primary); font-family: 'DM Sans', sans-serif; min-height: 100vh; }

    /* ─── PAGE WRAPPER ─── */
    .page-wrap { padding: 2rem; max-width: 1400px; }

    /* ─── HEADER ─── */
    .page-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 2.25rem; padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--dark-border); position: relative;
    }
    .page-header::after {
        content: ''; position: absolute; bottom: -1px; left: 0;
        width: 60px; height: 2px; background: var(--gold);
    }
    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem; font-weight: 700; letter-spacing: -0.03em;
        color: var(--text-primary); display: flex; align-items: center; gap: 0.65rem;
    }
    .page-title i { color: var(--gold); font-size: 1.5rem; }
    .page-meta {
        margin-top: 0.4rem; font-size: 0.75rem; color: var(--text-muted);
        display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
    }
    .page-meta-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--dark-border); }
    .meta-pill {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.68rem; font-weight: 600; padding: 0.15rem 0.55rem;
        border-radius: 20px; letter-spacing: 0.04em;
    }
    .meta-pill.active-pill  { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-border); }
    .meta-pill.inactive-pill{ background: var(--dark-elevated); color: var(--text-muted); border: 1px solid var(--dark-border); }

    /* ─── BUTTONS ─── */
    .btn-gold {
        background: var(--gold); color: #0D0D0D; border: none;
        padding: 0.65rem 1.35rem; border-radius: 8px;
        font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 0.85rem;
        cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;
        text-decoration: none; transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    }
    .btn-gold:hover { background: var(--gold-light); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(201,168,76,0.3); }
    .btn-ghost {
        background: transparent; color: var(--text-dim); border: 1px solid var(--dark-border);
        padding: 0.6rem 1.2rem; border-radius: 8px; font-family: 'DM Sans', sans-serif;
        font-weight: 500; font-size: 0.85rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s;
    }
    .btn-ghost:hover { border-color: var(--gold-dim); color: var(--gold); }
    .btn-danger-solid {
        background: var(--red-bg); color: var(--red); border: 1px solid var(--red-border);
        padding: 0.6rem 1.2rem; border-radius: 8px; font-family: 'DM Sans', sans-serif;
        font-weight: 600; font-size: 0.85rem; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s;
    }
    .btn-danger-solid:hover { background: rgba(139,51,51,0.25); }

    /* ─── GRID ─── */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: 1.25rem;
    }

    /* ─── SERVICE CARD ─── */
    .service-card {
        background: var(--dark-card); border: 1px solid var(--dark-border);
        border-radius: 16px; position: relative; overflow: hidden;
        display: flex; flex-direction: column;
        transition: border-color 0.25s, transform 0.22s, box-shadow 0.25s;
    }
    .service-card:hover {
        border-color: var(--gold-dim);
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.55);
    }
    .service-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .service-card:hover::before { opacity: 1; }

    .service-card-glow {
        position: absolute; top: -50px; right: -50px;
        width: 160px; height: 160px; border-radius: 50%;
        background: radial-gradient(circle, rgba(201,168,76,0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Inactive */
    .service-card.is-inactive { opacity: 0.55; }
    .service-card.is-inactive:hover { opacity: 1; }

    /* ─── ACTION BUTTONS ─── */
    .service-actions {
        position: absolute; top: 0.9rem; right: 0.9rem;
        display: flex; gap: 0.3rem; opacity: 0; transition: opacity 0.2s; z-index: 5;
    }
    .service-card:hover .service-actions { opacity: 1; }
    .btn-action {
        width: 30px; height: 30px; border-radius: 7px;
        border: 1px solid var(--dark-border); background: rgba(10,10,10,0.9);
        color: var(--text-muted); font-size: 0.68rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.18s; backdrop-filter: blur(6px);
    }
    .btn-action:hover        { border-color: var(--gold-dim); color: var(--gold); background: var(--gold-glow); }
    .btn-action.danger:hover { border-color: var(--red-border); color: var(--red); background: var(--red-bg); }

    /* ─── CARD INNER ─── */
    .service-card-inner {
        padding: 1.35rem 1.35rem 0;
        flex: 1; display: flex; flex-direction: column; gap: 1rem;
    }

    .service-header { display: flex; align-items: flex-start; gap: 0.9rem; padding-right: 3.5rem; }
    .service-icon {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        background: var(--gold-glow); border: 1px solid rgba(201,168,76,0.15);
        display: flex; align-items: center; justify-content: center;
        color: var(--gold); font-size: 1rem;
        transition: background 0.25s, border-color 0.25s;
    }
    .service-card:hover .service-icon { background: rgba(201,168,76,0.16); border-color: rgba(201,168,76,0.28); }

    .service-name-group { display: flex; flex-direction: column; gap: 0.25rem; }
    .service-name {
        font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700;
        color: var(--text-primary); line-height: 1.25;
    }
    .service-desc { font-size: 0.78rem; color: var(--text-muted); line-height: 1.55; }

    /* Divider */
    .card-divider { height: 1px; background: var(--dark-border); }

    /* Stats */
    .service-stats {
        display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0;
    }
    .stat-item { display: flex; align-items: center; gap: 0.5rem; }
    .stat-icon {
        width: 30px; height: 30px; border-radius: 8px;
        background: var(--dark-elevated); border: 1px solid var(--dark-border);
        display: flex; align-items: center; justify-content: center;
        color: var(--gold-dim); font-size: 0.65rem;
    }
    .stat-label { font-size: 0.62rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; line-height: 1; }
    .stat-value { font-size: 0.85rem; font-weight: 600; color: var(--text-dim); line-height: 1.4; }

    .service-price-wrap { text-align: right; }
    .service-price-label { font-size: 0.6rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }
    .service-price {
        font-family: 'Playfair Display', serif; font-size: 1.45rem; font-weight: 700;
        color: var(--gold); line-height: 1; letter-spacing: -0.02em;
    }

    /* ─── FOOTER ─── */
    .service-card-footer { padding: 0.75rem 1.35rem 1.35rem; }
    .status-toggle {
        width: 100%; padding: 0.48rem 1rem; border-radius: 8px;
        font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 0.72rem;
        cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 0.45rem;
        border: 1px solid transparent; letter-spacing: 0.05em; text-transform: uppercase;
    }
    .status-toggle.active  { background: var(--green-bg); border-color: var(--green-border); color: var(--green); }
    .status-toggle.active:hover { background: rgba(76,175,125,0.18); }
    .status-toggle.inactive{ background: var(--dark-elevated); color: var(--text-muted); border-color: var(--dark-border); }
    .status-toggle.inactive:hover{ border-color: var(--gold-dim); color: var(--gold); background: var(--gold-glow); }

    /* ─── EMPTY STATE ─── */
    .empty-state {
        grid-column: 1/-1; padding: 5rem 2rem; text-align: center;
        background: var(--dark-card); border: 1px dashed var(--dark-border); border-radius: 16px;
    }
    .empty-state i { font-size: 2.75rem; color: var(--dark-border); display: block; margin-bottom: 1rem; }
    .empty-state p { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem; }

    /* ════════ MODALS ════════ */
    .modal-overlay {
        position: fixed; inset: 0; z-index: 1000;
        background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center; padding: 1.5rem;
        opacity: 0; visibility: hidden; transition: opacity 0.25s, visibility 0.25s;
    }
    .modal-overlay.active { opacity: 1; visibility: visible; }
    .modal-box {
        background: var(--dark-card); border: 1px solid var(--dark-border);
        border-radius: 18px; width: 100%; max-width: 490px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.8);
        transform: scale(0.93) translateY(18px);
        transition: transform 0.28s cubic-bezier(.34,1.3,.64,1);
        overflow: hidden;
    }
    .modal-overlay.active .modal-box { transform: scale(1) translateY(0); }
    .modal-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1.3rem 1.5rem; border-bottom: 1px solid var(--dark-border);
        background: var(--dark-elevated); position: relative;
    }
    .modal-header::after {
        content: ''; position: absolute; bottom: 0; left: 1.5rem;
        width: 36px; height: 2px; background: var(--gold);
    }
    .modal-title {
        font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700;
        color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 0.5rem;
    }
    .modal-title i { color: var(--gold); font-size: 0.9rem; }
    .modal-close {
        width: 30px; height: 30px; border-radius: 7px;
        border: 1px solid var(--dark-border); background: transparent;
        color: var(--text-muted); cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 0.78rem;
        transition: all 0.18s;
    }
    .modal-close:hover { border-color: var(--gold-dim); color: var(--gold); background: var(--gold-glow); }
    .modal-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.1rem; }

    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group label {
        font-size: 0.62rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.09em; color: var(--text-muted);
    }
    .input-wrap { position: relative; }
    .input-wrap > i {
        position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
        font-size: 0.7rem; color: var(--text-muted); pointer-events: none;
    }
    .input-wrap > i.top-align { top: 0.95rem; transform: none; }
    .form-control {
        background: var(--dark-elevated); border: 1px solid var(--dark-border);
        color: var(--text-primary); border-radius: 9px; padding: 0.65rem 0.95rem;
        font-family: 'DM Sans', sans-serif; font-size: 0.875rem; width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control.has-icon { padding-left: 2.4rem; }
    .form-control:focus { outline: none; border-color: var(--gold-dim); box-shadow: 0 0 0 3px rgba(201,168,76,0.1); }
    .form-control::placeholder { color: var(--text-muted); opacity: 0.6; }
    textarea.form-control { resize: vertical; min-height: 80px; line-height: 1.55; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .modal-footer {
        display: flex; justify-content: flex-end; gap: 0.6rem;
        padding: 1rem 1.5rem; border-top: 1px solid var(--dark-border);
        background: var(--dark-elevated);
    }

    /* Delete modal */
    .delete-modal-box { max-width: 420px; }
    .delete-modal-body {
        padding: 2.25rem 1.5rem 1.5rem; text-align: center;
        display: flex; flex-direction: column; align-items: center; gap: 1rem;
    }
    .delete-icon-ring {
        width: 72px; height: 72px; border-radius: 50%;
        background: var(--red-bg); border: 2px solid var(--red-border);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.65rem; color: var(--red);
        animation: pulseRed 2.2s ease-in-out infinite;
    }
    @keyframes pulseRed {
        0%,100% { box-shadow: 0 0 0 0 rgba(217,112,112,0.2); }
        50%      { box-shadow: 0 0 0 10px rgba(217,112,112,0); }
    }
    .delete-modal-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--text-primary); }
    .delete-service-pill {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: var(--gold-glow); border: 1px solid rgba(201,168,76,0.2);
        color: var(--gold); padding: 0.3rem 0.9rem; border-radius: 20px;
        font-weight: 600; font-size: 0.85rem;
    }
    .delete-modal-desc { font-size: 0.82rem; color: var(--text-muted); line-height: 1.65; }

    /* Toast */
    .toast {
        position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 3000;
        background: var(--dark-elevated); border: 1px solid var(--dark-border);
        border-radius: 10px; padding: 0.9rem 1.25rem;
        display: flex; align-items: center; gap: 0.65rem;
        font-size: 0.83rem; color: var(--text-primary);
        box-shadow: 0 12px 40px rgba(0,0,0,0.6);
        transform: translateY(120%); transition: transform 0.32s cubic-bezier(.34,1.3,.64,1);
        min-width: 260px; max-width: 360px;
    }
    .toast.show   { transform: translateY(0); }
    .toast.success{ border-left: 3px solid var(--green); }
    .toast.error  { border-left: 3px solid var(--red); }
    .toast.success i { color: var(--green); }
    .toast.error i   { color: var(--red); }

    @media (max-width: 640px) {
        .form-row { grid-template-columns: 1fr; }
        .services-grid { grid-template-columns: 1fr; }
        .page-title { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="page-wrap">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-cut"></i>
                Serviços
            </h1>
            <div class="page-meta">
                <span>{{ $servicos->count() }} cadastrado{{ $servicos->count() !== 1 ? 's' : '' }}</span>
                @if($servicos->count())
                    <span class="page-meta-dot"></span>
                    <span class="meta-pill active-pill">
                        <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                        {{ $servicos->where('ativo', true)->count() }} ativo{{ $servicos->where('ativo', true)->count() !== 1 ? 's' : '' }}
                    </span>
                    @if($servicos->where('ativo', false)->count())
                        <span class="meta-pill inactive-pill">
                            {{ $servicos->where('ativo', false)->count() }} inativo{{ $servicos->where('ativo', false)->count() !== 1 ? 's' : '' }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
        <button class="btn-gold" onclick="openServiceModal()">
            <i class="fas fa-plus"></i> Novo Serviço
        </button>
    </div>

    {{-- GRID --}}
    <div class="services-grid">
        @forelse($servicos as $servico)
        @php
            $iconMap = [
                'corte'      => 'fa-scissors',
                'barba'      => 'fa-user-alt',
                'platin'     => 'fa-star',
                'pigment'    => 'fa-paint-brush',
                'sobrancelh' => 'fa-eye',
                'hidrat'     => 'fa-tint',
            ];
            $icon = 'fa-cut';
            foreach ($iconMap as $key => $ico) {
                if (stripos($servico->nome, $key) !== false) { $icon = $ico; break; }
            }
        @endphp

        <div class="service-card {{ $servico->ativo ? '' : 'is-inactive' }}">
            <div class="service-card-glow"></div>

            <div class="service-actions">
                <button class="btn-action js-edit-service"
                        data-id="{{ $servico->id }}" title="Editar">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="btn-action danger js-delete-service"
                        data-id="{{ $servico->id }}" data-name="{{ $servico->nome }}" title="Excluir">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="service-card-inner">
                <div class="service-header">
                    <div class="service-icon"><i class="fas {{ $icon }}"></i></div>
                    <div class="service-name-group">
                        <div class="service-name">{{ $servico->nome }}</div>
                        @if($servico->descricao)
                            <div class="service-desc">{{ $servico->descricao }}</div>
                        @endif
                    </div>
                </div>

                <div class="card-divider"></div>

                <div class="service-stats">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-label">Duração</div>
                            <div class="stat-value">{{ $servico->duracao_minutos }} min</div>
                        </div>
                    </div>
                    <div class="service-price-wrap">
                        <div class="service-price-label">Preço</div>
                        <div class="service-price">R$ {{ number_format($servico->preco, 2, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="service-card-footer">
                <form action="{{ route('servicos.toggle-status', $servico) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="status-toggle {{ $servico->ativo ? 'active' : 'inactive' }}">
                        <i class="fas fa-{{ $servico->ativo ? 'check-circle' : 'times-circle' }}"></i>
                        {{ $servico->ativo ? 'Ativo' : 'Inativo — Ativar' }}
                    </button>
                </form>
            </div>
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
</div>

{{-- MODAL: CRIAR / EDITAR --}}
<div id="serviceModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i id="modalIcon" class="fas fa-cut"></i>
                <span id="serviceModalTitle">Novo Serviço</span>
            </h2>
            <button class="modal-close" onclick="closeServiceModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="serviceForm" action="{{ route('servicos.store') }}" method="POST">
            @csrf
            <input type="hidden" id="serviceId" name="id">
            <input type="hidden" id="serviceMethod" name="_method" value="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nome do Serviço</label>
                    <div class="input-wrap">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="nome" name="nome"
                               class="form-control has-icon"
                               placeholder="Ex: Corte Masculino" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label>Descrição <span style="font-weight:400;text-transform:none;letter-spacing:0;opacity:.6;">(opcional)</span></label>
                    <div class="input-wrap">
                        <i class="fas fa-align-left top-align"></i>
                        <textarea id="descricao" name="descricao" rows="3"
                                  class="form-control has-icon"
                                  placeholder="Descreva brevemente o serviço..."></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Duração (min)</label>
                        <div class="input-wrap">
                            <i class="fas fa-clock"></i>
                            <input type="number" id="duracao_minutos" name="duracao_minutos"
                                   class="form-control has-icon"
                                   placeholder="30" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Preço (R$)</label>
                        <div class="input-wrap">
                            <i class="fas fa-tag"></i>
                            <input type="text" id="preco" name="preco"
                                   class="form-control has-icon"
                                   placeholder="45,00" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeServiceModal()">Cancelar</button>
                <button type="submit" class="btn-gold" id="serviceSubmitBtn">
                    <i class="fas fa-save"></i>
                    <span id="serviceSubmitText">Criar Serviço</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL: EXCLUIR --}}
<div id="deleteModal" class="modal-overlay">
    <div class="modal-box delete-modal-box">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-exclamation-triangle" style="color:var(--red);"></i>
                Confirmar Exclusão
            </h2>
            <button class="modal-close" onclick="closeDeleteModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="delete-modal-body">
            <div class="delete-icon-ring"><i class="fas fa-trash"></i></div>
            <p class="delete-modal-title">Excluir serviço?</p>
            <div class="delete-service-pill">
                <i class="fas fa-cut"></i>
                <span id="deleteServiceName">—</span>
            </div>
            <p class="delete-modal-desc">
                Esta ação moverá o serviço para a <strong style="color:var(--text-primary);">lixeira</strong>.
                Agendamentos futuros vinculados a este serviço serão afetados.
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

    const openModal  = id => { document.getElementById(id).classList.add('active');    document.body.style.overflow = 'hidden'; };
    const closeModal = id => { document.getElementById(id).classList.remove('active'); document.body.style.overflow = ''; };

    document.querySelectorAll('.modal-overlay').forEach(o =>
        o.addEventListener('click', e => { if (e.target === o) closeModal(o.id); })
    );

    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        document.getElementById('toastIcon').className = type === 'success'
            ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        t.className = `toast ${type} show`;
        setTimeout(() => t.classList.remove('show'), 3800);
    }

    window.openServiceModal = () => {
        document.getElementById('serviceModalTitle').textContent = 'Novo Serviço';
        document.getElementById('modalIcon').className = 'fas fa-cut';
        document.getElementById('serviceForm').reset();
        document.getElementById('serviceForm').action = '{{ route('servicos.store') }}';
        document.getElementById('serviceMethod').value = 'POST';
        const btn = document.getElementById('serviceSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> <span id="serviceSubmitText">Criar Serviço</span>';
        openModal('serviceModal');
        setTimeout(() => document.getElementById('nome').focus(), 260);
    };

    window.closeServiceModal = () => closeModal('serviceModal');
    window.closeDeleteModal  = () => closeModal('deleteModal');

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
            document.getElementById('modalIcon').className           = 'fas fa-pen';
            document.getElementById('serviceId').value       = data.id;
            document.getElementById('nome').value            = data.nome ?? '';
            document.getElementById('descricao').value       = data.descricao ?? '';
            document.getElementById('duracao_minutos').value = data.duracao_minutos ?? '';
            document.getElementById('preco').value           = String(data.preco ?? '').replace('.', ',');
            document.getElementById('serviceForm').action    = `/servicos/${id}`;
            document.getElementById('serviceMethod').value   = 'PUT';
            const btn = document.getElementById('serviceSubmitBtn');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> <span>Salvar Alterações</span>';
        })
        .catch(err => { closeServiceModal(); showToast('Erro ao carregar serviço.', 'error'); console.error(err); });
    };

    window.openDeleteModal = (id, name) => {
        document.getElementById('deleteServiceName').textContent = name;
        document.getElementById('deleteForm').action = `/servicos/${id}`;
        const btn = document.getElementById('deleteSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-trash"></i> Sim, excluir';
        openModal('deleteModal');
    };

    const grid = document.querySelector('.services-grid');
    if (grid) {
        grid.addEventListener('click', e => {
            const editBtn   = e.target.closest('.js-edit-service');
            const deleteBtn = e.target.closest('.js-delete-service');
            if (editBtn)   editService(editBtn.dataset.id);
            if (deleteBtn) openDeleteModal(deleteBtn.dataset.id, deleteBtn.dataset.name);
        });
    }

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

    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        if (document.getElementById('serviceModal').classList.contains('active')) closeServiceModal();
        if (document.getElementById('deleteModal').classList.contains('active'))  closeDeleteModal();
    });

    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif
    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif
});
</script>
@endpush