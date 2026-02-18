@extends('layouts.app')

@section('content')
<div class="edit-appointment-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="{{ route('agendamentos.index') }}" class="breadcrumb-link">
            <i class="fas fa-arrow-left"></i>
            Agendamentos
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">Editar</span>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-info">
            <h1>Editar Agendamento</h1>
            <p class="header-subtitle">Atualize as informações do agendamento de <strong>{{ $agendamento->nome_cliente }}</strong></p>
        </div>
        <div class="header-badge">
            <span class="badge-label">ID</span>
            <span class="badge-value">#{{ str_pad($agendamento->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Atenção!</strong> Corrija os seguintes erros:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('agendamentos.edit', $agendamento) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <!-- Cliente Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    <h3>Informações do Cliente</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome_cliente">
                            Nome do Cliente
                            <span class="required">*</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                id="nome_cliente" 
                                name="nome_cliente" 
                                value="{{ old('nome_cliente', $agendamento->nome_cliente) }}" 
                                placeholder="Digite o nome completo"
                                required
                            >
                        </div>
                        @error('nome_cliente')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="telefone_cliente">
                            Telefone
                            <span class="required">*</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input 
                                type="tel" 
                                id="telefone_cliente" 
                                name="telefone_cliente" 
                                value="{{ old('telefone_cliente', $agendamento->telefone_cliente) }}" 
                                placeholder="(00) 00000-0000"
                                required
                            >
                        </div>
                        @error('telefone_cliente')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Serviço Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-cut"></i>
                    <h3>Detalhes do Serviço</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="barbeiro_id">
                            Barbeiro
                            <span class="required">*</span>
                        </label>
                        <div class="select-wrapper">
                            <i class="fas fa-user-tie select-icon"></i>
                            <select id="barbeiro_id" name="barbeiro_id" required>
                                <option value="">Selecione um barbeiro</option>
                                @foreach($barbeiros as $barbeiro)
                                    <option value="{{ $barbeiro->id }}" {{ old('barbeiro_id', $agendamento->barbeiro_id) == $barbeiro->id ? 'selected' : '' }}>
                                        {{ $barbeiro->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        @error('barbeiro_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="servico_id">
                            Serviço
                            <span class="required">*</span>
                        </label>
                        <div class="select-wrapper">
                            <i class="fas fa-scissors select-icon"></i>
                            <select id="servico_id" name="servico_id" required>
                                <option value="">Selecione um serviço</option>
                                @foreach($servicos as $servico)
                                    <option 
                                        value="{{ $servico->id }}" 
                                        data-price="{{ $servico->preco }}"
                                        {{ old('servico_id', $agendamento->servico_id) == $servico->id ? 'selected' : '' }}
                                    >
                                        {{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        @error('servico_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Price Display -->
                <div class="price-display" id="priceDisplay" style="display: none;">
                    <i class="fas fa-dollar-sign"></i>
                    <div class="price-info">
                        <span class="price-label">Valor do Serviço</span>
                        <span class="price-value" id="priceValue">R$ 0,00</span>
                    </div>
                </div>
            </div>

            <!-- Data e Horário Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Data e Horário</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="data">
                            Data
                            <span class="required">*</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-calendar input-icon"></i>
                            <input 
                                type="date" 
                                id="data" 
                                name="data" 
                                value="{{ old('data', $agendamento->data->format('Y-m-d')) }}" 
                                required
                            >
                        </div>
                        @error('data')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="horario">
                            Horário
                            <span class="required">*</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-clock input-icon"></i>
                            <input 
                                type="time" 
                                id="horario" 
                                name="horario" 
                                value="{{ old('horario', $agendamento->horario->format('H:i')) }}" 
                                required
                            >
                        </div>
                        @error('horario')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status e Observações Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Status e Observações</h3>
                </div>

                <div class="form-group">
                    <label for="status">
                        Status do Agendamento
                        <span class="required">*</span>
                    </label>
                    
                    <div class="status-cards">
                        <label class="status-card">
                            <input 
                                type="radio" 
                                name="status" 
                                value="agendado" 
                                {{ old('status', $agendamento->status) == 'agendado' ? 'checked' : '' }}
                                required
                            >
                            <div class="status-content status-agendado">
                                <i class="fas fa-clock"></i>
                                <span>Agendado</span>
                            </div>
                        </label>

                        <label class="status-card">
                            <input 
                                type="radio" 
                                name="status" 
                                value="concluido" 
                                {{ old('status', $agendamento->status) == 'concluido' ? 'checked' : '' }}
                                required
                            >
                            <div class="status-content status-concluido">
                                <i class="fas fa-check-circle"></i>
                                <span>Concluído</span>
                            </div>
                        </label>

                        <label class="status-card">
                            <input 
                                type="radio" 
                                name="status" 
                                value="cancelado" 
                                {{ old('status', $agendamento->status) == 'cancelado' ? 'checked' : '' }}
                                required
                            >
                            <div class="status-content status-cancelado">
                                <i class="fas fa-times-circle"></i>
                                <span>Cancelado</span>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="observacoes">
                        Observações
                        <span class="optional">(Opcional)</span>
                    </label>
                    <textarea 
                        id="observacoes" 
                        name="observacoes" 
                        rows="4" 
                        placeholder="Adicione observações sobre o agendamento..."
                    >{{ old('observacoes', $agendamento->observacoes) }}</textarea>
                    <div class="char-counter">
                        <span id="charCount">0</span> caracteres
                    </div>
                    @error('observacoes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('agendamentos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                
                <div class="action-buttons">
                    <button type="button" class="btn btn-preview" id="previewBtn">
                        <i class="fas fa-eye"></i>
                        Visualizar
                    </button>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i>
                        Atualizar Agendamento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal" id="previewModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Prévia do Agendamento</h2>
            <button class="modal-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="previewContent">
            <!-- Conteúdo será preenchido via JavaScript -->
        </div>
    </div>
</div>

<style>
/* Edit Appointment Styles */
.edit-appointment-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Breadcrumb */
.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: var(--primary-color);
}

.breadcrumb-separator {
    color: var(--text-muted);
}

.breadcrumb-current {
    color: var(--text-color);
}

/* Page Header Enhancement */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
}

.header-info h1 {
    margin-bottom: 8px;
}

.header-subtitle {
    font-size: 14px;
    color: var(--text-muted);
}

.header-subtitle strong {
    color: var(--primary-color);
}

.header-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    padding: 12px 20px;
    background-color: rgba(212, 175, 55, 0.1);
    border-radius: 8px;
    border: 1px solid var(--primary-color);
}

.badge-label {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.badge-value {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-color);
}

/* Form Sections */
.form-section {
    background-color: var(--secondary-color);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}

.section-header i {
    font-size: 20px;
    color: var(--primary-color);
}

.section-header h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

/* Input with Icon */
.input-icon-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
}

.input-icon-wrapper input {
    padding-left: 45px !important;
}

/* Select with Icon */
.select-wrapper {
    position: relative;
}

.select-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 14px;
    pointer-events: none;
    z-index: 1;
}

.select-arrow {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 12px;
    pointer-events: none;
}

.select-wrapper select {
    padding-left: 45px !important;
    padding-right: 40px !important;
    appearance: none;
}

/* Required and Optional Indicators */
.required {
    color: var(--danger-color);
    margin-left: 4px;
}

.optional {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 400;
    margin-left: 4px;
}

/* Error Messages */
.error-message {
    display: block;
    color: var(--danger-color);
    font-size: 13px;
    margin-top: 5px;
}

/* Price Display */
.price-display {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    background-color: rgba(76, 175, 80, 0.1);
    border-radius: 8px;
    border: 1px solid var(--success-color);
    margin-top: 15px;
}

.price-display i {
    font-size: 24px;
    color: var(--success-color);
}

.price-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.price-label {
    font-size: 12px;
    color: var(--text-muted);
}

.price-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--success-color);
}

