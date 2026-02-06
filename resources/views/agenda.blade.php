@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Agenda</h1>
</div>

<div class="agenda-container">
    <div class="calendar-section">
        <div class="calendar-header">
            <h2>{{ $data->format('F \d\e Y') }}</h2>
            <div class="calendar-nav">
                <a href="{{ route('agenda', ['data' => $data->copy()->subMonth()->format('Y-m-d')]) }}" class="btn-nav">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <a href="{{ route('agenda') }}" class="btn-nav">
                    <i class="fas fa-calendar-day"></i>
                </a>
                <a href="{{ route('agenda', ['data' => $data->copy()->addMonth()->format('Y-m-d')]) }}" class="btn-nav">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
        
        <div class="calendar-grid">
            <div class="calendar-day-header">Dom</div>
            <div class="calendar-day-header">Seg</div>
            <div class="calendar-day-header">Ter</div>
            <div class="calendar-day-header">Qua</div>
            <div class="calendar-day-header">Qui</div>
            <div class="calendar-day-header">Sex</div>
            <div class="calendar-day-header">Sáb</div>
            
            @php
                $firstDay = $data->copy()->startOfMonth()->dayOfWeek;
                $daysInMonth = $data->daysInMonth;
                $today = now()->format('Y-m-d');
                $selected = $data->format('Y-m-d');
            @endphp
            
            @for($i = 0; $i < $firstDay; $i++)
                <div class="calendar-day other-month"></div>
            @endfor
            
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = $data->copy()->setDay($day)->format('Y-m-d');
                    $dayAppointments = $agendamentos->get($data->copy()->setDay($day)->format('Y-m-d'), collect());
                    $isToday = $currentDate === $today;
                    $isSelected = $currentDate === $selected;
                @endphp
                
                <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $isSelected ? 'selected' : '' }}">
                    <a href="{{ route('agenda', ['data' => $currentDate]) }}" class="calendar-day-link">
                        {{ $day }}
                        @if($dayAppointments->count() > 0)
                            <span class="appointment-count">{{ $dayAppointments->count() }}</span>
                        @endif
                    </a>
                </div>
            @endfor
        </div>
    </div>
    
    <div class="day-appointments">
        <h3>{{ $data->format('d \d\e F') }}</h3>
        
        @if($agendamentosDia->count() > 0)
            <div class="appointments-list">
                @foreach($agendamentosDia as $agendamento)
                    <div class="appointment-item">
                        <div class="appointment-time">{{ $agendamento->horario->format('H:i') }}</div>
                        <div class="appointment-info">
                            <div class="appointment-client">{{ $agendamento->nome_cliente }}</div>
                            <div class="appointment-service">{{ $agendamento->servico->nome }}</div>
                            <div class="appointment-phone">{{ $agendamento->telefone_cliente }}</div>
                        </div>
                        <div class="appointment-barber">
                            <img src="{{ asset('images/avatar-placeholder.png') }}" alt="{{ $agendamento->barbeiro->nome }}">
                            <span>{{ $agendamento->barbeiro->nome }}</span>
                        </div>
                        <div class="appointment-actions">
                            <a href="{{ route('agendamentos.edit', $agendamento) }}" class="btn-icon">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('agendamentos.destroy', $agendamento) }}" method="POST" onsubmit="return confirm('Tem certeza?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Nenhum agendamento para este dia</p>
            </div>
        @endif
    </div>
</div>
@endsection