@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Novo Agendamento</h1>
</div>

<div class="form-container">
    <form action="{{ route('agendamentos.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="nome_cliente">Nome do Cliente</label>
                <input type="text" id="nome_cliente" name="nome_cliente" value="{{ old('nome_cliente', 'João Silva') }}" required>
            </div>
            
            <div class="form-group">
                <label for="telefone_cliente">Telefone</label>
                <input type="tel" id="telefone_cliente" name="telefone_cliente" value="{{ old('telefone_cliente', '(11) 99999-9999') }}" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="barbeiro_id">Barbeiro</label>
                <select id="barbeiro_id" name="barbeiro_id" required>
                    <option value="">Selecione um barbeiro</option>
                    @foreach($barbeiros as $barbeiro)
                        <option value="{{ $barbeiro->id }}" {{ old('barbeiro_id') == $barbeiro->id ? 'selected' : '' }}>
                            {{ $barbeiro->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="servico_id">Serviço</label>
                <select id="servico_id" name="servico_id" required>
                    <option value="">Selecione um serviço</option>
                    @foreach($servicos as $servico)
                        <option value="{{ $servico->id }}" data-price="{{ $servico->preco }}" {{ old('servico_id') == $servico->id ? 'selected' : '' }}>
                            {{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="data">Data</label>
                <input type="date" id="data" name="data" value="{{ old('data', now()->format('Y-m-d')) }}" required>
            </div>
            
            <div class="form-group">
                <label for="horario">Horário</label>
                <input type="time" id="horario" name="horario" value="{{ old('horario') }}" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes" rows="3">{{ old('observacoes') }}</textarea>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn">
                <i class="fas fa-save"></i>
                Criar Agendamento
            </button>
        </div>
    </form>
</div>
@endsection