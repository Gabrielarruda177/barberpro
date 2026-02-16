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

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rotas de Agendamentos
Route::prefix('agendamentos')->name('agendamentos.')->group(function () {
    Route::get('/', [AgendamentoController::class, 'index'])->name('index');
    Route::get('/create', [AgendamentoController::class, 'create'])->name('create');
    Route::post('/', [AgendamentoController::class, 'store'])->name('store');
    Route::get('/{agendamento}/edit', [AgendamentoController::class, 'edit'])->name('edit');
    Route::put('/{agendamento}', [AgendamentoController::class, 'update'])->name('update');
    Route::delete('/{agendamento}', [AgendamentoController::class, 'destroy'])->name('destroy');
    
    
    // Soft Delete
    Route::post('/{agendamento}/apagar', [AgendamentoController::class, 'softDelete'])->name('softDelete');
    Route::post('/{id}/restaurar', [AgendamentoController::class, 'restore'])->name('restore');
    Route::delete('/{id}/deletar', [AgendamentoController::class, 'forceDelete'])->name('forceDelete');
    Route::get('/lixeira', [AgendamentoController::class, 'lixeira'])->name('lixeira');
    
    // API
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('horarios');
});

// Rotas de Barbeiros
Route::resource('barbeiros', BarbeiroController::class);

// Rotas de Serviços
Route::resource('servicos', ServicoController::class);

// Agenda
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/dia/{data}', [AgendaController::class, 'dia'])->name('agenda.dia');