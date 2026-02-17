@extends('layouts.app')

{{--
    DADOS NECESSÁRIOS NO CONTROLLER (DashboardController):
    ─────────────────────────────────────────────────────
    $agendamentosHoje      → int
    $concluidosHoje        → int
    $faturamentoHoje       → float
    $agendamentosMes       → int
    $faturamentoMes        → float
    $agendamentosDeHoje    → Collection (com barbeiro, servico)
    $canceladosHoje        → int
    $taxaConclusao         → float (%)

    // Faturamento últimos 30 dias: ['2026-01-19' => 320.00, ...]
    $faturamentoDiario     → array (keyed by date, last 30 days)

    // Agendamentos por status no mês: ['agendado'=>12,'concluido'=>45,'cancelado'=>3]
    $statusMes             → array

    // Agendamentos por barbeiro no mês: [['nome'=>'João','total'=>20,'valor'=>800], ...]
    $porBarbeiro           → Collection

    // Agendamentos por hora do dia: [8=>3, 9=>7, 10=>12, ...]
    $porHora               → array

    // Top serviços do mês: [['nome'=>'Corte','total'=>30,'valor'=>600], ...]
    $topServicos           → Collection (top 6)

    // Agendamentos por dia da semana: ['Dom'=>2,'Seg'=>15, ...]
    $porDiaSemana          → array
--}}

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
        --c-agendado: #5BA3D9;
        --c-concluido: #5DBF95;
        --c-cancelado: #D97070;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body { background: var(--dark); color: var(--text-primary); font-family: 'DM Sans', sans-serif; }

    /* ═══ HEADER ═══ */
    .dash-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 1.75rem; padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--dark-border); position: relative;
    }
    .dash-header::after {
        content:''; position:absolute; bottom:-1px; left:0;
        width:80px; height:2px; background:var(--gold);
    }
    .dash-title {
        font-family:'Playfair Display',serif; font-size:2rem; font-weight:700;
        color:var(--text-primary); letter-spacing:-0.02em; margin:0;
    }
    .dash-date { font-size:0.78rem; color:var(--text-muted); text-align:right; }
    .dash-date strong { display:block; font-size:0.88rem; color:var(--text-dim); font-weight:500; margin-top:0.1rem; }

    /* ═══ QUICK ACTIONS ═══ */
    .quick-actions { display:flex; gap:0.75rem; margin-bottom:1.5rem; }
    .btn-gold {
        background:var(--gold); color:#0D0D0D; border:none; padding:0.55rem 1.2rem;
        border-radius:7px; font-family:'DM Sans',sans-serif; font-weight:600; font-size:0.875rem;
        cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;
        text-decoration:none; transition:background 0.2s, transform 0.15s;
    }
    .btn-gold:hover { background:var(--gold-light); color:#0D0D0D; transform:translateY(-1px); }
    .btn-ghost {
        background:transparent; color:var(--text-dim); border:1px solid var(--dark-border);
        padding:0.5rem 1.1rem; border-radius:7px; font-family:'DM Sans',sans-serif;
        font-weight:500; font-size:0.875rem; cursor:pointer; display:inline-flex;
        align-items:center; gap:0.4rem; text-decoration:none;
        transition:border-color 0.2s, color 0.2s, background 0.2s;
    }
    .btn-ghost:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.06); }

    /* ═══ STAT CARDS ═══ */
    .stats-grid {
        display:grid; grid-template-columns:repeat(5, 1fr);
        gap:1rem; margin-bottom:1.5rem;
    }
    @media(max-width:1200px){ .stats-grid { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:768px)  { .stats-grid { grid-template-columns:repeat(2,1fr); } }

    .stat-card {
        background:var(--dark-card); border:1px solid var(--dark-border);
        border-radius:14px; padding:1.2rem 1.3rem;
        display:flex; align-items:center; gap:1rem;
        position:relative; overflow:hidden;
        transition:border-color 0.25s, transform 0.2s; cursor:default;
    }
    .stat-card:hover { border-color:var(--gold-dim); transform:translateY(-2px); }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:2px;
    }
    .stat-card.s-hoje::before       { background:var(--gold); }
    .stat-card.s-concluido::before  { background:#3D8B68; }
    .stat-card.s-fat-hoje::before   { background:linear-gradient(90deg,var(--gold),var(--gold-light)); }
    .stat-card.s-mes::before        { background:#2D6FA3; }
    .stat-card.s-fat-mes::before    { background:linear-gradient(90deg,#3D8B68,var(--gold)); }

    .stat-icon {
        width:44px; height:44px; border-radius:11px;
        display:flex; align-items:center; justify-content:center;
        font-size:1.1rem; flex-shrink:0;
    }
    .s-hoje     .stat-icon { background:rgba(201,168,76,0.12);  color:var(--gold); }
    .s-concluido .stat-icon{ background:rgba(61,139,104,0.12);  color:#5DBF95; }
    .s-fat-hoje .stat-icon { background:rgba(201,168,76,0.12);  color:var(--gold); }
    .s-mes      .stat-icon { background:rgba(45,111,163,0.12);  color:#5BA3D9; }
    .s-fat-mes  .stat-icon { background:rgba(93,191,149,0.12);  color:#5DBF95; }

    .stat-body { flex:1; min-width:0; }
    .stat-label { font-size:0.62rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-muted); margin-bottom:0.25rem; }
    .stat-value { font-family:'Playfair Display',serif; font-size:1.55rem; font-weight:700; color:var(--text-primary); line-height:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .s-fat-hoje .stat-value,
    .s-fat-mes  .stat-value { color:var(--gold); font-size:1.2rem; }
    .stat-sub { font-size:0.68rem; color:var(--text-muted); margin-top:0.2rem; }

    /* ═══ CARD GENÉRICO ═══ */
    .g-card { background:var(--dark-card); border:1px solid var(--dark-border); border-radius:14px; overflow:hidden; }
    .g-card-header {
        display:flex; justify-content:space-between; align-items:center;
        padding:1rem 1.4rem; border-bottom:1px solid var(--dark-border);
        background:var(--dark-elevated);
    }
    .g-card-title { font-family:'Playfair Display',serif; font-size:0.95rem; font-weight:600; color:var(--text-primary); margin:0; }
    .g-card-sub { font-size:0.7rem; color:var(--text-muted); }
    .g-card-body { padding:1.2rem 1.4rem; }

    /* ═══ GRID LAYOUTS ═══ */
    .grid-2-1 { display:grid; grid-template-columns:2fr 1fr; gap:1.25rem; margin-bottom:1.25rem; }
    .grid-3   { display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-bottom:1.25rem; }
    .grid-2   { display:grid; grid-template-columns:repeat(2,1fr); gap:1.25rem; margin-bottom:1.25rem; }
    @media(max-width:1100px){
        .grid-2-1 { grid-template-columns:1fr; }
        .grid-3   { grid-template-columns:1fr 1fr; }
    }
    @media(max-width:768px){
        .grid-3, .grid-2 { grid-template-columns:1fr; }
    }

    /* ═══ CHART WRAPPERS ═══ */
    .chart-wrap { position:relative; }
    .chart-wrap canvas { display:block; }

    /* ═══ DONUT LEGEND ═══ */
    .donut-wrap { display:flex; flex-direction:column; align-items:center; gap:1rem; }
    .donut-center-wrap { position:relative; width:180px; height:180px; }
    .donut-center-label {
        position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
        text-align:center; pointer-events:none;
    }
    .donut-center-label .dcl-val {
        font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:var(--text-primary); line-height:1;
    }
    .donut-center-label .dcl-sub { font-size:0.62rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.08em; }
    .donut-legend { display:flex; flex-direction:column; gap:0.5rem; width:100%; }
    .dl-item { display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; }
    .dl-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .dl-name { color:var(--text-dim); flex:1; }
    .dl-val  { color:var(--text-primary); font-weight:600; }
    .dl-pct  { color:var(--text-muted); font-size:0.68rem; }

    /* ═══ BARBEIRO BARS ═══ */
    .barber-bar-item { margin-bottom:0.9rem; }
    .barber-bar-item:last-child { margin-bottom:0; }
    .bb-header { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:0.3rem; }
    .bb-name { font-size:0.82rem; font-weight:500; color:var(--text-primary); }
    .bb-val  { font-size:0.75rem; color:var(--gold); font-weight:600; }
    .bb-track { background:var(--dark-elevated); border-radius:4px; height:6px; overflow:hidden; }
    .bb-fill  { height:100%; border-radius:4px; background:linear-gradient(90deg,var(--gold-dim),var(--gold-light)); transition:width 1s cubic-bezier(.4,0,.2,1); }

    /* ═══ SERVICE TABLE ═══ */
    .svc-table { width:100%; border-collapse:collapse; font-size:0.82rem; }
    .svc-table thead th {
        font-size:0.62rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;
        color:var(--text-muted); padding:0.4rem 0.75rem; border-bottom:1px solid var(--dark-border);
        text-align:left;
    }
    .svc-table tbody tr { border-bottom:1px solid rgba(255,255,255,0.04); transition:background 0.15s; }
    .svc-table tbody tr:last-child { border-bottom:none; }
    .svc-table tbody tr:hover { background:rgba(201,168,76,0.03); }
    .svc-table td { padding:0.55rem 0.75rem; color:var(--text-dim); vertical-align:middle; }
    .svc-table td:first-child { color:var(--text-primary); font-weight:500; }
    .rank-badge {
        width:20px; height:20px; border-radius:50%; background:var(--dark-elevated);
        border:1px solid var(--dark-border); color:var(--text-muted); font-size:0.62rem; font-weight:700;
        display:inline-flex; align-items:center; justify-content:center;
    }
    .rank-badge.gold { background:rgba(201,168,76,0.15); border-color:var(--gold-dim); color:var(--gold); }
    .rank-badge.silver { background:rgba(180,180,180,0.1); border-color:#555; color:#aaa; }
    .rank-badge.bronze { background:rgba(180,120,60,0.12); border-color:#7a4b1e; color:#c2844e; }

    /* ═══ TAXA DE CONCLUSÃO ═══ */
    .taxa-wrap { display:flex; align-items:center; gap:1.5rem; padding:0.5rem 0; }
    .taxa-ring-wrap { position:relative; width:100px; height:100px; flex-shrink:0; }
    .taxa-ring-label {
        position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
        text-align:center;
    }
    .taxa-ring-label .trl-val { font-family:'Playfair Display',serif; font-size:1.1rem; font-weight:700; color:var(--text-primary); line-height:1; }
    .taxa-ring-label .trl-sub { font-size:0.55rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.06em; }
    .taxa-info { flex:1; }
    .taxa-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem; font-size:0.78rem; }
    .taxa-row .tr-label { color:var(--text-muted); }
    .taxa-row .tr-val { color:var(--text-primary); font-weight:600; }

    /* ═══ APPOINTMENTS LIST ═══ */
    .appointments-list { padding:0.25rem 0; }
    .appointment-item {
        display:grid; grid-template-columns:60px 1fr auto auto;
        align-items:center; gap:1rem; padding:0.8rem 1.4rem;
        border-bottom:1px solid rgba(255,255,255,0.04); transition:background 0.15s;
    }
    .appointment-item:last-child { border-bottom:none; }
    .appointment-item:hover { background:rgba(201,168,76,0.03); }
    .time-col { border-right:1px solid var(--dark-border); padding-right:1rem; text-align:center; }
    .appt-time { font-family:'Playfair Display',serif; font-size:1rem; font-weight:600; color:var(--gold); }
    .appt-time-label { font-size:0.58rem; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted); font-weight:600; margin-bottom:0.05rem; }
    .appt-client { font-weight:600; font-size:0.9rem; color:var(--text-primary); margin-bottom:0.12rem; }
    .appt-service { font-size:0.75rem; color:var(--text-muted); }
    .appt-barber { font-size:0.78rem; color:var(--text-dim); font-weight:500; white-space:nowrap; display:flex; align-items:center; gap:0.3rem; }
    .appt-barber i { font-size:0.62rem; color:var(--gold-dim); }
    .appt-status { display:inline-flex; align-items:center; gap:0.3rem; padding:0.2rem 0.55rem; border-radius:20px; font-size:0.66rem; font-weight:700; letter-spacing:0.04em; white-space:nowrap; }
    .appt-status::before { content:''; width:5px; height:5px; border-radius:50%; }
    .appt-status.agendado  { background:rgba(45,111,163,0.15); color:#5BA3D9; } .appt-status.agendado::before  { background:#5BA3D9; }
    .appt-status.concluido { background:rgba(61,139,104,0.15); color:#5DBF95; } .appt-status.concluido::before { background:#5DBF95; }
    .appt-status.cancelado { background:rgba(139,51,51,0.15);  color:#D97070; } .appt-status.cancelado::before { background:#D97070; }
    .btn-icon-sm {
        width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center;
        border-radius:6px; font-size:0.75rem; text-decoration:none; border:1px solid var(--dark-border);
        color:var(--text-muted); background:transparent; transition:all 0.2s;
    }
    .btn-icon-sm:hover { border-color:var(--gold-dim); color:var(--gold); background:rgba(201,168,76,0.08); }

    .count-pill { font-size:0.7rem; font-weight:700; background:rgba(201,168,76,0.12); color:var(--gold); padding:0.18rem 0.6rem; border-radius:20px; border:1px solid rgba(201,168,76,0.2); }

    .empty-state { text-align:center; padding:3rem 2rem; color:var(--text-muted); }
    .empty-state i { font-size:2rem; color:var(--dark-border); margin-bottom:0.75rem; display:block; }
    .empty-state p { font-size:0.875rem; margin:0; }
</style>

<div class="container-fluid" style="padding:1.5rem; max-width:1500px;">

    {{-- ── HEADER ── --}}
    <div class="dash-header">
        <h1 class="dash-title">Dashboard</h1>
        <div class="dash-date">Bem-vindo de volta<strong>{{ now()->translatedFormat('d \d\e F \d\e Y') }}</strong></div>
    </div>

    {{-- ── QUICK ACTIONS ── --}}
    <div class="quick-actions">
        <a href="{{ route('agendamentos.create') }}" class="btn-gold"><i class="fas fa-plus"></i> Novo Agendamento</a>
        <a href="{{ route('agendamentos.index') }}" class="btn-ghost"><i class="fas fa-calendar-alt"></i> Ver Agenda</a>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stats-grid">
        <div class="stat-card s-hoje">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-body">
                <div class="stat-label">Hoje</div>
                <div class="stat-value">{{ $agendamentosHoje }}</div>
                <div class="stat-sub">agendamentos</div>
            </div>
        </div>
        <div class="stat-card s-concluido">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-body">
                <div class="stat-label">Concluídos</div>
                <div class="stat-value">{{ $concluidosHoje }}</div>
                <div class="stat-sub">hoje</div>
            </div>
        </div>
        <div class="stat-card s-fat-hoje">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-body">
                <div class="stat-label">Fat. Hoje</div>
                <div class="stat-value">R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</div>
                <div class="stat-sub">faturamento</div>
            </div>
        </div>
        <div class="stat-card s-mes">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-body">
                <div class="stat-label">Este Mês</div>
                <div class="stat-value">{{ $agendamentosMes }}</div>
                <div class="stat-sub">agendamentos</div>
            </div>
        </div>
        <div class="stat-card s-fat-mes">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-body">
                <div class="stat-label">Fat. Mês</div>
                <div class="stat-value">R$ {{ number_format($faturamentoMes, 2, ',', '.') }}</div>
                <div class="stat-sub">faturamento</div>
            </div>
        </div>
    </div>

    {{-- ── ROW 1: Faturamento 30d (grande) + Status do Mês (donut) ── --}}
    <div class="grid-2-1">
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Faturamento · Últimos 30 Dias</h5>
                <span class="g-card-sub">receita diária</span>
            </div>
            <div class="g-card-body">
                <div class="chart-wrap" style="height:220px;">
                    <canvas id="chartFaturamento"></canvas>
                </div>
            </div>
        </div>
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Status · Mês</h5>
                <span class="g-card-sub">distribuição</span>
            </div>
            <div class="g-card-body">
                <div class="donut-wrap">
                    <div class="donut-center-wrap">
                        <canvas id="chartStatus"></canvas>
                        <div class="donut-center-label">
                            <div class="dcl-val" id="donutTotal">–</div>
                            <div class="dcl-sub">total</div>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="dl-item"><div class="dl-dot" style="background:#5BA3D9"></div><span class="dl-name">Agendados</span><span class="dl-val">{{ $statusMes['agendado'] ?? 0 }}</span></div>
                        <div class="dl-item"><div class="dl-dot" style="background:#5DBF95"></div><span class="dl-name">Concluídos</span><span class="dl-val">{{ $statusMes['concluido'] ?? 0 }}</span></div>
                        <div class="dl-item"><div class="dl-dot" style="background:#D97070"></div><span class="dl-name">Cancelados</span><span class="dl-val">{{ $statusMes['cancelado'] ?? 0 }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW 2: Agendamentos por hora + Por dia da semana + Taxa de conclusão ── --}}
    <div class="grid-3">
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Pico de Horários</h5>
                <span class="g-card-sub">agendamentos/hora</span>
            </div>
            <div class="g-card-body">
                <div class="chart-wrap" style="height:190px;">
                    <canvas id="chartHorario"></canvas>
                </div>
            </div>
        </div>
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Por Dia da Semana</h5>
                <span class="g-card-sub">volume médio</span>
            </div>
            <div class="g-card-body">
                <div class="chart-wrap" style="height:190px;">
                    <canvas id="chartDiaSemana"></canvas>
                </div>
            </div>
        </div>
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Taxa de Conclusão</h5>
                <span class="g-card-sub">mês atual</span>
            </div>
            <div class="g-card-body">
                <div class="taxa-wrap">
                    <div class="taxa-ring-wrap">
                        <canvas id="chartTaxa"></canvas>
                        <div class="taxa-ring-label">
                            <div class="trl-val">{{ number_format($taxaConclusao ?? 0, 0) }}%</div>
                            <div class="trl-sub">taxa</div>
                        </div>
                    </div>
                    <div class="taxa-info">
                        <div class="taxa-row"><span class="tr-label">Concluídos</span><span class="tr-val">{{ $statusMes['concluido'] ?? 0 }}</span></div>
                        <div class="taxa-row"><span class="tr-label">Cancelados</span><span class="tr-val">{{ $statusMes['cancelado'] ?? 0 }}</span></div>
                        <div class="taxa-row"><span class="tr-label">Agendados</span><span class="tr-val">{{ $statusMes['agendado'] ?? 0 }}</span></div>
                        <div class="taxa-row" style="margin-top:0.5rem; padding-top:0.5rem; border-top:1px solid var(--dark-border);">
                            <span class="tr-label">Total</span>
                            <span class="tr-val" style="color:var(--gold);">{{ ($statusMes['agendado'] ?? 0) + ($statusMes['concluido'] ?? 0) + ($statusMes['cancelado'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ROW 3: Performance Barbeiros + Top Serviços ── --}}
    <div class="grid-2">
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Performance por Barbeiro</h5>
                <span class="g-card-sub">agendamentos no mês</span>
            </div>
            <div class="g-card-body" style="padding-bottom:0.5rem;">
                {{-- Barras estáticas (animadas por CSS) + mini chart --}}
                <div class="chart-wrap" style="height:180px; margin-bottom:1rem;">
                    <canvas id="chartBarbeiro"></canvas>
                </div>
                @foreach($porBarbeiro as $b)
                @php $maxB = $porBarbeiro->max('total') ?: 1; @endphp
                <div class="barber-bar-item">
                    <div class="bb-header">
                        <span class="bb-name">{{ $b['nome'] ?? $b->nome }}</span>
                        <span class="bb-val">R$ {{ number_format($b['valor'] ?? $b->valor ?? 0, 2, ',', '.') }}</span>
                    </div>
                    <div class="bb-track">
                        <div class="bb-fill" style="width:{{ (($b['total'] ?? $b->total ?? 0) / $maxB) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="g-card">
            <div class="g-card-header">
                <h5 class="g-card-title">Top Serviços</h5>
                <span class="g-card-sub">mais realizados no mês</span>
            </div>
            <div class="g-card-body" style="padding:0;">
                <div style="padding:1rem 1.4rem;">
                    <div class="chart-wrap" style="height:160px;">
                        <canvas id="chartServicos"></canvas>
                    </div>
                </div>
                <table class="svc-table">
                    <thead>
                        <tr>
                            <th style="width:32px;">#</th>
                            <th>Serviço</th>
                            <th>Qtd</th>
                            <th>Receita</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topServicos as $i => $s)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : ($i === 2 ? 'bronze' : '')) }}">
                                    {{ $i + 1 }}
                                </span>
                            </td>
                            <td>{{ $s['nome'] ?? $s->nome }}</td>
                            <td>{{ $s['total'] ?? $s->total }}</td>
                            <td style="color:var(--gold); font-weight:500;">R$ {{ number_format($s['valor'] ?? $s->valor ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── ROW 4: Agendamentos de Hoje ── --}}
    <div class="g-card" style="margin-bottom:1.25rem;">
        <div class="g-card-header">
            <h5 class="g-card-title">Agendamentos de Hoje</h5>
            @if($agendamentosDeHoje->count() > 0)
            <span class="count-pill">{{ $agendamentosDeHoje->count() }} agendamento{{ $agendamentosDeHoje->count() !== 1 ? 's' : '' }}</span>
            @endif
        </div>
        @if($agendamentosDeHoje->count() > 0)
        <div class="appointments-list">
            @foreach($agendamentosDeHoje as $ag)
            <div class="appointment-item">
                <div class="time-col">
                    <div class="appt-time-label">Hora</div>
                    <div class="appt-time">{{ \Carbon\Carbon::parse($ag->horario)->format('H:i') }}</div>
                </div>
                <div>
                    <div class="appt-client">{{ $ag->nome_cliente }}</div>
                    <div class="appt-service">{{ $ag->servico->nome }}</div>
                </div>
                <div class="appt-barber"><i class="fas fa-scissors"></i> {{ $ag->barbeiro->nome }}</div>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <span class="appt-status {{ $ag->status }}">{{ ucfirst($ag->status) }}</span>
                    <a href="{{ route('agendamentos.edit', $ag) }}" class="btn-icon-sm"><i class="fas fa-pen"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>Nenhum agendamento para hoje. <a href="{{ route('agendamentos.create') }}" style="color:var(--gold);text-decoration:none;font-weight:500;">Criar agendamento →</a></p>
        </div>
        @endif
    </div>

</div>

{{-- ═══════════════════ SCRIPTS ═══════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Dados do Laravel → JS ──────────────────────────────────────────────────

// Faturamento diário (últimos 30 dias)
const fatDiario = @json($faturamentoDiario ?? []);

// Status do mês
const statusMes = {
    agendado:  {{ $statusMes['agendado']  ?? 0 }},
    concluido: {{ $statusMes['concluido'] ?? 0 }},
    cancelado: {{ $statusMes['cancelado'] ?? 0 }},
};

// Por hora (0–23)
const porHora = @json($porHora ?? []);

// Por dia da semana
const porDiaSemana = @json($porDiaSemana ?? []);

// Por barbeiro
const barbeiroLabels = @json($porBarbeiro->pluck('nome') ?? []);
const barbeiroTotais = @json($porBarbeiro->pluck('total') ?? []);

// Por serviço
const servicoLabels = @json($topServicos->pluck('nome') ?? []);
const servicoTotais = @json($topServicos->pluck('total') ?? []);

// Taxa de conclusão
const taxaConclusao = {{ $taxaConclusao ?? 0 }};

// ── Defaults Chart.js ──────────────────────────────────────────────────────
Chart.defaults.color         = '#6B6560';
Chart.defaults.font.family   = "'DM Sans', sans-serif";
Chart.defaults.font.size     = 11;
Chart.defaults.borderColor   = '#2A2A2A';

const gridOpts = {
    color: 'rgba(255,255,255,0.04)',
    drawBorder: false,
};

const noTicks = { display: false };
const hideLegend = { display: false };

// ── 1. FATURAMENTO 30 DIAS ─────────────────────────────────────────────────
(function(){
    const labels = Object.keys(fatDiario).map(d => {
        const [y,m,dd] = d.split('-');
        return `${dd}/${m}`;
    });
    const values = Object.values(fatDiario);

    const ctx = document.getElementById('chartFaturamento').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 220);
    grad.addColorStop(0, 'rgba(201,168,76,0.25)');
    grad.addColorStop(1, 'rgba(201,168,76,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data: values,
                borderColor: '#C9A84C',
                borderWidth: 2,
                backgroundColor: grad,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#C9A84C',
                pointHoverBorderColor: '#0D0D0D',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: hideLegend, tooltip: {
                backgroundColor: '#1C1C1C',
                borderColor: '#2A2A2A', borderWidth: 1,
                titleColor: '#F0EDE8', bodyColor: '#9C9690',
                callbacks: {
                    label: ctx => ' R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits:2})
                }
            }},
            scales: {
                x: { grid: { display:false }, ticks: { maxTicksLimit: 10, maxRotation:0 } },
                y: { grid: gridOpts, ticks: {
                    callback: v => 'R$ ' + v.toLocaleString('pt-BR')
                }}
            }
        }
    });
})();

// ── 2. STATUS DONUT ────────────────────────────────────────────────────────
(function(){
    const total = statusMes.agendado + statusMes.concluido + statusMes.cancelado;
    document.getElementById('donutTotal').textContent = total;

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Agendado','Concluído','Cancelado'],
            datasets: [{
                data: [statusMes.agendado, statusMes.concluido, statusMes.cancelado],
                backgroundColor: ['#2D6FA3','#3D8B68','#8B3333'],
                borderColor: '#141414',
                borderWidth: 3,
                hoverBorderColor: '#141414',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: hideLegend,
                tooltip: {
                    backgroundColor:'#1C1C1C', borderColor:'#2A2A2A', borderWidth:1,
                    titleColor:'#F0EDE8', bodyColor:'#9C9690',
                }
            }
        }
    });
})();

// ── 3. PICO DE HORÁRIOS ────────────────────────────────────────────────────
(function(){
    // porHora é { "08": 3, "09": 7, ... }
    const hours = [];
    const vals  = [];
    for(let h = 7; h <= 20; h++){
        hours.push(h.toString().padStart(2,'0') + 'h');
        vals.push(porHora[h] || porHora[h.toString().padStart(2,'0')] || 0);
    }

    const ctx = document.getElementById('chartHorario').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 190);
    grad.addColorStop(0, 'rgba(201,168,76,0.8)');
    grad.addColorStop(1, 'rgba(201,168,76,0.2)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: hours,
            datasets: [{
                data: vals,
                backgroundColor: grad,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: hideLegend, tooltip: {
                backgroundColor:'#1C1C1C', borderColor:'#2A2A2A', borderWidth:1,
                titleColor:'#F0EDE8', bodyColor:'#9C9690',
                callbacks: { label: ctx => ` ${ctx.parsed.y} agendamentos` }
            }},
            scales: {
                x: { grid:{ display:false }, ticks:{ maxRotation:0 } },
                y: { grid: gridOpts, ticks:{ stepSize:1 } }
            }
        }
    });
})();

// ── 4. DIA DA SEMANA ───────────────────────────────────────────────────────
(function(){
    const ordem = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
    const vals  = ordem.map(d => porDiaSemana[d] || 0);
    const maxV  = Math.max(...vals);

    new Chart(document.getElementById('chartDiaSemana'), {
        type: 'radar',
        data: {
            labels: ordem,
            datasets: [{
                data: vals,
                backgroundColor: 'rgba(201,168,76,0.12)',
                borderColor: '#C9A84C',
                borderWidth: 2,
                pointBackgroundColor: '#C9A84C',
                pointBorderColor: '#0D0D0D',
                pointBorderWidth: 1.5,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: hideLegend, tooltip:{
                backgroundColor:'#1C1C1C', borderColor:'#2A2A2A', borderWidth:1,
                titleColor:'#F0EDE8', bodyColor:'#9C9690',
            }},
            scales: {
                r: {
                    grid:{ color:'rgba(255,255,255,0.06)' },
                    angleLines:{ color:'rgba(255,255,255,0.06)' },
                    ticks:{ display:false, stepSize: Math.max(1, Math.ceil(maxV/4)) },
                    pointLabels:{ color:'#9C9690', font:{ size:11, weight:'500' } },
                }
            }
        }
    });
})();

// ── 5. TAXA DE CONCLUSÃO (ring) ────────────────────────────────────────────
(function(){
    const restante = Math.max(0, 100 - taxaConclusao);
    new Chart(document.getElementById('chartTaxa'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [taxaConclusao, restante],
                backgroundColor: ['#3D8B68', '#1C1C1C'],
                borderColor: ['#3D8B68', '#2A2A2A'],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: hideLegend, tooltip:{ enabled:false } },
        }
    });
})();

// ── 6. BARBEIROS (horizontal bar) ─────────────────────────────────────────
(function(){
    const colors = ['#C9A84C','#E8C96A','#8B6914','#A08535','#6B5520'];
    new Chart(document.getElementById('chartBarbeiro'), {
        type: 'bar',
        data: {
            labels: barbeiroLabels,
            datasets: [{
                data: barbeiroTotais,
                backgroundColor: barbeiroLabels.map((_,i) => colors[i % colors.length]),
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: hideLegend, tooltip:{
                backgroundColor:'#1C1C1C', borderColor:'#2A2A2A', borderWidth:1,
                titleColor:'#F0EDE8', bodyColor:'#9C9690',
                callbacks:{ label: ctx => ` ${ctx.parsed.x} agendamentos` }
            }},
            scales: {
                x: { grid: gridOpts, ticks:{ stepSize:1 } },
                y: { grid:{ display:false } }
            }
        }
    });
})();

// ── 7. SERVIÇOS (polar area) ───────────────────────────────────────────────
(function(){
    const colors = ['#C9A84C','#5BA3D9','#5DBF95','#D97070','#A07AD9','#E8A45C'];
    new Chart(document.getElementById('chartServicos'), {
        type: 'polarArea',
        data: {
            labels: servicoLabels,
            datasets: [{
                data: servicoTotais,
                backgroundColor: colors.map(c => c + '55'),
                borderColor: colors,
                borderWidth: 1.5,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true, position: 'right',
                    labels:{ color:'#9C9690', font:{ size:10 }, boxWidth:10, padding:8 }
                },
                tooltip:{
                    backgroundColor:'#1C1C1C', borderColor:'#2A2A2A', borderWidth:1,
                    titleColor:'#F0EDE8', bodyColor:'#9C9690',
                    callbacks:{ label: ctx => ` ${ctx.parsed.r} realizados` }
                }
            },
            scales: {
                r: {
                    grid:{ color:'rgba(255,255,255,0.05)' },
                    ticks:{ display:false },
                }
            }
        }
    });
})();
</script>
@endsection