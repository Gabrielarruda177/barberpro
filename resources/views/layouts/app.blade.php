<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberPro - Sistema de Gestão</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <!-- Modals -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title"></h2>
                <button class="modal-close" id="modal-close">&times;</button>
            </div>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>