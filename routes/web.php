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
*/

// --------------------------------------------------
// DASHBOARD
// --------------------------------------------------
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


// --------------------------------------------------
// AGENDAMENTOS (COM LIXEIRA)
// --------------------------------------------------
Route::prefix('agendamentos')->name('agendamentos.')->group(function () {

    Route::get('/', [AgendamentoController::class, 'index'])->name('index');
    Route::get('/create', [AgendamentoController::class, 'create'])->name('create');
    Route::get('/{agendamento}/edit', [AgendamentoController::class, 'edit'])->name('edit');

    Route::post('/', [AgendamentoController::class, 'store'])->name('store');
    Route::put('/{agendamento}', [AgendamentoController::class, 'update'])->name('update');

    // Soft Delete
    Route::delete('/{agendamento}', [AgendamentoController::class, 'destroy'])->name('destroy');

    // Lixeira
    Route::get('/lixeira', [AgendamentoController::class, 'lixeira'])->name('lixeira');
    Route::post('/{agendamento}/restaurar', [AgendamentoController::class, 'restore'])->name('restore');
    Route::delete('/{agendamento}/deletar', [AgendamentoController::class, 'forceDelete'])->name('forceDelete');

    // AJAX Horários
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('horarios');
});


// --------------------------------------------------
// SERVIÇOS (COM LIXEIRA)
// --------------------------------------------------
Route::prefix('servicos')->name('servicos.')->group(function () {

    Route::get('/', [ServicoController::class, 'index'])->name('index');
    Route::post('/', [ServicoController::class, 'store'])->name('store');
    Route::get('/{servico}/edit', [ServicoController::class, 'edit'])->name('edit');

    Route::put('/{servico}', [ServicoController::class, 'update'])->name('update');
    Route::delete('/{servico}', [ServicoController::class, 'destroy'])->name('destroy');

    Route::get('/{servico}/json', [ServicoController::class, 'getJson'])->name('json');
    Route::patch('/{servico}/toggle-status', [ServicoController::class, 'toggleStatus'])->name('toggle-status');

    // Lixeira
    Route::get('/lixeira', [ServicoController::class, 'lixeira'])->name('lixeira');
    Route::post('/{servico}/restaurar', [ServicoController::class, 'restore'])->name('restore');
    Route::delete('/{servico}/deletar', [ServicoController::class, 'forceDelete'])->name('forceDelete');
});


// --------------------------------------------------
// BARBEIROS (COM LIXEIRA)
// --------------------------------------------------
Route::prefix('barbeiros')->name('barbeiros.')->group(function () {

    Route::get('/', [BarbeiroController::class, 'index'])->name('index');
    Route::post('/', [BarbeiroController::class, 'store'])->name('store');
    Route::get('/{barbeiro}/edit', [BarbeiroController::class, 'edit'])->name('edit');

    Route::put('/{barbeiro}', [BarbeiroController::class, 'update'])->name('update');
    Route::delete('/{barbeiro}', [BarbeiroController::class, 'destroy'])->name('destroy');

    Route::get('/{barbeiro}/json', [BarbeiroController::class, 'getJson'])->name('json');
    Route::patch('/{barbeiro}/toggle-status', [BarbeiroController::class, 'toggleStatus'])->name('toggle-status');

    // Lixeira
    Route::get('/lixeira', [BarbeiroController::class, 'lixeira'])->name('lixeira');
    Route::post('/{barbeiro}/restaurar', [BarbeiroController::class, 'restore'])->name('restore');
    Route::delete('/{barbeiro}/deletar', [BarbeiroController::class, 'forceDelete'])->name('forceDelete');
});


// --------------------------------------------------
// AGENDA (VISÃO GERAL)
// --------------------------------------------------
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/dia/{data}', [AgendaController::class, 'dia'])->name('agenda.dia');
