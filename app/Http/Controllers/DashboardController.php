<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbeiro;
use App\Models\Servico;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoje = Carbon::today();
        $mesAtual = $hoje->month;
        $anoAtual = $hoje->year;

        // ── STAT CARDS ────────────────────────────────────────────────────
        $agendamentosHoje   = Agendamento::whereDate('data', $hoje)->count();
        $concluidosHoje     = Agendamento::whereDate('data', $hoje)->where('status', 'concluido')->count();
        $canceladosHoje     = Agendamento::whereDate('data', $hoje)->where('status', 'cancelado')->count();
        $faturamentoHoje    = Agendamento::whereDate('data', $hoje)->where('status', 'concluido')->sum('valor');
        $agendamentosMes    = Agendamento::whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->count();
        $faturamentoMes     = Agendamento::whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'concluido')->sum('valor');

        // ── AGENDAMENTOS DE HOJE (lista) ───────────────────────────────────
        $agendamentosDeHoje = Agendamento::with(['barbeiro', 'servico'])
            ->whereDate('data', $hoje)
            ->orderBy('horario')
            ->get();

        // ── STATUS DO MÊS ─────────────────────────────────────────────────
        $statusMes = [
            'agendado'  => Agendamento::whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'agendado')->count(),
            'concluido' => Agendamento::whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'concluido')->count(),
            'cancelado' => Agendamento::whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'cancelado')->count(),
        ];

        // ── TAXA DE CONCLUSÃO ─────────────────────────────────────────────
        $totalMes       = $statusMes['agendado'] + $statusMes['concluido'] + $statusMes['cancelado'];
        $taxaConclusao  = $totalMes > 0 ? round(($statusMes['concluido'] / $totalMes) * 100, 1) : 0;

        // ── FATURAMENTO DIÁRIO (últimos 30 dias) ──────────────────────────
        $faturamentoDiario = [];
        for ($i = 29; $i >= 0; $i--) {
            $dia = Carbon::today()->subDays($i);
            $chave = $dia->format('Y-m-d');
            $faturamentoDiario[$chave] = (float) Agendamento::whereDate('data', $dia)
                ->where('status', 'concluido')
                ->sum('valor');
        }

        // ── AGENDAMENTOS POR HORA ─────────────────────────────────────────
        // Conta agendamentos do mês atual agrupados por hora
        $porHoraRaw = Agendamento::whereMonth('data', $mesAtual)
            ->whereYear('data', $anoAtual)
            ->selectRaw("HOUR(horario) as hora, COUNT(*) as total")
            ->groupBy('hora')
            ->orderBy('hora')
            ->pluck('total', 'hora')
            ->toArray();

        // Garante todas as horas de 7 a 20
        $porHora = [];
        for ($h = 7; $h <= 20; $h++) {
            $porHora[$h] = $porHoraRaw[$h] ?? 0;
        }

        // ── POR DIA DA SEMANA ─────────────────────────────────────────────
        $diasNomes = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        $porDiaSemanaRaw = Agendamento::whereMonth('data', $mesAtual)
            ->whereYear('data', $anoAtual)
            ->selectRaw("DAYOFWEEK(data) as dia_semana, COUNT(*) as total")
            ->groupBy('dia_semana')
            ->pluck('total', 'dia_semana')
            ->toArray();

        $porDiaSemana = [];
        foreach ($diasNomes as $idx => $nome) {
            // MySQL: DAYOFWEEK retorna 1=Dom, 2=Seg, ...
            $porDiaSemana[$nome] = $porDiaSemanaRaw[$idx + 1] ?? 0;
        }

        // ── PERFORMANCE POR BARBEIRO ──────────────────────────────────────
        $porBarbeiro = Barbeiro::withCount([
                'agendamentos as total' => function ($q) use ($mesAtual, $anoAtual) {
                    $q->whereMonth('data', $mesAtual)->whereYear('data', $anoAtual);
                }
            ])
            ->withSum([
                'agendamentos as valor' => function ($q) use ($mesAtual, $anoAtual) {
                    $q->whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'concluido');
                }
            ], 'valor')
            ->orderByDesc('total')
            ->get()
            ->map(fn($b) => [
                'nome'  => $b->nome,
                'total' => (int)   ($b->total ?? 0),
                'valor' => (float) ($b->valor ?? 0),
            ]);

        // ── TOP SERVIÇOS ──────────────────────────────────────────────────
        $topServicos = Servico::withCount([
                'agendamentos as total' => function ($q) use ($mesAtual, $anoAtual) {
                    $q->whereMonth('data', $mesAtual)->whereYear('data', $anoAtual);
                }
            ])
            ->withSum([
                'agendamentos as valor' => function ($q) use ($mesAtual, $anoAtual) {
                    $q->whereMonth('data', $mesAtual)->whereYear('data', $anoAtual)->where('status', 'concluido');
                }
            ], 'valor')
            ->orderByDesc('total')
            ->take(6)
            ->get()
            ->map(fn($s) => [
                'nome'  => $s->nome,
                'total' => (int)   ($s->total ?? 0),
                'valor' => (float) ($s->valor ?? 0),
            ]);

        return view('dashboard', compact(
            'agendamentosHoje',
            'concluidosHoje',
            'canceladosHoje',
            'faturamentoHoje',
            'agendamentosMes',
            'faturamentoMes',
            'agendamentosDeHoje',
            'statusMes',
            'taxaConclusao',
            'faturamentoDiario',
            'porHora',
            'porDiaSemana',
            'porBarbeiro',
            'topServicos'
        ));
    }
}