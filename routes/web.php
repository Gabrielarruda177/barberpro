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
// ROTAS DE AGENDAMENTOS (COM SOFT DELETE)
// --------------------------------------------------
Route::prefix('agendamentos')->name('agendamentos.')->group(function () {
    // Listagem e Formulários
    Route::get('/', [AgendamentoController::class, 'index'])->name('index');
    Route::get('/create', [AgendamentoController::class, 'create'])->name('create');
    Route::get('/{agendamento}/edit', [AgendamentoController::class, 'edit'])->name('edit');
    
    // Ações (CRUD)
    Route::post('/', [AgendamentoController::class, 'store'])->name('store');
    Route::put('/{agendamento}', [AgendamentoController::class, 'update'])->name('update');
    
    // Exclusão Permanente (Método destroy para compatibilidade)
    // NOTA: O ideal é usar apenas soft delete, mas a rota é mantida se o método existir.
    Route::delete('/{agendamento}', [AgendamentoController::class, 'destroy'])->name('destroy');
    
    // --- ROTAS DE SOFT DELETE ---
    // Rota para "apagar" (enviar para a lixeira)
    Route::post('/{agendamento}/apagar', [AgendamentoController::class, 'softDelete'])->name('softDelete');
    
    // Rota para visualizar a lixeira
    Route::get('/lixeira', [AgendamentoController::class, 'lixeira'])->name('lixeira');
    
    // Rota para restaurar da lixeira
    // MELHORIA: Usar {agendamento} para consistência e Route Model Binding.
    Route::post('/{agendamento}/restaurar', [AgendamentoController::class, 'restore'])->name('restore');
    
    // Rota para excluir permanentemente da lixeira
    // MELHORIA: Usar {agendamento} para consistência.
    Route::delete('/{agendamento}/deletar', [AgendamentoController::class, 'forceDelete'])->name('forceDelete');

    // API para buscar horários disponíveis (usada via AJAX/JavaScript)
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('horarios');
});


// --------------------------------------------------
// ROTAS DE RECURSOS (BARBEIROS E SERVIÇOS)
// --------------------------------------------------
// Route::resource cria automaticamente as rotas para index, create, store, show, edit, update, destroy
Route::resource('barbeiros', BarbeiroController::class);
Route::resource('servicos', ServicoController::class);


// --------------------------------------------------
// ROTAS DA AGENDA (VISÃO GERAL)
// --------------------------------------------------
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/dia/{data}', [AgendaController::class, 'dia'])->name('agenda.dia');
