@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --gold: #C9A84C;
        --gold-light: #E8C96A;
        --gold-dim: #8B6914;
        --dark: #0D0D0D;
        --dark-card: #141414;
        --dark-elevated: #1C1C1C;
        --dark-border: #2A2A2A;
        --text-primary: #F0EDE8;
        --text-muted: #6B6560;
        --text-dim: #9C9690;
        --success: #3D8B68;
        --info: #2D6FA3;
        --danger: #8B3333;
        --status-agendado: #2D6FA3;
        --status-concluido: #3D8B68;
        --status-cancelado: #8B3333;
    }

    * { box-sizing: border-box; }

    body {
        background: var(--dark);
        color: var(--text-primary);
        font-family: 'DM Sans', sans-serif;
    }

    /* ── PAGE HEADER ── */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--dark-border);
        position: relative;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 80px;
        height: 2px;
        background: var(--gold);
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .page-title i {
        color: var(--gold);
        font-size: 1.4rem;
    }

    /* ── BUTTONS ── */
    .btn-gold {
        background: var(--gold);
        color: #0D0D0D;
        border: none;
        padding: 0.55rem 1.3rem;
        border-radius: 6px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.02em;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-gold:hover {
        background: var(--gold-light);
        color: #0D0D0D;
        transform: translateY(-1px);
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-dim);
        border: 1px solid var(--dark-border);
        padding: 0.5rem 1.1rem;
        border-radius: 6px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }

    .btn-ghost:hover {
        border-color: var(--gold-dim);
        color: var(--gold);
        background: rgba(201, 168, 76, 0.06);
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 0.8rem;
        text-decoration: none;
        border: 1px solid var(--dark-border);
        color: var(--text-dim);
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon:hover { color: var(--gold); border-color: var(--gold-dim); background: rgba(201, 168, 76, 0.08); }
    .btn-icon.danger:hover { color: #E05252; border-color: #8B3333; background: rgba(139, 51, 51, 0.12); }

    /* ── FILTER CARD ── */
    .filter-card {
        background: var(--dark-card);
        border: 1px solid var(--dark-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.75rem;
    }

    .filter-card label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.4rem;
    }

    .filter-card input,
    .filter-card select {
        background: var(--dark-elevated);
        border: 1px solid var(--dark-border);
        color: var(--text-primary);
        border-radius: 7px;
        padding: 0.5rem 0.85rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
        -webkit-appearance: none;
    }

    .filter-card input:focus,
    .filter-card select:focus {
        outline: none;
        border-color: var(--gold-dim);
        box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.08);
    }

    .filter-card select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B6560' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        padding-right: 2.2rem;
    }

    /* ── STATS ROW ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) 1.6fr;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .stat-card {
        background: var(--dark-card);
        border: 1px solid var(--dark-border);
        border-radius: 12px;
        padding: 1.2rem 1.4rem;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s;
    }

    .stat-card:hover { border-color: var(--gold-dim); }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
    }

    .stat-card.total::before    { background: var(--gold); }
    .stat-card.agendado::before { background: var(--status-agendado); }
    .stat-card.concluido::before{ background: var(--status-concluido); }
    .stat-card.cancelado::before{ background: var(--status-cancelado); }
    .stat-card.faturamento::before { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-family: 'Playfair Display', serif;
        font-size: 2.1rem;
        font-weight: 700;
        line-height: 1;
        color: var(--text-primary);
    }

    .stat-card.faturamento .stat-value {
        font-size: 1.6rem;
        color: var(--gold);
    }

    /* ── VIEW TOGGLE ── */
    .view-toggle-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 1.25rem;
        gap: 0.75rem;
    }

    /* ── MAIN CARD ── */
    .main-card {
        background: var(--dark-card);
        border: 1px solid var(--dark-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .main-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--dark-border);
        background: var(--dark-elevated);
    }

    .main-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    /* ── TABLE ── */
    .styled-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .styled-table thead th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--dark-border);
        background: var(--dark-elevated);
        white-space: nowrap;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: background 0.15s;
    }

    .styled-table tbody tr:last-child { border-bottom: none; }
    .styled-table tbody tr:hover { background: rgba(201, 168, 76, 0.03); }

    .styled-table td {
        padding: 0.85rem 1rem;
        color: var(--text-dim);
        vertical-align: middle;
    }

    .styled-table td:first-child { color: var(--text-primary); font-weight: 500; }

    .styled-table .client-name { color: var(--text-primary); font-weight: 500; }

    /* ── STATUS BADGES ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .status-badge::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }

    .status-badge.agendado  { background: rgba(45,111,163,0.15); color: #5BA3D9; }
    .status-badge.agendado::before  { background: #5BA3D9; }
    .status-badge.concluido { background: rgba(61,139,104,0.15); color: #5DBF95; }
    .status-badge.concluido::before { background: #5DBF95; }
    .status-badge.cancelado { background: rgba(139,51,51,0.15);  color: #D97070; }
    .status-badge.cancelado::before { background: #D97070; }

    /* ── EMPTY STATE ── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 2.5rem;
        color: var(--dark-border);
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state p { font-size: 0.9rem; margin: 0; }

    /* ── CALENDAR ── */
    .calendar-nav-btn {
        width: 32px; height: 32px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 6px;
        border: 1px solid var(--dark-border);
        color: var(--text-dim);
        text-decoration: none;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .calendar-nav-btn:hover { border-color: var(--gold-dim); color: var(--gold); }

    .calendar-table { width: 100%; border-collapse: collapse; }
    .calendar-table thead th {
        padding: 0.6rem;
        text-align: center;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--dark-border);
    }

    .calendar-cell {
        border: 1px solid var(--dark-border);
        height: 115px;
        width: 14.28%;
        padding: 0.5rem;
        vertical-align: top;
        position: relative;
        transition: background 0.15s;
    }

    .calendar-cell:hover { background: rgba(201,168,76,0.03); }
    .calendar-cell.today { background: rgba(201, 168, 76, 0.05); }
    .calendar-cell.weekend { background: rgba(0,0,0,0.12); }
    .calendar-cell.empty { background: rgba(0,0,0,0.15); }

    .day-number {
        font-size: 0.8rem;
        font-weight: 600;
        width: 24px; height: 24px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        margin-bottom: 0.35rem;
        color: var(--text-dim);
    }

    .day-number.today-num {
        background: var(--gold);
        color: #0D0D0D;
    }

    .day-count {
        position: absolute;
        top: 0.4rem; right: 0.4rem;
        font-size: 0.65rem;
        font-weight: 700;
        background: rgba(93, 191, 149, 0.2);
        color: #5DBF95;
        width: 18px; height: 18px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
    }

    .cal-event {
        font-size: 0.65rem;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0.15rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .cal-event-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .cal-more { font-size: 0.6rem; color: var(--gold-dim); margin-top: 0.1rem; }

    .cal-link {
        position: absolute;
        inset: 0;
        z-index: 1;
    }

    /* ── PAGINATION ── */
    .pagination-wrap { padding: 1rem 1.5rem; border-top: 1px solid var(--dark-border); }

    /* Override Laravel pagination */
    .pagination { gap: 0.25rem; }
    .page-link {
        background: var(--dark-elevated) !important;
        border-color: var(--dark-border) !important;
        color: var(--text-dim) !important;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8rem;
        border-radius: 5px !important;
    }
    .page-item.active .page-link {
        background: var(--gold) !important;
        border-color: var(--gold) !important;
        color: #0D0D0D !important;
        font-weight: 600;
    }
    .page-link:hover { border-color: var(--gold-dim) !important; color: var(--gold) !important; }
</style>

<div class="container-fluid" style="padding: 1.5rem; max-width: 1400px;">

    {{-- ── HEADER ── --}}
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-calendar-alt"></i>
            Agenda de Agendamentos
        </h1>
        <a href="{{ route('agendamentos.create') }}" class="btn-gold">
            <i class="fas fa-plus"></i> Novo Agendamento
        </a>
    </div>

    {{-- ── FILTERS ── --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('agendamentos.index') }}" id="filterForm">
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label>Mês / Ano</label>
                    <input type="month" name="selected_date" value="{{ $selectedDate->format('Y-m') }}">
                </div>
                <div class="col-md-3">
                    <label>Barbeiro</label>
                    <select name="barbeiro_id">
                        <option value="">Todos</option>
                        @foreach($barbeiros as $barbeiro)
                            <option value="{{ $barbeiro->id }}" {{ $barbeiroId == $barbeiro->id ? 'selected' : '' }}>
                                {{ $barbeiro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="agendado"  {{ $status == 'agendado'  ? 'selected' : '' }}>Agendado</option>
                        <option value="concluido" {{ $status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelado" {{ $status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div style="display:flex; gap:0.5rem;">
                        <button type="submit" class="btn-gold" style="flex:1; justify-content:center;">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('agendamentos.index') }}?view={{ $viewMode }}" class="btn-ghost">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── STATS ── --}}
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $estatisticas['total'] }}</div>
        </div>
        <div class="stat-card agendado">
            <div class="stat-label">Agendados</div>
            <div class="stat-value">{{ $estatisticas['agendados'] }}</div>
        </div>
        <div class="stat-card concluido">
            <div class="stat-label">Concluídos</div>
            <div class="stat-value">{{ $estatisticas['concluidos'] }}</div>
        </div>
        <div class="stat-card cancelado">
            <div class="stat-label">Cancelados</div>
            <div class="stat-value">{{ $estatisticas['cancelados'] }}</div>
        </div>
        <div class="stat-card faturamento">
            <div class="stat-label">Faturamento do Mês</div>
            <div class="stat-value">R$ {{ number_format($estatisticas['valor_total'], 2, ',', '.') }}</div>
        </div>
    </div>

    {{-- ── VIEW TOGGLE ── --}}
    <div class="view-toggle-bar">
        <a href="{{ route('agendamentos.index') }}?view={{ $viewMode === 'list' ? 'calendar' : 'list' }}&selected_date={{ $selectedDate->format('Y-m-d') }}" class="btn-ghost">
            <i class="fas fa-exchange-alt"></i>
            Ver como {{ $viewMode === 'list' ? 'Calendário' : 'Lista' }}
        </a>
    </div>

    {{-- ══════════════════════════════════════════════
         LIST VIEW
    ══════════════════════════════════════════════ --}}
    @if($viewMode === 'list')
    <div class="main-card">
        <div class="main-card-header">
            <h5 class="main-card-title">Lista de Agendamentos</h5>
        </div>
        <div style="overflow-x:auto;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Cliente</th>
                        <th>Barbeiro</th>
                        <th>Serviço</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendamentos as $agendamento)
                    <tr>
                        <td>{{ $agendamento->data->format('d/m/Y') }}</td>
                        <td>{{ $agendamento->horario }}</td>
                        <td class="client-name">{{ $agendamento->nome_cliente }}</td>
                        <td>{{ optional($agendamento->barbeiro)->nome ?? '—' }}</td>
                        <td>{{ optional($agendamento->servico)->nome ?? '—' }}</td>
                        <td style="color:var(--gold); font-weight:500;">R$ {{ number_format($agendamento->valor, 2, ',', '.') }}</td>
                        <td>
                            <span class="status-badge {{ $agendamento->status }}">
                                {{ ucfirst($agendamento->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap:0.35rem;">
                                <a href="{{ route('agendamentos.edit', $agendamento) }}" class="btn-icon" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('agendamentos.destroy', $agendamento) }}" method="POST"
                                      onsubmit="return confirm('Confirmar exclusão?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>Nenhum agendamento encontrado para os filtros selecionados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($agendamentos->hasPages())
        <div class="pagination-wrap">
            {{ $agendamentos->links() }}
        </div>
        @endif
    </div>
    @endif

    {{-- ══════════════════════════════════════════════
         CALENDAR VIEW
    ══════════════════════════════════════════════ --}}
    @if($viewMode === 'calendar')
    <div class="main-card" style="margin-bottom:1.5rem;">
        <div class="main-card-header">
            <h5 class="main-card-title">{{ $data->format('F Y') }}</h5>
            <div style="display:flex; gap:0.4rem; align-items:center;">
                <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $data->copy()->subMonth()->format('Y-m-d') }}" class="calendar-nav-btn">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="btn-ghost" style="padding:0.3rem 0.75rem; font-size:0.78rem;">
                    Hoje
                </a>
                <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $data->copy()->addMonth()->format('Y-m-d') }}" class="calendar-nav-btn">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <table class="calendar-table">
            <thead>
                <tr>
                    <th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th>
                    <th>Qui</th><th>Sex</th><th>Sáb</th>
                </tr>
            </thead>
            <tbody>
                @for($semana = 0; $semana < 6; $semana++)
                <tr>
                    @for($dia = 0; $dia < 7; $dia++)
                    @php
                        $index = ($semana * 7) + $dia;
                        $diaCalendario = $calendario[$index] ?? null;
                        $isWeekend = in_array($dia, [0, 6]);
                    @endphp
                    @if($diaCalendario)
                    <td class="calendar-cell {{ $diaCalendario['hoje'] ? 'today' : ($isWeekend ? 'weekend' : '') }}">
                        <div class="day-number {{ $diaCalendario['hoje'] ? 'today-num' : '' }}">
                            {{ $diaCalendario['dia'] }}
                        </div>
                        @if($diaCalendario['tem_agendamentos'])
                        <div class="day-count">{{ $diaCalendario['total_agendamentos'] }}</div>
                        @endif
                        <div>
                            @foreach($diaCalendario['agendamentos']->take(2) as $ag)
                            <div class="cal-event">
                                <div class="cal-event-dot" style="background:
                                    @if($ag->status === 'concluido') #5DBF95
                                    @elseif($ag->status === 'cancelado') #D97070
                                    @else #5BA3D9
                                    @endif;"></div>
                                {{ $ag->horario }} {{ $ag->nome_cliente }}
                            </div>
                            @endforeach
                            @if($diaCalendario['total_agendamentos'] > 2)
                            <div class="cal-more">+{{ $diaCalendario['total_agendamentos'] - 2 }} mais</div>
                            @endif
                        </div>
                        <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $diaCalendario['data']->format('Y-m-d') }}" class="cal-link"></a>
                    </td>
                    @else
                    <td class="calendar-cell empty"></td>
                    @endif
                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    {{-- Day appointments --}}
    <div class="main-card">
        <div class="main-card-header">
            <h5 class="main-card-title">
                Agendamentos · {{ $selectedDate->format('d/m/Y') }}
            </h5>
            <span style="font-size:0.72rem; font-weight:600; background:rgba(201,168,76,0.15); color:var(--gold); padding:0.2rem 0.6rem; border-radius:12px;">
                {{ $agendamentosDia->count() }} agendamento{{ $agendamentosDia->count() !== 1 ? 's' : '' }}
            </span>
        </div>
        @if($agendamentosDia->isNotEmpty())
        <div style="overflow-x:auto;">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Horário</th>
                        <th>Cliente</th>
                        <th>Barbeiro</th>
                        <th>Serviço</th>
                        <th>Status</th>
                        <th style="text-align:right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendamentosDia as $ag)
                    <tr>
                        <td>{{ $ag->horario }}</td>
                        <td class="client-name">{{ $ag->nome_cliente }}</td>
                        <td>{{ optional($ag->barbeiro)->nome ?? '—' }}</td>
                        <td>{{ optional($ag->servico)->nome ?? '—' }}</td>
                        <td>
                            <span class="status-badge {{ $ag->status }}">{{ ucfirst($ag->status) }}</span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap:0.35rem;">
                                <a href="{{ route('agendamentos.edit', $ag) }}" class="btn-icon" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('agendamentos.destroy', $ag) }}" method="POST"
                                      onsubmit="return confirm('Confirmar exclusão?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-day"></i>
            <p>Nenhum agendamento para este dia.</p>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection