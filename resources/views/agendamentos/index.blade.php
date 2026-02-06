@extends('layouts.app')

@section('content')
<div class="appointments-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">Gerenciar Agendamentos</h1>
                <p class="page-subtitle">Visualize e gerencie todos os agendamentos da sua barbearia</p>
            </div>
            <a href="{{ route('agendamentos.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Novo Agendamento
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filters-header">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filtrar Agendamentos
            </h3>
        </div>
        <form method="GET" action="{{ route('agendamentos.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="form-group">
                    <label for="filter_data" class="form-label">
                        <i class="fas fa-calendar"></i>
                        Data
                    </label>
                    <input type="date" id="filter_data" name="data" value="{{ request('data') }}" class="form-input">
                </div>
                <div class="form-group">
                    <label for="filter_status" class="form-label">
                        <i class="fas fa-flag"></i>
                        Status
                    </label>
                    <select id="filter_status" name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="agendado" {{ request('status') == 'agendado' ? 'selected' : '' }}>Agendado</option>
                        <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                        <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_barbeiro" class="form-label">
                        <i class="fas fa-user-tie"></i>
                        Barbeiro
                    </label>
                    <select id="filter_barbeiro" name="barbeiro_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($barbeiros as $barbeiro)
                            <option value="{{ $barbeiro->id }}" {{ request('barbeiro_id') == $barbeiro->id ? 'selected' : '' }}>
                                {{ $barbeiro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="filters-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('agendamentos.index') }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="results-summary">
        <p class="results-text">
            Encontrados <strong>{{ $agendamentos->count() }}</strong> agendamentos
            @if(request('data') || request('status') || request('barbeiro_id'))
                <span>com os filtros aplicados</span>
            @endif
        </p>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-wrapper">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Serviço</th>
                        <th>Barbeiro</th>
                        <th>Data / Horário</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendamentos as $agendamento)
                        <tr>
                            <td>
                                <div class="client-info">
                                    <div class="client-avatar">
                                        {{ substr($agendamento->nome_cliente, 0, 1) }}
                                    </div>
                                    <div class="client-details">
                                        <div class="client-name">{{ $agendamento->nome_cliente }}</div>
                                        <div class="client-phone">{{ $agendamento->telefone_cliente }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="service-tag">{{ $agendamento->servico->nome }}</span>
                            </td>
                            <td>
                                <div class="barber-info">
                                    <div class="barber-avatar">
                                        {{ substr($agendamento->barbeiro->nome, 0, 1) }}
                                    </div>
                                    <div class="barber-name">{{ $agendamento->barbeiro->nome }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="datetime-info">
                                    <div class="date-text">{{ $agendamento->data->format('d/m/Y') }}</div>
                                    <div class="time-text">{{ $agendamento->horario->format('H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="price-value">R$ {{ number_format($agendamento->valor, 2, ',', '.') }}</div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $agendamento->status }}">
                                    @if($agendamento->status == 'agendado')
                                        <i class="fas fa-clock"></i>
                                    @elseif($agendamento->status == 'concluido')
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                    {{ ucfirst($agendamento->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('agendamentos.edit', $agendamento) }}" class="btn-action btn-edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('agendamentos.destroy', $agendamento) }}" method="POST" class="inline-form" onsubmit="return confirm('Tem certeza que deseja excluir este agendamento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <h3>Nenhum agendamento encontrado</h3>
                                        <p>Tente ajustar os filtros ou crie um novo agendamento</p>
                                        <a href="{{ route('agendamentos.create') }}" class="btn-primary">Criar Agendamento</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginação -->
        @if($agendamentos->hasPages())
            <div class="pagination-wrapper">
                {{ $agendamentos->links() }}
            </div>
        @endif
    </div>
</div>

<style>
/* Main Container */
.appointments-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
    background: #0f0f0f;
    min-height: 100vh;
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.header-text {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0 0 0.5rem 0;
}

.page-subtitle {
    font-size: 0.95rem;
    color: #9ca3af;
    margin: 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    border: none;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

/* Filters Card */
.filters-card {
    background: #1a1a1a;
    border-radius: 1rem;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    border: 1px solid #2a2a2a;
}

.filters-header {
    margin-bottom: 1.5rem;
}

.filters-title {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
}

.filters-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
}

.form-input,
.form-select {
    padding: 0.75rem 1rem;
    background: #0f0f0f;
    border: 1px solid #2a2a2a;
    border-radius: 0.625rem;
    color: #ffffff;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.form-select {
    cursor: pointer;
}

.filters-actions {
    display: flex;
    gap: 0.75rem;
    padding-top: 0.5rem;
}

.btn-filter,
.btn-clear {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 0.625rem;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-filter {
    background: #f59e0b;
    color: #ffffff;
    border: none;
}

.btn-filter:hover {
    background: #d97706;
}

.btn-clear {
    background: transparent;
    color: #9ca3af;
    border: 1px solid #2a2a2a;
}

.btn-clear:hover {
    background: #1a1a1a;
    color: #ffffff;
    border-color: #3a3a3a;
}

/* Results Summary */
.results-summary {
    margin-bottom: 1rem;
}

.results-text {
    font-size: 0.875rem;
    color: #9ca3af;
    margin: 0;
}

.results-text strong {
    color: #f59e0b;
    font-weight: 600;
}

/* Table Card */
.table-card {
    background: #1a1a1a;
    border-radius: 1rem;
    border: 1px solid #2a2a2a;
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
}

.appointments-table {
    width: 100%;
    border-collapse: collapse;
}

.appointments-table thead {
    background: #0f0f0f;
    border-bottom: 1px solid #2a2a2a;
}

.appointments-table th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.appointments-table tbody tr {
    border-bottom: 1px solid #2a2a2a;
    transition: background 0.2s ease;
}

.appointments-table tbody tr:hover {
    background: #1f1f1f;
}

.appointments-table tbody tr:last-child {
    border-bottom: none;
}

.appointments-table td {
    padding: 1.25rem 1.25rem;
    font-size: 0.9rem;
    color: #e5e7eb;
}

/* Client Info */
.client-info {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.client-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: #ffffff;
    flex-shrink: 0;
}

.client-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.client-name {
    font-weight: 600;
    color: #ffffff;
    font-size: 0.95rem;
}

.client-phone {
    font-size: 0.8125rem;
    color: #9ca3af;
}

/* Service Tag */
.service-tag {
    display: inline-block;
    padding: 0.375rem 0.875rem;
    background: #2a2a2a;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    color: #f59e0b;
    font-weight: 500;
}

/* Barber Info */
.barber-info {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}

.barber-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #2a2a2a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    color: #9ca3af;
    flex-shrink: 0;
}

.barber-name {
    color: #e5e7eb;
    font-size: 0.9rem;
}

/* DateTime Info */
.datetime-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.date-text {
    font-weight: 500;
    color: #ffffff;
    font-size: 0.9rem;
}

.time-text {
    font-size: 0.8125rem;
    color: #9ca3af;
}

/* Price Value */
.price-value {
    font-weight: 600;
    color: #10b981;
    font-size: 0.95rem;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-agendado {
    background: rgba(59, 130, 246, 0.15);
    color: #60a5fa;
}

.status-concluido {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
}

.status-cancelado {
    background: rgba(239, 68, 68, 0.15);
    color: #f87171;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.inline-form {
    display: inline;
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
}

.btn-edit {
    color: #60a5fa;
}

.btn-edit:hover {
    background: rgba(59, 130, 246, 0.15);
}

.btn-delete {
    color: #f87171;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.15);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem !important;
}

.empty-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    text-align: center;
}

.empty-content svg {
    color: #4b5563;
}

.empty-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #9ca3af;
    margin: 0;
}

.empty-content p {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem 1.25rem;
    border-top: 1px solid #2a2a2a;
}

/* Responsive */
@media (max-width: 1024px) {
    .filters-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .appointments-page {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .btn-primary {
        width: 100%;
        justify-content: center;
    }

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .filters-actions {
        flex-direction: column;
    }

    .btn-filter,
    .btn-clear {
        width: 100%;
        justify-content: center;
    }

    .page-title {
        font-size: 1.5rem;
    }

    /* Hide less important columns on mobile */
    .appointments-table th:nth-child(2),
    .appointments-table td:nth-child(2),
    .appointments-table th:nth-child(3),
    .appointments-table td:nth-child(3) {
        display: none;
    }

    .appointments-table th,
    .appointments-table td {
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
    }

    .client-avatar {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }
}

@media (max-width: 480px) {
    .datetime-info {
        font-size: 0.8125rem;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endsection