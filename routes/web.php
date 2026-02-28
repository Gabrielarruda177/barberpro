<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ProfileController;

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
// PERFIL
// --------------------------------------------------
Route::prefix('perfil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/remover-foto', [ProfileController::class, 'removePhoto'])->name('remove-photo');
});


// --------------------------------------------------
// AGENDAMENTOS (COM LIXEIRA)
// Rotas estáticas ANTES das rotas com parâmetro {agendamento}
// --------------------------------------------------
Route::prefix('agendamentos')->name('agendamentos.')->group(function () {

    // Estáticas — devem vir PRIMEIRO
    Route::get('/lixeira', [AgendamentoController::class, 'lixeira'])->name('lixeira');
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])->name('horarios');
    Route::get('/create', [AgendamentoController::class, 'create'])->name('create');
    Route::get('/', [AgendamentoController::class, 'index'])->name('index');
    Route::post('/', [AgendamentoController::class, 'store'])->name('store');

    // Com parâmetro — devem vir DEPOIS
    Route::get('/{agendamento}/edit', [AgendamentoController::class, 'edit'])->name('edit');
    Route::put('/{agendamento}', [AgendamentoController::class, 'update'])->name('update');
    Route::delete('/{agendamento}', [AgendamentoController::class, 'destroy'])->name('destroy');

    // Lixeira com parâmetro
    Route::post('/{id}/restaurar', [AgendamentoController::class, 'restore'])->name('restore');
    Route::delete('/{id}/deletar', [AgendamentoController::class, 'forceDelete'])->name('forceDelete');
});


// --------------------------------------------------
// SERVIÇOS (COM LIXEIRA)
// Rotas estáticas ANTES das rotas com parâmetro {servico}
// --------------------------------------------------
Route::prefix('servicos')->name('servicos.')->group(function () {

    // Estáticas — devem vir PRIMEIRO
    Route::get('/lixeira', [ServicoController::class, 'lixeira'])->name('lixeira');
    Route::get('/', [ServicoController::class, 'index'])->name('index');
    Route::post('/', [ServicoController::class, 'store'])->name('store');

    // Com parâmetro — devem vir DEPOIS
    Route::get('/{servico}/json', [ServicoController::class, 'getJson'])->name('json');
    Route::get('/{servico}/edit', [ServicoController::class, 'edit'])->name('edit');
    Route::put('/{servico}', [ServicoController::class, 'update'])->name('update');
    Route::delete('/{servico}', [ServicoController::class, 'destroy'])->name('destroy');
    Route::patch('/{servico}/toggle-status', [ServicoController::class, 'toggleStatus'])->name('toggle-status');

    // Lixeira com parâmetro
    Route::post('/{id}/restaurar', [ServicoController::class, 'restore'])->name('restore');
    Route::delete('/{id}/deletar', [ServicoController::class, 'forceDelete'])->name('forceDelete');
});


// --------------------------------------------------
// BARBEIROS (COM LIXEIRA)
// Rotas estáticas ANTES das rotas com parâmetro {barbeiro}
// --------------------------------------------------
Route::prefix('barbeiros')->name('barbeiros.')->group(function () {

    // Estáticas — devem vir PRIMEIRO
    Route::get('/lixeira', [BarbeiroController::class, 'lixeira'])->name('lixeira');
    Route::get('/', [BarbeiroController::class, 'index'])->name('index');
    Route::post('/', [BarbeiroController::class, 'store'])->name('store');

    // Com parâmetro — devem vir DEPOIS
    Route::get('/{barbeiro}/json', [BarbeiroController::class, 'getJson'])->name('json');
    Route::get('/{barbeiro}/edit', [BarbeiroController::class, 'edit'])->name('edit');
    Route::put('/{barbeiro}', [BarbeiroController::class, 'update'])->name('update');
    Route::delete('/{barbeiro}', [BarbeiroController::class, 'destroy'])->name('destroy');
    Route::patch('/{barbeiro}/toggle-status', [BarbeiroController::class, 'toggleStatus'])->name('toggle-status');

    // Lixeira com parâmetro
    Route::post('/{id}/restaurar', [BarbeiroController::class, 'restore'])->name('restore');
    Route::delete('/{id}/deletar', [BarbeiroController::class, 'forceDelete'])->name('forceDelete');
});


// --------------------------------------------------
// AGENDA (VISÃO GERAL)
// --------------------------------------------------
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/dia/{data}', [AgendaController::class, 'dia'])->name('agenda.dia');
