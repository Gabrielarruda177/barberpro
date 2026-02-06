<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;
        
        $agendamentosHoje = Agendamento::whereDate('data', $hoje)->count();
        $concluidosHoje = Agendamento::whereDate('data', $hoje)
                                     ->where('status', 'concluido')
                                     ->count();
        $faturamentoHoje = Agendamento::whereDate('data', $hoje)
                                      ->where('status', 'concluido')
                                      ->sum('valor');
        $agendamentosMes = Agendamento::whereMonth('data', $mesAtual)
                                      ->whereYear('data', $anoAtual)
                                      ->count();
        
        $agendamentosDeHoje = Agendamento::with(['barbeiro', 'servico'])
                                        ->whereDate('data', $hoje)
                                        ->orderBy('horario')
                                        ->get();
        
        return view('dashboard', compact(
            'agendamentosHoje',
            'concluidosHoje',
            'faturamentoHoje',
            'agendamentosMes',
            'agendamentosDeHoje'
        ));
    }
}