@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <div class="header-date">Bem-vindo! Hoje é {{ now()->format('d \d\e F \d\e Y') }}</div>
</div>

<div class="cards-grid">
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <div class="card-content">
            <div class="card-title">HOJE</div>
            <div class="card-value">{{ $agendamentosHoje }} agendamentos</div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="card-content">
            <div class="card-title">CONCLUÍDOS</div>
            <div class="card-value">{{ $concluidosHoje }} hoje</div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="card-content">
            <div class="card-title">FATURAMENTO</div>
            <div class="card-value">R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}</div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="card-content">
            <div class="card-title">ESTE MÊS</div>
            <div class="card-value">{{ $agendamentosMes }} agendamentos</div>
        </div>
    </div>
</div>

<div class="content-card">
    <h2>Agendamentos de Hoje</h2>
    
    @if($agendamentosDeHoje->count() > 0)
        <div class="appointments-list">
            @foreach($agendamentosDeHoje as $agendamento)
                <div class="appointment-item">
                    <div class="appointment-time">{{ $agendamento->horario->format('H:i') }}</div>
                    <div class="appointment-info">
                        <div class="appointment-client">{{ $agendamento->nome_cliente }}</div>
                        <div class="appointment-service">{{ $agendamento->servico->nome }}</div>
                    </div>
                    <div class="appointment-barber">{{ $agendamento->barbeiro->nome }}</div>
                    <div class="appointment-actions">
                        <a href="{{ route('agendamentos.edit', $agendamento) }}" class="btn-icon">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>Nenhum agendamento para hoje</p>
        </div>
    @endif
</div>
@endsection