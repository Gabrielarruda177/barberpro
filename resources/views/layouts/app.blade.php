<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarberPro — @yield('title', 'Sistema de Gestão')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    @yield('styles')
</head>
<body>

<div class="app-container" id="appContainer">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <button class="sidebar-toggle" id="sidebarToggle" title="Expandir/Recolher">
                <i class="fas fa-bars"></i>
            </button>
            <span class="logo-text">
                <i class="fas fa-cut" style="color:var(--gold);font-size:0.85rem;margin-right:0.3rem;"></i>
                BarberPro
            </span>
        </div>

        <nav>
            <ul class="nav-menu">

                <li class="nav-section-label">Principal</li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <span class="nav-tooltip">Dashboard</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('agenda') }}"
                       class="nav-link {{ request()->routeIs('agenda*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="nav-label">Agenda</span>
                    </a>
                    <span class="nav-tooltip">Agenda</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('agendamentos.index') }}"
                       class="nav-link {{ request()->routeIs('agendamentos.index') || request()->routeIs('agendamentos.create') || request()->routeIs('agendamentos.edit') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="nav-label">Agendamentos</span>
                        @php
                            try {
                                $pendentesHoje = \App\Models\Agendamento::where('status','agendado')
                                    ->whereDate('data', today())->count();
                            } catch(\Exception $e) { $pendentesHoje = 0; }
                        @endphp
                        @if(!empty($pendentesHoje) && $pendentesHoje > 0)
                            <span class="nav-badge">{{ $pendentesHoje }}</span>
                        @endif
                    </a>
                    <span class="nav-tooltip">Agendamentos</span>
                </li>

                <li class="nav-divider"></li>
                <li class="nav-section-label">Cadastros</li>

                <li class="nav-item">
                    <a href="{{ route('barbeiros.index') }}"
                       class="nav-link {{ request()->routeIs('barbeiros.index') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                        <span class="nav-label">Barbeiros</span>
                    </a>
                    <span class="nav-tooltip">Barbeiros</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('servicos.index') }}"
                       class="nav-link {{ request()->routeIs('servicos.index') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas fa-cut"></i></span>
                        <span class="nav-label">Serviços</span>
                    </a>
                    <span class="nav-tooltip">Serviços</span>
                </li>

                <li class="nav-divider"></li>
                <li class="nav-section-label">Sistema</li>

                {{-- ── LIXEIRA com submenu ── --}}
                @php
                    $lixeiraAtiva = request()->routeIs('agendamentos.lixeira')
                                 || request()->routeIs('barbeiros.lixeira')
                                 || request()->routeIs('servicos.lixeira');

                    try {
                        $totalLixeira = \App\Models\Agendamento::onlyTrashed()->count()
                                      + \App\Models\Barbeiro::onlyTrashed()->count()
                                      + \App\Models\Servico::onlyTrashed()->count();
                    } catch(\Exception $e) { $totalLixeira = 0; }
                @endphp

                <li class="nav-item nav-has-submenu {{ $lixeiraAtiva ? 'open' : '' }}">
                    <button class="nav-link nav-link-toggle {{ $lixeiraAtiva ? 'active' : '' }}"
                            onclick="toggleSubmenu(this)">
                        <span class="nav-icon"><i class="fas fa-trash-alt"></i></span>
                        <span class="nav-label">Lixeira</span>
                        @if($totalLixeira > 0)
                            <span class="nav-badge" style="background:rgba(217,112,112,0.2);color:#D97070;">
                                {{ $totalLixeira }}
                            </span>
                        @endif
                        <span class="nav-chevron nav-label"><i class="fas fa-chevron-down"></i></span>
                    </button>
                    <span class="nav-tooltip">Lixeira</span>

                    <ul class="nav-submenu">
                        <li>
                            <a href="{{ route('agendamentos.lixeira') }}"
                               class="nav-sublink {{ request()->routeIs('agendamentos.lixeira') ? 'active' : '' }}">
                                <i class="fas fa-calendar-times"></i>
                                <span>Agendamentos</span>
                                @php
                                    try { $lxAg = \App\Models\Agendamento::onlyTrashed()->count(); }
                                    catch(\Exception $e) { $lxAg = 0; }
                                @endphp
                                @if($lxAg > 0)
                                    <span class="sub-count">{{ $lxAg }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('barbeiros.lixeira') }}"
                               class="nav-sublink {{ request()->routeIs('barbeiros.lixeira') ? 'active' : '' }}">
                                <i class="fas fa-user-slash"></i>
                                <span>Barbeiros</span>
                                @php
                                    try { $lxBa = \App\Models\Barbeiro::onlyTrashed()->count(); }
                                    catch(\Exception $e) { $lxBa = 0; }
                                @endphp
                                @if($lxBa > 0)
                                    <span class="sub-count">{{ $lxBa }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('servicos.lixeira') }}"
                               class="nav-sublink {{ request()->routeIs('servicos.lixeira') ? 'active' : '' }}">
                                <i class="fas fa-scissors"></i>
                                <span>Serviços</span>
                                @php
                                    try { $lxSv = \App\Models\Servico::onlyTrashed()->count(); }
                                    catch(\Exception $e) { $lxSv = 0; }
                                @endphp
                                @if($lxSv > 0)
                                    <span class="sub-count">{{ $lxSv }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.index') }}" class="sidebar-user" 
               style="text-decoration:none;display:flex;align-items:center;gap:0.75rem;
                      padding:0.5rem;border-radius:10px;transition:background 0.2s;"
               onmouseover="this.style.background='rgba(201,168,76,0.08)';"
               onmouseout="this.style.background='transparent';">
                <div class="user-avatar">
                    @auth
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @else
                        A
                    @endauth
                </div>
                <div class="user-info">
                    <div class="user-name">
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Admin
                        @endauth
                    </div>
                    <div class="user-role">Perfil</div>
                </div>
            </a>
        </div>

    </aside>

    {{-- ══════════════ MAIN ══════════════ --}}
    <main class="main-content">

        @if(session('success'))
            <div class="alert alert-success" id="flashAlert">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" id="flashAlert">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
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

{{-- ══════════════ MODAL SOFT DELETE (global) ══════════════ --}}
<div id="softDeleteModal" style="display:none;position:fixed;inset:0;z-index:1000;
     background:rgba(0,0,0,0.82);backdrop-filter:blur(8px);
     align-items:center;justify-content:center;padding:1.5rem;">
    <div style="background:#141414;border:1px solid #262626;border-radius:18px;
                width:90%;max-width:400px;overflow:hidden;
                box-shadow:0 40px 100px rgba(0,0,0,0.8);
                animation:modalIn 0.24s cubic-bezier(.34,1.3,.64,1);">

        {{-- header --}}
        <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:1.2rem 1.5rem;border-bottom:1px solid #262626;
                    background:#1C1C1C;position:relative;">
            <span style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;
                         color:#F0EDE8;display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-exclamation-triangle" style="color:#D97070;font-size:0.85rem;"></i>
                Confirmar Exclusão
            </span>
            <button onclick="fecharModal()" style="width:30px;height:30px;border-radius:7px;
                border:1px solid #262626;background:transparent;color:#6B6560;cursor:pointer;
                display:flex;align-items:center;justify-content:center;font-size:0.78rem;
                transition:all 0.18s;"
                onmouseover="this.style.color='#C9A84C';this.style.borderColor='#8B6914';"
                onmouseout="this.style.color='#6B6560';this.style.borderColor='#262626';">
                <i class="fas fa-times"></i>
            </button>
            <div style="position:absolute;bottom:0;left:1.5rem;width:36px;height:2px;background:#C9A84C;"></div>
        </div>

        {{-- body --}}
        <div style="padding:2rem 1.5rem 1.5rem;text-align:center;
                    display:flex;flex-direction:column;align-items:center;gap:1rem;">
            <div style="width:68px;height:68px;border-radius:50%;
                        background:rgba(139,51,51,0.12);border:2px solid rgba(139,51,51,0.28);
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.6rem;color:#D97070;">
                <i class="fas fa-eye-slash"></i>
            </div>
            <p style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;
                      color:#F0EDE8;margin:0;" id="softDeleteTitle">Ocultar item?</p>
            <div style="display:inline-flex;align-items:center;gap:0.4rem;
                        background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.2);
                        color:#C9A84C;padding:0.3rem 0.85rem;border-radius:20px;
                        font-weight:600;font-size:0.85rem;">
                <i id="softDeleteIcon" class="fas fa-user"></i>
                <span id="softDeleteName">—</span>
            </div>
            <p style="font-size:0.82rem;color:#6B6560;line-height:1.65;margin:0;">
                Será movido para a <strong style="color:#F0EDE8;">lixeira</strong>
                e poderá ser restaurado depois.
            </p>
        </div>

        {{-- footer --}}
        <div style="display:flex;justify-content:space-between;gap:0.6rem;
                    padding:1rem 1.5rem;border-top:1px solid #262626;background:#1C1C1C;">
            <button type="button" onclick="fecharModal()" style="
                background:transparent;color:#9C9690;border:1px solid #262626;
                padding:0.5rem 1.1rem;border-radius:7px;font-family:'DM Sans',sans-serif;
                font-weight:500;font-size:0.85rem;cursor:pointer;
                display:inline-flex;align-items:center;gap:0.4rem;transition:all 0.2s;"
                onmouseover="this.style.borderColor='#8B6914';this.style.color='#C9A84C';"
                onmouseout="this.style.borderColor='#262626';this.style.color='#9C9690';">
                <i class="fas fa-arrow-left"></i> Cancelar
            </button>
            <form id="softDeleteForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" id="softDeleteBtn" style="
                    background:rgba(139,51,51,0.85);color:#F0EDE8;
                    border:1px solid rgba(139,51,51,0.5);padding:0.5rem 1.2rem;
                    border-radius:7px;font-family:'DM Sans',sans-serif;
                    font-weight:600;font-size:0.85rem;cursor:pointer;
                    display:inline-flex;align-items:center;gap:0.4rem;transition:background 0.2s;">
                    <i class="fas fa-eye-slash"></i> Sim, mover para lixeira
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(0.93) translateY(18px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}

/* ── Submenu da lixeira ── */
.nav-has-submenu .nav-submenu {
    display: none;
    list-style: none;
    padding: 0.25rem 0 0.25rem 2.5rem;
    margin: 0;
}
.nav-has-submenu.open .nav-submenu {
    display: block;
}
.nav-sublink {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    color: var(--text-muted, #6B6560);
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    margin-bottom: 0.1rem;
}
.nav-sublink:hover,
.nav-sublink.active {
    background: rgba(201,168,76,0.08);
    color: #C9A84C;
}
.nav-sublink i { font-size: 0.7rem; width: 14px; text-align: center; }
.nav-sublink .sub-count {
    margin-left: auto;
    font-size: 0.6rem;
    font-weight: 700;
    background: rgba(217,112,112,0.15);
    color: #D97070;
    padding: 0.1rem 0.4rem;
    border-radius: 10px;
}
.nav-link-toggle {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}
.nav-chevron {
    margin-left: auto;
    font-size: 0.6rem;
    transition: transform 0.2s;
}
.nav-has-submenu.open .nav-chevron i {
    transform: rotate(180deg);
}
/* Ocultar submenu quando sidebar recolhida */
.sidebar.collapsed .nav-submenu { display: none !important; }
</style>

{{-- ══════════════ SCRIPTS ══════════════ --}}
<script>
(function () {
    /* ── Sidebar toggle ─── */
    const KEY       = 'barberpro_sidebar';
    const container = document.getElementById('appContainer');
    const sidebar   = document.getElementById('sidebar');
    const btn       = document.getElementById('sidebarToggle');

    if (localStorage.getItem(KEY) === 'collapsed') {
        sidebar.classList.add('collapsed');
        container.classList.add('sidebar-collapsed');
    }

    btn.addEventListener('click', () => {
        const collapsed = sidebar.classList.toggle('collapsed');
        container.classList.toggle('sidebar-collapsed', collapsed);
        localStorage.setItem(KEY, collapsed ? 'collapsed' : 'expanded');
    });

    /* ── Flash auto-dismiss ─── */
    const flash = document.getElementById('flashAlert');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.4s, transform 0.4s';
            flash.style.opacity    = '0';
            flash.style.transform  = 'translateY(-6px)';
            setTimeout(() => flash.remove(), 400);
        }, 4000);
    }
})();

