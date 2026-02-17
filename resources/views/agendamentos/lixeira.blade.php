2@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">
            <i class="fas fa-trash me-2"></i>
            Lixeira de Agendamentos
        </h2>
        <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($agendamentos->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Barbeiro</th>
                                <th>Serviço</th>
                                <th>Valor</th>
                                <th>Apagado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agendamentos as $agendamento)
                                <tr>
                                    <td>{{ $agendamento->nome_cliente }}</td>
                                    <td>{{ $agendamento->data->format('d/m/Y') }}</td>
                                    <td>{{ $agendamento->horario }}</td>
                                    <td>{{ $agendamento->barbeiro->nome }}</td>
                                    <td>{{ $agendamento->servico->nome }}</td>
                                    <td>R$ {{ number_format($agendamento->valor, 2, ',', '.') }}</td>
                                    <td>{{ $agendamento->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('agendamentos.restore', $agendamento->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" 
                                                        title="Restaurar">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('agendamentos.forceDelete', $agendamento->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Deseja excluir permanentemente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        title="Excluir permanentemente">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $agendamentos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                    <h5>Nenhum agendamento na lixeira</h5>
                    <a href="{{ route('agendamentos.index') }}" class="btn btn-primary">
                        Ver Agendamentos
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection