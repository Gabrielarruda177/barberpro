@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Agendamentos - {{ $data->format('d/m/Y') }}</h1>
        <div>
            <a href="{{ route('agenda') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar para a Agenda
            </a>
            <a href="{{ route('agendamentos.create') }}?data={{ $data->format('Y-m-d') }}" class="btn btn-primary ms-2">
                <i class="fas fa-plus"></i> Novo Agendamento
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($agendamentos->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Horário</th>
                                <th>Cliente</th>
                                <th>Barbeiro</th>
                                <th>Serviço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agendamentos as $ag)
                                <tr>
                                    <td>{{ $ag->horario }}</td>
                                    <td>{{ $ag->nome_cliente }}</td>
                                    <td>{{ $ag->barbeiro->nome }}</td>
                                    <td>{{ $ag->servico->nome }}</td>
                                    <td><span class="badge" style="background-color: {{ getCorPorStatus($ag->status) }};">{{ ucfirst($ag->status) }}</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('agendamentos.edit', $ag) }}" class="btn btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmarApagar({{ $ag->id }}, '{{ $ag->nome_cliente }}')" class="btn btn-outline-danger" title="Apagar">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum agendamento para este dia.</h5>
                    <p class="text-muted">Que tal criar um novo?</p>
                    <a href="{{ route('agendamentos.create') }}?data={{ $data->format('Y-m-d') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Agendamento
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection




    <style>
    /* Variáveis de Cor (assumindo do seu layout principal) */
    :root {
        --primary-color: #d4af37;
        --background-color: #1a1a1a;
        --secondary-color: #2d2d2d;
        --text-color: #f0f0f0;
        --text-muted: #a0a0a0;
        --border-color: #404040;
    }

    /* Estilos para a página do dia */
    .day-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .day-header-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .day-header-actions .page-title {
        margin: 0;
        font-size: 1.75rem;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-spacer {
        flex-grow: 1;
    }

    .day-info-card {
        background: var(--secondary-color);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .day-info-card h2 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--primary-color);
        text-transform: capitalize;
    }

    .day-subtitle {
        margin: 0.25rem 0 0;
        color: var(--text-muted);
        font-size: 1rem;
        text-transform: capitalize;
    }

    /* Estilos da lista de agendamentos (reutilizados da agenda.blade.php) */
    .day-appointments-list {
        background: var(--secondary-color);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
    }

    .day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .day-header h3 {
        font-size: 1.25rem;
        color: var(--text-color);
        margin: 0;
    }

    .appointment-count-badge {
        padding: 0.375rem 0.875rem;
        background: rgba(212, 175, 55, 0.15);
        border: 1px solid var(--primary-color);
        border-radius: 2rem;
        font-size: 0.8125rem;
        color: var(--primary-color);
        font-weight: 600;
    }

    .appointments-timeline {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .appointment-item {
        display: flex;
        gap: 1.25rem;
        padding: 1.25rem;
        background: var(--background-color);
        border-radius: 0.875rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .appointment-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .appointment-item.status-concluido {
        border-left: 4px solid #10b981;
    }

    .appointment-item.status-cancelado {
        border-left: 4px solid #ef4444;
    }

    .appointment-item.status-agendado {
        border-left: 4px solid #3b82f6;
    }

    .appointment-time {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 60px;
    }

    .appointment-time .time {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .appointment-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .appointment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .appointment-header h4 {
        font-size: 1.125rem;
        color: var(--text-color);
        margin: 0;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-badge.status-agendado { background: #dbeafe; color: #1e40af; }
    .status-badge.status-concluido { background: #d1fae5; color: #065f46; }
    .status-badge.status-cancelado { background: #fee2e2; color: #991b1b; }

    .appointment-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .detail-item i {
        color: var(--primary-color);
    }

    .appointment-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        padding: 0;
        text-decoration: none;
    }

    .btn-edit { color: #60a5fa; }
    .btn-edit:hover { background: rgba(59, 130, 246, 0.15); }

    .btn-delete { color: #f87171; }
    .btn-delete:hover { background: rgba(239, 68, 68, 0.15); }

    .empty-day {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-day i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-day p {
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
    }

    /* Estilos do Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-box {
        background: var(--secondary-color);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        color: var(--text-color);
    }

    .modal-icon {
        font-size: 3rem;
        color: #f87171;
        margin-bottom: 1rem;
    }

    .modal-box h3 {
        margin-bottom: 1rem;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .btn-cancel, .btn-confirm {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-cancel {
        background: var(--background-color);
        color: var(--text-muted);
        border: 1px solid var(--border-color);
    }

    .btn-cancel:hover {
        background: var(--border-color);
    }

    .btn-confirm {
        background: #ef4444;
        color: white;
    }

    .btn-confirm:hover {
        background: #dc2626;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .day-page {
            padding: 1rem;
        }
        .day-header-actions {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .appointment-item {
            flex-direction: column;
        }
        .appointment-time {
            flex-direction: row;
            justify-content: center;
            width: 100%;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        .appointment-details {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    </style>

@push('scripts')
<script>
// Função para confirmar o apagamento (soft delete)
function confirmarApagar(id, nomeCliente) {
    document.getElementById('clientName').textContent = nomeCliente;
    
    const form = document.getElementById('softDeleteForm');
    form.action = `/agendamentos/${id}/apagar`;
    
    const modal = document.getElementById('softDeleteModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Submeter o formulário de soft delete
const softDeleteForm = document.getElementById('softDeleteForm');
softDeleteForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = this.action;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }
    
    document.body.appendChild(form);
    form.submit();
});
</script>
@endpush