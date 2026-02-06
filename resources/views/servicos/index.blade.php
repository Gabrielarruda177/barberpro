@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Serviços</h1>
    <button class="btn" onclick="openServiceModal()">
        <i class="fas fa-plus"></i>
        Adicionar Serviço
    </button>
</div>

<div class="items-grid">
    @foreach($servicos as $servico)
        <div class="item-card">
            <div class="item-header">
                <div class="item-title">{{ $servico->nome }}</div>
                <div class="item-actions">
                    <button class="btn-icon" onclick="editService({{ $servico->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('servicos.destroy', $servico) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="item-details">
                <div class="item-description">{{ $servico->descricao }}</div>
                <div class="item-info">
                    <i class="fas fa-clock"></i>
                    <span>{{ $servico->duracao_minutos }} min</span>
                </div>
                <div class="item-price">R$ {{ number_format($servico->preco, 2, ',', '.') }}</div>
                <div class="item-status">
                    <form action="{{ route('servicos.toggle-status', $servico) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="status-toggle {{ $servico->ativo ? 'active' : 'inactive' }}">
                            <i class="fas fa-{{ $servico->ativo ? 'check' : 'times' }}"></i>
                            {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    
    @if($servicos->isEmpty())
        <div class="empty-state">
            <i class="fas fa-cut"></i>
            <p>Nenhum serviço cadastrado</p>
            <button class="btn" onclick="openServiceModal()">Adicionar Primeiro Serviço</button>
        </div>
    @endif
</div>

<!-- Modal Serviço -->
<div id="serviceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="serviceModalTitle">Novo Serviço</h2>
            <button class="modal-close" onclick="closeServiceModal()">&times;</button>
        </div>
        <form id="serviceForm" action="{{ route('servicos.store') }}" method="POST">
            @csrf
            <input type="hidden" id="serviceId" name="id">
            <input type="hidden" id="serviceMethod" name="_method" value="POST">
            
            <div class="form-group">
                <label for="nome">Nome do Serviço</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="3" required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="duracao_minutos">Duração (minutos)</label>
                    <input type="number" id="duracao_minutos" name="duracao_minutos" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (R$)</label>
                    <input type="text" id="preco" name="preco" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeServiceModal()">Cancelar</button>
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i>
                    <span id="serviceSubmitText">Criar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openServiceModal() {
    document.getElementById('serviceModalTitle').textContent = 'Novo Serviço';
    document.getElementById('serviceSubmitText').textContent = 'Criar';
    document.getElementById('serviceForm').reset();
    document.getElementById('serviceForm').action = '{{ route('servicos.store') }}';
    document.getElementById('serviceMethod').value = 'POST';
    document.getElementById('serviceModal').style.display = 'flex';
}

function editService(id) {
    // Buscar dados do serviço via AJAX
    fetch(`/api/servicos/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('serviceModalTitle').textContent = 'Editar Serviço';
            document.getElementById('serviceSubmitText').textContent = 'Salvar';
            document.getElementById('serviceId').value = data.id;
            document.getElementById('nome').value = data.nome;
            document.getElementById('descricao').value = data.descricao;
            document.getElementById('duracao_minutos').value = data.duracao_minutos;
            document.getElementById('preco').value = data.preco;
            document.getElementById('serviceForm').action = `{{ route('servicos.index') }}/${id}`;
            document.getElementById('serviceMethod').value = 'PUT';
            document.getElementById('serviceModal').style.display = 'flex';
        });
}

function closeServiceModal() {
    document.getElementById('serviceModal').style.display = 'none';
}
</script>
@endsection