@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="fas fa-calendar-alt me-2"></i>
            Agenda de Agendamentos
        </h2>
        <div class="btn-group">
            <a href="{{ route('agendamentos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Novo Agendamento
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('agendamentos.index') }}" class="row g-3" id="filterForm">
                <input type="hidden" name="view" value="{{ $viewMode }}">
                
                <div class="col-md-3">
                    <label class="form-label">Mês/Ano</label>
                    <input type="month" name="selected_date" class="form-control" 
                           value="{{ $selectedDate->format('Y-m') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Barbeiro</label>
                    <select name="barbeiro_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($barbeiros as $barbeiro)
                            <option value="{{ $barbeiro->id }}" 
                                    {{ $barbeiroId == $barbeiro->id ? 'selected' : '' }}>
                                {{ $barbeiro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="agendado" {{ $status == 'agendado' ? 'selected' : '' }}>Agendado</option>
                        <option value="concluido" {{ $status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelado" {{ $status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('agendamentos.index') }}?view={{ $viewMode }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-2"><div class="card text-white bg-primary"><div class="card-body"><h6>Total</h6><h3>{{ $estatisticas['total'] }}</h3></div></div></div>
        <div class="col-md-2"><div class="card text-white bg-info"><div class="card-body"><h6>Agendados</h6><h3>{{ $estatisticas['agendados'] }}</h3></div></div></div>
        <div class="col-md-2"><div class="card text-white bg-success"><div class="card-body"><h6>Concluídos</h6><h3>{{ $estatisticas['concluidos'] }}</h3></div></div></div>
        <div class="col-md-2"><div class="card text-white bg-danger"><div class="card-body"><h6>Cancelados</h6><h3>{{ $estatisticas['cancelados'] }}</h3></div></div></div>
        <div class="col-md-4"><div class="card text-white bg-warning"><div class="card-body"><h6>Faturamento do Mês</h6><h3>R$ {{ number_format($estatisticas['valor_total'], 2, ',', '.') }}</h3></div></div></div>
    </div>

    <!-- Botão de Toggle de View -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('agendamentos.index') }}?view={{ $viewMode === 'list' ? 'calendar' : 'list' }}&selected_date={{ $selectedDate->format('Y-m-d') }}" class="btn btn-outline-secondary">
            <i class="fas fa-exchange-alt"></i>
            Ver como {{ $viewMode === 'list' ? 'Calendário' : 'Lista' }}
        </a>
    </div>

    <!-- VIEW DE LISTA -->
    @if($viewMode === 'list')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lista de Agendamentos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Barbeiro</th>
                                <th>Serviço</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agendamentos as $agendamento)
                                <tr>
                                    <td>{{ $agendamento->data->format('d/m/Y') }}</td>
                                    <td>{{ $agendamento->horario }}</td>
                                    <td>{{ $agendamento->nome_cliente }}</td>
                                    <td>{{ $agendamento->barbeiro->nome }}</td>
                                    <td>{{ $agendamento->servico->nome }}</td>
                                    <td>R$ {{ number_format($agendamento->valor, 2, ',', '.') }}</td>
                                    <td><span class="badge" style="background-color: {{ getCorPorStatus($agendamento->status) }};">{{ ucfirst($agendamento->status) }}</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('agendamentos.edit', $agendamento) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('agendamentos.destroy', $agendamento) }}" method="POST" onsubmit="return confirm('Tem certeza?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum agendamento encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $agendamentos->links() }}
            </div>
        </div>
    @endif

    <!-- VIEW DE CALENDÁRIO -->
    @if($viewMode === 'calendar')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $data->format('F Y') }}</h5>
                <div class="btn-group">
                    <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $data->copy()->subMonth()->format('Y-m-d') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-chevron-left"></i></a>
                    <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ Carbon::today()->format('Y-m-d') }}" class="btn btn-sm btn-outline-primary">Hoje</a>
                    <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $data->copy()->addMonth()->format('Y-m-d') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light"><tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr></thead>
                        <tbody>
                            @for($semana = 0; $semana < 6; $semana++)
                                <tr>
                                    @for($dia = 0; $dia < 7; $dia++)
                                        <td class="p-2 align-top" style="height: 120px; width: 14.28%;">
                                            @php
                                                $index = ($semana * 7) + $dia;
                                                $diaCalendario = isset($calendario[$index]) ? $calendario[$index] : null;
                                            @endphp
                                            @if($diaCalendario)
                                                <div class="h-100 d-flex flex-column position-relative">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <span class="badge @if($diaCalendario['hoje']) bg-primary @elseif($diaCalendario['fim_de_semana']) bg-secondary @else bg-light text-dark @endif">{{ $diaCalendario['dia'] }}</span>
                                                        @if($diaCalendario['tem_agendamentos'])
                                                            <span class="badge bg-success">{{ $diaCalendario['total'] }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="small overflow-auto">
                                                        @foreach($diaCalendario['agendamentos']->take(2) as $ag)
                                                            <div class="text-truncate" style="font-size: 0.7em;">
                                                                <i class="fas fa-circle" style="color: {{ getCorPorStatus($ag->status) }}; font-size: 0.5em;"></i>
                                                                {{ $ag->horario }} {{ $ag->nome_cliente }}
                                                            </div>
                                                        @endforeach
                                                        @if($diaCalendario['total'] > 2)
                                                            <small class="text-muted">+{{ $diaCalendario['total'] - 2 }} mais</small>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('agendamentos.index') }}?view=calendar&selected_date={{ $diaCalendario['data']->format('Y-m-d') }}" class="stretched-link"></a>
                                                </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Lista de Agendamentos do Dia Selecionado -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Agendamentos do dia {{ $selectedDate->format('d/m/Y') }} <span class="badge bg-primary ms-2">{{ $agendamentosDia->count() }}</span></h5>
            </div>
            <div class="card-body">
                @if($agendamentosDia->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table">
                            <thead><tr><th>Horário</th><th>Cliente</th><th>Barbeiro</th><th>Serviço</th><th>Status</th><th>Ações</th></tr></thead>
                            <tbody>
                                @foreach($agendamentosDia as $ag)
                                    <tr>
                                        <td>{{ $ag->horario }}</td>
                                        <td>{{ $ag->nome_cliente }}</td>
                                        <td>{{ $ag->barbeiro->nome }}</td>
                                        <td>{{ $ag->servico->nome }}</td>
                                        <td><span class="badge" style="background-color: {{ getCorPorStatus($ag->status) }};">{{ ucfirst($ag->status) }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('agendamentos.edit', $ag) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('agendamentos.destroy', $ag) }}" method="POST" onsubmit="return confirm('Tem certeza?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted">Nenhum agendamento para este dia.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection