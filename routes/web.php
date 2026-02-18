<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas web para sua aplicação. Elas
| são carregadas pelo RouteServiceProvider dentro de um grupo que
| contém o middleware "web". Ai gogo!
|
*/

// --------------------------------------------------
// ROTA PRINCIPAL (DASHBOARD)
// --------------------------------------------------
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


// --------------------------------------------------
// ROTAS DE AGENDAMENTOS (COM SOFT DELETE PADRONIZADO)
// --------------------------------------------------
Route::prefix('agendamentos')->name('agendamentos.')->group(function () {
    // Listagem e Formulários
    Route::get('/', [AgendamentoController::class, 'index'])->name('index');
    Route::get('/create', [AgendamentoController::class, 'create'])->name('create');
    Route::get('/{agendamento}/edit', [AgendamentoController::class, 'edit'])->name('edit');
    
    // Ações (CRUD)
    Route::post('/', [AgendamentoController::class, 'store'])->name('store');
    
    // --- MUDANÇA PRINCIPAL ---
    // A rota DELETE padrão agora será responsável pelo Soft Delete.
    // O método no controller deve ser `destroy()` e deve chamar `$agendamento->delete();`.
    Route::delete('/{agendamento}', [AgendamentoController::class, 'destroy'])->name('destroy');
    
    // --- ROTAS DA LIXEIRA (MANTIDAS) ---
    // Rota para visualizar a lixeira
    Route::get('/lixeira', [AgendamentoController::class, 'lixeira'])->name('lixeira');
    
    // Rota para restaurar da lixeira
    Route::post('/{agendamento}/restaurar', [AgendamentoController::class, 'restore'])->name('restore');
    
    // Rota para excluir permanentemente da lixeira
    // O método no controller deve ser `forceDelete()` e deve chamar `$agendamento->forceDelete();`.
    Route::delete('/{agendamento}/deletar', [AgendamentoController::class, 'forceDelete'])->name('forceDelete');

    // API para buscar horários disponíveis (usada via AJAX/JavaScript)
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('horarios');
});


// --------------------------------------------------
// ROTAS DE BARBEIROS
// --------------------------------------------------
Route::prefix('barbeiros')->name('barbeiros.')->group(function () {
    // Rotas do Resource Controller (CRUD)
    Route::get('/', [BarbeiroController::class, 'index'])->name('index');
    Route::post('/', [BarbeiroController::class, 'store'])->name('store');
    Route::get('/{barbeiro}/edit', [BarbeiroController::class, 'edit'])->name('edit');
    Route::put('/{barbeiro}', [BarbeiroController::class, 'update'])->name('update');
    Route::delete('/{barbeiro}', [BarbeiroController::class, 'destroy'])->name('destroy');

    // Rota para buscar os dados de um barbeiro em JSON (para o modal de edição)
    Route::get('/{barbeiro}/json', [BarbeiroController::class, 'getJson'])->name('json');

    // Rota para alternar o status (ativo/inativo) do barbeiro
    Route::patch('/{barbeiro}/toggle-status', [BarbeiroController::class, 'toggleStatus'])->name('toggle-status');
});


// --------------------------------------------------
// ROTAS DE SERVIÇOS
// --------------------------------------------------
Route::prefix('servicos')->name('servicos.')->group(function () {
    // Rotas do Resource Controller (CRUD)
    Route::get('/', [ServicoController::class, 'index'])->name('index');
    Route::post('/', [ServicoController::class, 'store'])->name('store');
    Route::get('/{servico}/edit', [ServicoController::class, 'edit'])->name('edit');
    Route::put('/{servico}', [ServicoController::class, 'update'])->name('update');
    Route::delete('/{servico}', [ServicoController::class, 'destroy'])->name('destroy');

    // Rota para buscar os dados de um serviço em JSON (para o modal de edição)
    Route::get('/{servico}/json', [ServicoController::class, 'getJson'])->name('json');

    // Rota para alternar o status (ativo/inativo) do serviço
    Route::patch('/{servico}/toggle-status', [ServicoController::class, 'toggleStatus'])->name('toggle-status');
});


// --------------------------------------------------
// ROTAS DA AGENDA (VISÃO GERAL)
// --------------------------------------------------
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/dia/{data}', [AgendaController::class, 'dia'])->name('agenda.dia');