/* Status Cards */
.status-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.status-card {
    cursor: pointer;
}

.status-card input[type="radio"] {
    display: none;
}

.status-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 20px;
    background-color: var(--background-color);
    border: 2px solid var(--border-color);
    border-radius: 10px;
    transition: all 0.3s ease;
    text-align: center;
}

.status-content i {
    font-size: 28px;
}

.status-content span {
    font-size: 14px;
    font-weight: 600;
}

.status-card input[type="radio"]:checked + .status-content {
    border-width: 2px;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.05);
}

.status-agendado {
    border-color: var(--warning-color);
}

.status-card input[type="radio"]:checked + .status-agendado {
    background-color: rgba(255, 152, 0, 0.1);
    border-color: var(--warning-color);
}

.status-agendado i,
.status-agendado span {
    color: var(--warning-color);
}

.status-concluido {
    border-color: var(--success-color);
}

.status-card input[type="radio"]:checked + .status-concluido {
    background-color: rgba(76, 175, 80, 0.1);
    border-color: var(--success-color);
}

.status-concluido i,
.status-concluido span {
    color: var(--success-color);
}

.status-cancelado {
    border-color: var(--danger-color);
}

.status-card input[type="radio"]:checked + .status-cancelado {
    background-color: rgba(244, 67, 54, 0.1);
    border-color: var(--danger-color);
}