/* ── Submenu toggle ─── */
function toggleSubmenu(btn) {
    const li = btn.closest('.nav-has-submenu');
    li.classList.toggle('open');
}

/* ── Modal soft delete genérico ──
 *
 * Uso em qualquer view:
 *   confirmarApagar('/agendamentos/5', 'João Silva', 'Agendamento', 'fa-calendar')
 *   confirmarApagar('/barbeiros/3', 'Carlos Lima', 'Barbeiro', 'fa-user-tie')
 *   confirmarApagar('/servicos/2', 'Corte Masculino', 'Serviço', 'fa-cut')
 */
function confirmarApagar(action, nome, tipo, icon) {
    tipo  = tipo  || 'Item';
    icon  = icon  || 'fa-trash';

    document.getElementById('softDeleteTitle').textContent = 'Mover ' + tipo + ' para lixeira?';
    document.getElementById('softDeleteName').textContent  = nome;
    document.getElementById('softDeleteIcon').className    = 'fas ' + icon;
    document.getElementById('softDeleteForm').action       = action;

    const btn = document.getElementById('softDeleteBtn');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-eye-slash"></i> Sim, mover para lixeira';

    const modal = document.getElementById('softDeleteModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fecharModal() {
    document.getElementById('softDeleteModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('softDeleteModal');
    modal.addEventListener('click', e => { if (e.target === modal) fecharModal(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.style.display === 'flex') fecharModal();
    });
    document.getElementById('softDeleteForm').addEventListener('submit', function () {
        const btn = document.getElementById('softDeleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Movendo...';
    });
});
</script>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>