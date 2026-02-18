<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarberPro - @yield('title', 'Sistema de Gestão')</title>

    <!-- CSS Principal (Laravel Mix/Vite) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts (necessário para a página de barbeiros) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <!-- 
        ✅ A LINHA QUE ESTAVA FALTANDO!
        Esta diretiva insere o CSS da página (ex: da página de barbeiros) aqui.
    -->
    @yield('styles')

</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <i class="fas fa-cut"></i>
                <span>BarberPro</span>
            </div>
            
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('agenda') }}" class="nav-item {{ request()->routeIs('agenda*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Agenda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('agendamentos.index') }}" class="nav-item {{ request()->routeIs('agendamentos.index') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Agendamentos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('barbeiros.index') }}" class="nav-item {{ request()->routeIs('barbeiros.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Barbeiros</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('servicos.index') }}" class="nav-item {{ request()->routeIs('servicos.*') ? 'active' : '' }}">
                        <i class="fas fa-cut"></i>
                        <span>Serviços</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>

    <!-- Modal de Soft Delete (mantive sua lógica) -->
    <div class="modal-overlay" id="softDeleteModal" style="display: none;">
        <div class="modal-box" style="background: #2d2d2d; border: 1px solid #404040; border-radius: 1rem; padding: 2rem; max-width: 400px; width: 90%; text-align: center; color: #f0f0f0;">
            <div class="modal-icon" style="font-size: 3rem; color: #f87171; margin-bottom: 1rem;">
                <i class="fas fa-eye-slash"></i>
            </div>
            <h3>Confirmar Apagar</h3>
            <p>Tem certeza que deseja apagar o agendamento de <strong id="clientName"></strong>?</p>
            <p style="font-size: 0.85rem; color: #a0a0a0; margin-top: 0.5rem;">
                O agendamento será ocultado da agenda, mas permanecerá no sistema para histórico.
            </p>
            
            <form id="softDeleteForm" method="POST" style="margin-top: 1.5rem;">
                @csrf
                <div class="modal-actions" style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem;">
                    <button type="button" class="btn-cancel" onclick="fecharModal()" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; border: 1px solid #404040; background: #1a1a1a; color: #a0a0a0;">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-confirm" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; border: none; background: #ef4444; color: white;">
                        <i class="fas fa-eye-slash"></i>
                        Apagar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stack para scripts adicionais das páginas -->
    @stack('scripts')

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Script para o Modal (mantido, pois é global) -->
    <script>
        function fecharModal() {
            const modal = document.getElementById('softDeleteModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('softDeleteModal');
            modal.addEventListener('click', function(e) { if (e.target === modal) fecharModal(); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && modal.style.display === 'flex') fecharModal(); });
        });
    </script>
</body>
</html>