.status-cancelado i,
.status-cancelado span {
    color: var(--danger-color);
}

/* Character Counter */
.char-counter {
    text-align: right;
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 5px;
}

/* Form Actions Enhancement */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-top: 30px;
    padding: 20px;
    background-color: var(--secondary-color);
    border-radius: 12px;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.btn-preview {
    background-color: var(--border-color);
    color: var(--text-color);
}

.btn-preview:hover {
    background-color: #444;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background-color: var(--secondary-color);
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid var(--border-color);
}

.modal-header h2 {
    font-size: 20px;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    color: var(--text-muted);
    font-size: 20px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--text-color);
}

.modal-body {
    padding: 25px;
    max-height: calc(90vh - 80px);
    overflow-y: auto;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}

.preview-item:last-child {
    border-bottom: none;
}

.preview-label {
    color: var(--text-muted);
    font-size: 14px;
}

.preview-value {
    color: var(--text-color);
    font-weight: 600;
    font-size: 14px;
    text-align: right;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
    }

    .header-badge {
        align-self: flex-start;
    }

    .status-cards {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .action-buttons {
        width: 100%;
        flex-direction: column;
    }

    .btn, .btn-secondary, .btn-preview {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Price Display
    const servicoSelect = document.getElementById('servico_id');
    const priceDisplay = document.getElementById('priceDisplay');
    const priceValue = document.getElementById('priceValue');

    function updatePrice() {
        const selectedOption = servicoSelect.options[servicoSelect.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        
        if (price && price !== '') {
            priceDisplay.style.display = 'flex';
            priceValue.textContent = 'R$ ' + parseFloat(price).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            priceDisplay.style.display = 'none';
        }
    }

    if (servicoSelect) {
        updatePrice(); // Initial price display
        servicoSelect.addEventListener('change', updatePrice);
    }

    // Phone Mask
    const telefone = document.getElementById('telefone_cliente');
    if (telefone) {
        telefone.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
                e.target.value = value;
            }
        });
    }

    // Character Counter
    const observacoes = document.getElementById('observacoes');
    const charCount = document.getElementById('charCount');

    if (observacoes && charCount) {
        charCount.textContent = observacoes.value.length;
        
        observacoes.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Preview Modal
    const previewBtn = document.getElementById('previewBtn');
    const previewModal = document.getElementById('previewModal');
    const closeModal = document.getElementById('closeModal');
    const previewContent = document.getElementById('previewContent');

    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const formData = new FormData(document.getElementById('editForm'));
            
            const nome = formData.get('nome_cliente');
            const telefone = formData.get('telefone_cliente');
            const barbeiro = document.getElementById('barbeiro_id').options[document.getElementById('barbeiro_id').selectedIndex].text;
            const servico = document.getElementById('servico_id').options[document.getElementById('servico_id').selectedIndex].text;
            const data = formData.get('data');
            const horario = formData.get('horario');
            const status = formData.get('status');
            const obs = formData.get('observacoes');

            let html = '<div style="display: flex; flex-direction: column; gap: 0;">';
            
            html += `<div class="preview-item"><span class="preview-label">Cliente</span><span class="preview-value">${nome}</span></div>`;
            html += `<div class="preview-item"><span class="preview-label">Telefone</span><span class="preview-value">${telefone}</span></div>`;
            html += `<div class="preview-item"><span class="preview-label">Barbeiro</span><span class="preview-value">${barbeiro}</span></div>`;
            html += `<div class="preview-item"><span class="preview-label">Serviço</span><span class="preview-value">${servico}</span></div>`;
            
            if (data) {
                const dataFormatada = new Date(data + 'T00:00:00').toLocaleDateString('pt-BR');
                html += `<div class="preview-item"><span class="preview-label">Data</span><span class="preview-value">${dataFormatada}</span></div>`;
            }
            
            html += `<div class="preview-item"><span class="preview-label">Horário</span><span class="preview-value">${horario}</span></div>`;
            html += `<div class="preview-item"><span class="preview-label">Status</span><span class="preview-value">${status.charAt(0).toUpperCase() + status.slice(1)}</span></div>`;
            
            if (obs && obs.trim() !== '') {
                html += `<div class="preview-item"><span class="preview-label">Observações</span><span class="preview-value" style="max-width: 60%; text-align: right;">${obs}</span></div>`;
            }
            
            html += '</div>';
            
            previewContent.innerHTML = html;
            previewModal.classList.add('active');
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            previewModal.classList.remove('active');
        });
    }

    // Close modal when clicking outside
    if (previewModal) {
        previewModal.addEventListener('click', function(e) {
            if (e.target === previewModal) {
                previewModal.classList.remove('active');
            }
        });
    }
});
</script>

@endsection