<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbeiro;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AgendaController extends Controller
{
    /**
     * Vista principal do calendário mensal.
     */
    public function index(Request $request)
    {
        // 1. Parse seguro com fallback para o mês atual
        $data = Carbon::parse($request->get('data', now()->format('Y-m')))->startOfMonth();

        $barbeiroId = $request->integer('barbeiro_id') ?: null;
        $status     = $request->get('status');

        // 2. Uma única query otimizada com eager loading e select limitado
        $agendamentos = Agendamento::query()
            ->whereMonth('data', $data->month)
            ->whereYear('data', $data->year)
            ->when($barbeiroId, fn($q) => $q->where('barbeiro_id', $barbeiroId))
            ->when($status,     fn($q) => $q->where('status', $status))
            ->with([
                'barbeiro:id,nome',
                'servico:id,nome,preco',
            ])
            ->orderBy('data')
            ->orderBy('horario')
            ->get();

        // 3. Agrupar por data UMA VEZ — evita N pesquisas na collection dentro do foreach
        $agendamentosPorData = $agendamentos->groupBy(
            fn($ag) => Carbon::parse($ag->data)->format('Y-m-d')
        );

        // 4. Construir o calendário de forma mais eficiente
        $calendario = $this->buildCalendario($data, $agendamentosPorData);

        // 5. Agendamentos do dia selecionado (ou hoje, se dentro do mês)
        $diaAtivo = $request->get('dia')
            ? $data->copy()->setDay((int) $request->get('dia'))
            : $data->copy(); // começo do mês; view mostra lista do dia clicado

        $agendamentosDoDia = ($agendamentosPorData[$diaAtivo->format('Y-m-d')] ?? collect())->values();

        // 6. Stats calculadas diretamente — sem percorrer a collection múltiplas vezes
        $stats = $this->calcularEstatisticas($agendamentos);

        // 7. Barbeiros em cache para não bater no banco em toda request de agenda
        $barbeiros = cache()->remember('barbeiros_lista_agenda', 60, fn() =>
            Barbeiro::select('id', 'nome')->orderBy('nome')->get()
        );

        return view('agendamentos.agenda', compact(
            'data',
            'agendamentos',
            'agendamentosDoDia',
            'agendamentosPorData',
            'calendario',
            'stats',
            'barbeiros',
            'diaAtivo',
        ));
    }

    /**
     * Retorna agendamentos de um dia específico como JSON (endpoint AJAX).
     */
    public function agendamentosDiaAjax(Request $request, string $data)
    {
        $date = Carbon::parse($data)->startOfDay();

        $agendamentos = Agendamento::whereDate('data', $date)
            ->with(['barbeiro:id,nome', 'servico:id,nome,preco'])
            ->orderBy('horario')
            ->get();

        return response()->json([
            'data'         => $date->translatedFormat('d \d\e F \d\e Y'),
            'total'        => $agendamentos->count(),
            'agendamentos' => $agendamentos->map(fn($ag) => [
                'id'           => $ag->id,
                'horario'      => Carbon::parse($ag->horario)->format('H:i'),
                'nome_cliente' => $ag->nome_cliente,
                'status'       => $ag->status,
                'barbeiro'     => $ag->barbeiro?->nome,
                'servico'      => $ag->servico?->nome,
                'preco'        => $ag->servico?->preco
                    ? 'R$ ' . number_format($ag->servico->preco, 2, ',', '.')
                    : null,
                'edit_url'     => route('agendamentos.edit', $ag->id),
            ]),
        ]);
    }

    /**
     * Vista de detalhe de um dia específico.
     */
    public function dia(string $data)
    {
        $data = Carbon::parse($data)->startOfDay();

        $agendamentos = Agendamento::whereDate('data', $data)
            ->with(['barbeiro:id,nome', 'servico:id,nome,preco'])
            ->orderBy('horario')
            ->get();

        return view('agendamentos.dia', compact('agendamentos', 'data'));
    }

    // ──────────────────────────────────────────────────────────
    //  HELPERS PRIVADOS
    // ──────────────────────────────────────────────────────────

    /**
     * Constrói o array do calendário com os dias do mês.
     * Recebe os agendamentos já agrupados por data para evitar
     * pesquisas repetidas dentro do loop.
     */
    private function buildCalendario(Carbon $data, \Illuminate\Support\Collection $agendamentosPorData): array
    {
        $start = $data->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $end   = $data->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $calendario = [];

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $key              = $date->format('Y-m-d');
            $agsDoDia         = $agendamentosPorData[$key] ?? collect();
            $doMesAtual       = $date->month === $data->month;

            $calendario[] = [
                'data'             => $date->copy(),
                'dia'              => $date->day,
                'hoje'             => $date->isToday(),
                'fim_de_semana'    => $date->isWeekend(),
                'do_mes_atual'     => $doMesAtual,
                'tem_agendamentos' => $agsDoDia->isNotEmpty() && $doMesAtual,
                'total'            => $agsDoDia->count(),
                'agendamentos'     => $agsDoDia,
                // Contagens por status — úteis para colorir a célula ou exibir badges
                'n_agendado'       => $agsDoDia->where('status', 'agendado')->count(),
                'n_concluido'      => $agsDoDia->where('status', 'concluido')->count(),
                'n_cancelado'      => $agsDoDia->where('status', 'cancelado')->count(),
            ];
        }

        return $calendario;
    }

    /**
     * Calcula estatísticas mensais numa única passagem pela collection
     * em vez de chamar ->where() várias vezes (N passes → 1 pass).
     */
    private function calcularEstatisticas(\Illuminate\Support\Collection $agendamentos): array
    {
        $stats = [
            'total'      => 0,
            'agendados'  => 0,
            'concluidos' => 0,
            'cancelados' => 0,
            'valor_total'=> 0.0,
        ];

        foreach ($agendamentos as $ag) {
            $stats['total']++;
            match ($ag->status) {
                'agendado'  => $stats['agendados']++,
                'concluido' => $stats['concluidos']++,
                'cancelado' => $stats['cancelados']++,
                default     => null,
            };
            if ($ag->status === 'concluido') {
                $stats['valor_total'] += (float) ($ag->servico?->preco ?? $ag->valor ?? 0);
            }
        }

        return $stats;
    }
}