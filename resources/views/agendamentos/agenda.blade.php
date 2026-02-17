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
    }

    body { background: var(--dark); color: var(--text-primary); font-family: 'DM Sans', sans-serif; }

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

    .btn-gold {
        background:var(--gold); color:#0D0D0D; border:none; padding:0.55rem 1.3rem;
        border-radius:6px; font-family:'DM Sans',sans-serif; font-weight:600; font-size:0.875rem;
        cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;
        text-decoration:none; transition:background 0.2s, transform 0.15s;
    }
    .btn-gold:hover { background:var(--gold-light); color:#0D0D0D; transform:translateY(-1px); }

    .btn-ghost {
        background:transparent; color:var(--text-dim); border:1px solid var(--dark-border);
        padding:0.5rem 1.1rem; border-radius:6px; font-family:'DM Sans',sans-serif;
        font-weight:500; font-size:0.875rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; text-decoration:none;
        transition:border-color 0.2s, color 0.2s, background 0.2s;
    }
    .btn-ghost:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.06); }

    .btn-nav {
        width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center;
        border-radius:7px; border:1px solid var(--dark-border); color:var(--text-dim);
        text-decoration:none; font-size:0.82rem; transition:all 0.2s; background:transparent;
    }
    .btn-nav:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.06); }

    /* FILTER */
    .filter-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:12px; padding:1.4rem; margin-bottom:1.5rem;
    }
    .filter-card label {
        font-size:0.68rem; font-weight:700; letter-spacing:0.08em;
        text-transform:uppercase; color:var(--text-muted); display:block; margin-bottom:0.4rem;
    }
    .filter-card input,
    .filter-card select {
        background:var(--dark-elevated); border:1px solid var(--dark-border);
        color:var(--text-primary); border-radius:7px; padding:0.5rem 0.85rem;
        font-family:'DM Sans',sans-serif; font-size:0.875rem; width:100%;
        transition:border-color 0.2s; appearance:none; -webkit-appearance:none;
    }
    .filter-card input:focus,
    .filter-card select:focus { outline:none; border-color:var(--gold-dim); box-shadow:0 0 0 3px rgba(201,168,76,0.08); }
    .filter-card select {
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236B6560' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 0.85rem center; padding-right:2.2rem;
    }

    /* CALENDAR */
    .cal-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:12px; overflow:hidden;
    }
    .cal-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1rem 1.5rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .cal-month-title {
        font-family:'Playfair Display',serif; font-size:1.05rem; font-weight:600;
        color:var(--text-primary); margin:0; text-transform:capitalize;
    }
    .cal-nav { display:flex; align-items:center; gap:0.4rem; }

    .cal-table { width:100%; border-collapse:collapse; table-layout:fixed; }
    .cal-table thead th {
        padding:0.65rem 0.5rem; text-align:center;
        font-size:0.65rem; font-weight:700; letter-spacing:0.1em;
        text-transform:uppercase; color:var(--text-muted);
        border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .cal-cell {
        height:130px; vertical-align:top; padding:0.6rem;
        border:1px solid var(--dark-border); position:relative; transition:background 0.15s;
    }
    .cal-cell.has-events { cursor:pointer; }
    .cal-cell.has-events:hover { background:rgba(201,168,76,0.04); }
    .cal-cell.is-today { background:rgba(201,168,76,0.05); }
    .cal-cell.is-today .day-num { background:var(--gold); color:#0D0D0D; }
    .cal-cell.is-weekend { background:rgba(0,0,0,0.15); }
    .cal-cell.out-of-month { background:rgba(0,0,0,0.25); opacity:0.4; }
    .cal-cell.empty-cell { background:rgba(0,0,0,0.2); }

    .day-num {
        width:26px; height:26px; border-radius:50%;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:0.8rem; font-weight:600; color:var(--text-dim); margin-bottom:0.4rem;
    }
    .day-count {
        position:absolute; top:0.5rem; right:0.5rem;
        font-size:0.62rem; font-weight:700;
        background:rgba(93,191,149,0.18); color:#5DBF95;
        width:20px; height:20px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
    }
    .day-events { display:flex; flex-direction:column; gap:0.2rem; }
    .day-event {
        display:flex; align-items:center; gap:0.3rem;
        font-size:0.67rem; color:var(--text-muted); line-height:1.3;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .day-event-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
    .day-event-time { color:var(--gold-dim); font-weight:600; flex-shrink:0; }
    .day-event-name { overflow:hidden; text-overflow:ellipsis; }
    .day-more { font-size:0.62rem; color:var(--gold-dim); margin-top:0.15rem; font-weight:600; }
    .cal-stretched-link { position:absolute; inset:0; z-index:1; }

    /* STATS BAR */
    .cal-stats {
        display:grid; grid-template-columns:repeat(4,1fr);
        border-top:1px solid var(--dark-border);
    }
    .cal-stat { padding:1rem 1.25rem; border-right:1px solid var(--dark-border); text-align:center; }
    .cal-stat:last-child { border-right:none; }
    .cal-stat-label { font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:0.25rem; }
    .cal-stat-value { font-family:'Playfair Display',serif; font-size:1.5rem; font-weight:700; color:var(--text-primary); }

    /* DAY ROW LIST */
    .day-row {
        display:grid; grid-template-columns:64px 1fr auto auto;
        align-items:center; gap:1rem; padding:0.85rem 1.5rem;
        border-bottom:1px solid rgba(255,255,255,0.04); transition:background 0.15s;
    }
    .day-row:last-child { border-bottom:none; }
    .day-row:hover { background:rgba(201,168,76,0.03); }
    .day-row-time { border-right:1px solid var(--dark-border); padding-right:1rem; text-align:center; }
    .day-row-time-label { font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); font-weight:600; margin-bottom:0.05rem; }
    .day-row-time-val { font-family:'Playfair Display',serif; font-size:1rem; font-weight:600; color:var(--gold); }
    .day-row-client { font-weight:600; font-size:0.9rem; color:var(--text-primary); margin-bottom:0.1rem; }
    .day-row-service { font-size:0.75rem; color:var(--text-muted); }
    .day-row-barber { font-size:0.78rem; color:var(--text-dim); font-weight:500; white-space:nowrap; display:flex; align-items:center; gap:0.3rem; }
    .day-row-right { display:flex; align-items:center; gap:0.5rem; }

    .status-badge {
        display:inline-flex; align-items:center; gap:0.3rem; padding:0.2rem 0.55rem;
        border-radius:20px; font-size:0.67rem; font-weight:700; letter-spacing:0.04em; white-space:nowrap;
    }
    .status-badge::before { content:''; width:5px; height:5px; border-radius:50%; }
    .status-badge.agendado  { background:rgba(45,111,163,0.15);  color:#5BA3D9; } .status-badge.agendado::before  { background:#5BA3D9; }
    .status-badge.concluido { background:rgba(61,139,104,0.15);  color:#5DBF95; } .status-badge.concluido::before { background:#5DBF95; }
    .status-badge.cancelado { background:rgba(139,51,51,0.15);   color:#D97070; } .status-badge.cancelado::before { background:#D97070; }

    .btn-icon-sm {
        width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center;
        border-radius:6px; font-size:0.75rem; text-decoration:none;
        border:1px solid var(--dark-border); color:var(--text-muted); background:transparent; transition:all 0.2s;
    }
    .btn-icon-sm:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.08); }

    @media(max-width:768px) {
        .cal-cell { height:80px; }
        .cal-stats { grid-template-columns:repeat(2,1fr); }
        .day-row { grid-template-columns:56px 1fr auto; }
        .day-row-barber { display:none; }
    }
</style>

<div class="container-fluid" style="padding:1.5rem; max-width:1400px;">

    {{-- HEADER --}}
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-calendar-alt"></i>
            Agenda do Mês
        </h1>
        <a href="{{ route('agendamentos.create') }}" class="btn-gold">
            <i class="fas fa-plus"></i> Novo Agendamento
        </a>
    </div>

    {{-- FILTROS --}}
    {{-- O controller usa ?data=YYYY-MM e barbeiro_id e status --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('agenda') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label>Mês / Ano</label>
                    <input type="month" name="data" value="{{ $data->format('Y-m') }}">
                </div>
                <div class="col-md-3">
                    <label>Barbeiro</label>
                    <select name="barbeiro_id">
                        <option value="">Todos</option>
                        @foreach($barbeiros as $barbeiro)
                            <option value="{{ $barbeiro->id }}" {{ request('barbeiro_id') == $barbeiro->id ? 'selected' : '' }}>
                                {{ $barbeiro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="agendado"  {{ request('status') == 'agendado'  ? 'selected' : '' }}>Agendado</option>
                        <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <div style="display:flex; gap:0.5rem;">
                        <button type="submit" class="btn-gold" style="flex:1; justify-content:center;">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('agenda') }}" class="btn-ghost">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- CALENDÁRIO --}}
    <div class="cal-card" style="margin-bottom:1.25rem;">
        <div class="cal-header">
            <h5 class="cal-month-title">{{ $data->translatedFormat('F \d\e Y') }}</h5>
            <div class="cal-nav">
                <a href="{{ route('agenda', ['data' => $data->copy()->subMonth()->format('Y-m')]) }}" class="btn-nav">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('agenda') }}" class="btn-ghost" style="padding:0.3rem 0.85rem; font-size:0.78rem;">
                    Hoje
                </a>
                <a href="{{ route('agenda', ['data' => $data->copy()->addMonth()->format('Y-m')]) }}" class="btn-nav">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="cal-table">
                <thead>
                    <tr>
                        <th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th>
                        <th>Qui</th><th>Sex</th><th>Sáb</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($calendario); $i += 7)
                    <tr>
                        @for($j = 0; $j < 7; $j++)
                            @php
                                $idx = $i + $j;
                                $diaCalendario = $calendario[$idx] ?? null;
                                $isWeekend = in_array($j, [0, 6]);
                            @endphp
                            @if($diaCalendario)
                                @php
                                    $outOfMonth = $diaCalendario['data']->month != $data->month;
                                    $classes = 'cal-cell';
                                    if($diaCalendario['hoje'])              $classes .= ' is-today';
                                    elseif($isWeekend)                      $classes .= ' is-weekend';
                                    if($outOfMonth)                         $classes .= ' out-of-month';
                                    if($diaCalendario['tem_agendamentos'])  $classes .= ' has-events';
                                @endphp
                                <td class="{{ $classes }}">
                                    <div class="day-num">{{ $diaCalendario['dia'] }}</div>

                                    @if($diaCalendario['tem_agendamentos'])
                                        <div class="day-count">{{ $diaCalendario['total'] }}</div>
                                        <div class="day-events">
                                            @foreach($diaCalendario['agendamentos']->take(3) as $ag)
                                                <div class="day-event">
                                                    <div class="day-event-dot" style="background:
                                                        @if($ag->status === 'concluido') #5DBF95
                                                        @elseif($ag->status === 'cancelado') #D97070
                                                        @else #5BA3D9
                                                        @endif;"></div>
                                                    <span class="day-event-time">
                                                        {{ \Carbon\Carbon::parse($ag->horario)->format('H:i') }}
                                                    </span>
                                                    <span class="day-event-name">{{ $ag->nome_cliente }}</span>
                                                </div>
                                            @endforeach
                                            @if($diaCalendario['total'] > 3)
                                                <div class="day-more">+{{ $diaCalendario['total'] - 3 }} mais</div>
                                            @endif
                                        </div>
                                        {{-- O controller tem rota agenda.dia com o formato Y-m-d --}}
                                        <a href="{{ route('agenda.dia', $diaCalendario['data']->format('Y-m-d')) }}" class="cal-stretched-link"></a>
                                    @endif
                                </td>
                            @else
                                <td class="cal-cell empty-cell"></td>
                            @endif
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- $stats vem de calcularEstatisticas($agendamentos) no controller --}}
        <div class="cal-stats">
            <div class="cal-stat">
                <div class="cal-stat-label">Total do Mês</div>
                <div class="cal-stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="cal-stat">
                <div class="cal-stat-label">Agendados</div>
                <div class="cal-stat-value" style="color:#5BA3D9;">{{ $stats['agendados'] }}</div>
            </div>
            <div class="cal-stat">
                <div class="cal-stat-label">Concluídos</div>
                <div class="cal-stat-value" style="color:#5DBF95;">{{ $stats['concluidos'] }}</div>
            </div>
            <div class="cal-stat">
                <div class="cal-stat-label">Faturamento</div>
                <div class="cal-stat-value" style="color:var(--gold); font-size:1.15rem;">
                    R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- AGENDAMENTOS DO DIA --}}
    {{-- $agendamentosDoDia = $agendamentos->where('data', $data->format('Y-m-d'))->values() --}}
    <div class="cal-card">
        <div class="cal-header">
            <h5 class="cal-month-title" style="font-size:0.95rem;">
                Agendamentos · {{ $data->translatedFormat('d \d\e F') }}
            </h5>
            <span style="font-size:0.72rem; font-weight:700; background:rgba(201,168,76,0.12); color:var(--gold); padding:0.2rem 0.65rem; border-radius:20px; border:1px solid rgba(201,168,76,0.2);">
                {{ $agendamentosDoDia->count() }} agendamento{{ $agendamentosDoDia->count() !== 1 ? 's' : '' }}
            </span>
        </div>

        @if($agendamentosDoDia->isNotEmpty())
            @foreach($agendamentosDoDia as $ag)
            <div class="day-row">
                <div class="day-row-time">
                    <div class="day-row-time-label">Hora</div>
                    <div class="day-row-time-val">{{ \Carbon\Carbon::parse($ag->horario)->format('H:i') }}</div>
                </div>
                <div>
                    <div class="day-row-client">{{ $ag->nome_cliente }}</div>
                    <div class="day-row-service">{{ $ag->servico->nome }}</div>
                </div>
                <div class="day-row-barber">
                    <i class="fas fa-scissors" style="font-size:0.65rem; color:var(--gold-dim);"></i>
                    {{ $ag->barbeiro->nome }}
                </div>
                <div class="day-row-right">
                    <span class="status-badge {{ $ag->status }}">{{ ucfirst($ag->status) }}</span>
                    <a href="{{ route('agendamentos.edit', $ag) }}" class="btn-icon-sm" title="Editar">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align:center; padding:3rem 2rem; color:var(--text-muted);">
                <i class="fas fa-calendar-day" style="font-size:2rem; color:var(--dark-border); display:block; margin-bottom:0.75rem;"></i>
                <p style="font-size:0.875rem; margin:0;">Nenhum agendamento para este dia.</p>
            </div>
        @endif
    </div>

</div>
@endsection