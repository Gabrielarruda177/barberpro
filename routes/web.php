<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\BarbeiroController;
use App\Http\Controllers\ServicoController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');

Route::resource('agendamentos', AgendamentoController::class)->except(['show']);

Route::resource('barbeiros', BarbeiroController::class)->except(['show', 'create', 'edit']);

Route::resource('servicos', ServicoController::class)->except(['show', 'create', 'edit']);

Route::patch('/servicos/{servico}/toggle-status', [ServicoController::class, 'toggleStatus'])->name('servicos.toggle-status');