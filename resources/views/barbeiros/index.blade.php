@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Barbeiros</h1>
    <button class="btn" onclick="openBarberModal()">
        <i class="fas fa-plus"></i>
        Adicionar Barbeiro
    </button>
</div>

<div class="items-grid">
    @foreach($barbeiros as $barbeiro)
        <div class="item-card">
            <div class="item-header">
                <div class="item-avatar">
                    <img src="{{ asset('images/avatar-placeholder.png') }}" alt="{{ $barbeiro->nome }}">
                </div>
                <div class="item-title">{{ $barbeiro->nome }}</div>
                <div class="item-actions">
                    <button class="btn-icon" onclick="editBarber({{ $barbeiro->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('barbeiros.destroy', $barbeiro) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="item-details">
                <div class="item-info">
                    <i class="fas fa-phone"></i>
                    <span>{{ $barbeiro->telefone }}</span>
                </div>
                <div class="item-info">
                    <i class="fas fa-cut"></i>
                    <span>{{ $barbeiro->especialidades }}</span>
                </div>
                <div class="item-info">
                    <i class="fas fa-clock"></i>
                    <span>{{ $barbeiro->inicio_trabalho->format('H:i') }} - {{ $barbeiro->fim_trabalho->format('H:i') }}</span>
                </div>
            </div>
        </div>
    @endforeach
    
    @if($barbeiros->isEmpty())
        <div class="empty-state">
            <i class="fas fa-user-tie"></i>
            <p>Nenhum barbeiro cadastrado</p>
            <button class="btn" onclick="openBarberModal()">Adicionar Primeiro Barbeiro</button>
        </div>
    @endif
</div>

<!-- Modal Barbeiro -->
<div id="barberModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="barberModalTitle">Novo Barbeiro</h2>
            <button class="modal-close" onclick="closeBarberModal()">&times;</button>
        </div>
        <form id="barberForm" action="{{ route('barbeiros.store') }}" method="POST">
            @csrf
            <input type="hidden" id="barberId" name="id">
            <input type="hidden" id="barberMethod" name="_method" value="POST">
            
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" required>
            </div>
            
            <div class="form-group">
                <label for="especialidades">Especialidades</label>
                <input type="text" id="especialidades" name="especialidades" placeholder="Cortes clássicos, barba, etc." required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="inicio_trabalho">Início</label>
                    <input type="time" id="inicio_trabalho" name="inicio_trabalho" required>
                </div>
                
                <div class="form-group">
                    <label for="fim_trabalho">Fim</label>
                    <input type="time" id="fim_trabalho" name="fim_trabalho" required>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeBarberModal()">Cancelar</button>
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i>
                    <span id="barberSubmitText">Criar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBarberModal() {
    document.getElementById('barberModalTitle').textContent = 'Novo Barbeiro';
    document.getElementById('barberSubmitText').textContent = 'Criar';
    document.getElementById('barberForm').reset();
    document.getElementById('barberForm').action = '{{ route('barbeiros.store') }}';
    document.getElementById('barberMethod').value = 'POST';
    document.getElementById('barberModal').style.display = 'flex';
}

function editBarber(id) {
    // Buscar dados do barbeiro via AJAX
    fetch(`/api/barbeiros/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('barberModalTitle').textContent = 'Editar Barbeiro';
            document.getElementById('barberSubmitText').textContent = 'Salvar';
            document.getElementById('barberId').value = data.id;
            document.getElementById('nome').value = data.nome;
            document.getElementById('telefone').value = data.telefone;
            document.getElementById('especialidades').value = data.especialidades;
            document.getElementById('inicio_trabalho').value = data.inicio_trabalho;
            document.getElementById('fim_trabalho').value = data.fim_trabalho;
            document.getElementById('barberForm').action = `{{ route('barbeiros.index') }}/${id}`;
            document.getElementById('barberMethod').value = 'PUT';
            document.getElementById('barberModal').style.display = 'flex';
        });
}

function closeBarberModal() {
    document.getElementById('barberModal').style.display = 'none';
}
</script>
@endsection