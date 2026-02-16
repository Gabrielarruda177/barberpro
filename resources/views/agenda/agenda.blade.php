<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbeiro;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Obter data do request ou usar hoje
        $data = $request->get('data') ? Carbon::parse($request->get('data')) : Carbon::today();
        
        // Aplicar filtros
        $query = Agendamento::with(['barbeiro', 'servico'])
                          ->whereMonth('data', $data->month)
                          ->whereYear('data', $data->year)
                          ->whereNull('deleted_at');

        if ($request->filled('barbeiro_id')) {
            $query->where('barbeiro_id', $request->barbeiro_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agendamentos = $query->get()
                            ->groupBy(function($item) {
                                return $item->data->format('Y-m-d');
                            });
        
        $agendamentosDia = $agendamentos->get($data->format('Y-m-d'), collect());
        
        // Gerar calendário do mês
        $calendario = $this->gerarCalendarioMensal($data, $agendamentos);
        
        // Calcular estatísticas
        $estatisticas = $this->calcularEstatisticas($agendamentos);
        
        // Obter barbeiros para filtros
        $barbeiros = Barbeiro::orderBy('nome')->get();
        
        return view('agenda', compact(
            'data', 
            'agendamentos', 
            'agendamentosDia',
            'calendario',
            'estatisticas',
            'barbeiros'
        ));
    }

    /**
     * Visualização de um dia específico
     */
    public function dia($data)
    {
        $data = Carbon::parse($data);
        
        $agendamentos = Agendamento::with(['barbeiro', 'servico'])
                                  ->whereDate('data', $data)
                                  ->whereNull('deleted_at')
                                  ->orderBy('horario')
                                  ->get();

        return view('agenda.dia', compact('data', 'agendamentos'));
    }


    
    /**
     * Gera o calendário mensal
     */
    private function gerarCalendarioMensal($data, $agendamentos)
    {
        $primeiroDia = $data->copy()->startOfMonth();
        $ultimoDia = $data->copy()->endOfMonth();
        
        // Adicionar dias vazios no início se necessário
        $calendario = [];
        $diaSemana = $primeiroDia->dayOfWeek;
        
        // Dias vazios antes do mês
        for ($i = 0; $i < $diaSemana; $i++) {
            $calendario[] = null;
        }
        
        // Dias do mês
        $periodo = CarbonPeriod::create($primeiroDia, $ultimoDia);
        
        foreach ($periodo as $dia) {
            $agendamentosDoDia = $agendamentos->get($dia->format('Y-m-d'), collect());  
            
            $calendario[] = [
                'data' => $dia,
                'dia' => $dia->day,
                'hoje' => $dia->isToday(),
                'fim_de_semana' => $dia->isWeekend(),
                'passado' => $dia->isPast(),
                'agendamentos' => $agendamentosDoDia,
                'total' => $agendamentosDoDia->count(),
                'tem_agendamentos' => $agendamentosDoDia->isNotEmpty(),
                'cor_status' => $this->getCorDoDia($agendamentosDoDia)
            ];
        }
        
        return $calendario;
    }

    /**
     * Calcula estatísticas do mês
     */
    private function calcularEstatisticas($agendamentos)
    {
        $todos = $agendamentos->flatten();
        
        return [
            'total' => $todos->count(),
            'concluidos' => $todos->where('status', 'concluido')->count(),
            'cancelados' => $todos->where('status', 'cancelado')->count(),
            'agendados' => $todos->where('status', 'agendado')->count(),
            'valor_total' => $todos->sum('valor'),
            'valor_medio' => $todos->avg('valor')
        ];
    }

    /**
     * Retorna a cor do dia baseada no status do último agendamento
     */
    private function getCorDoDia($agendamentos)
    {
        if ($agendamentos->isEmpty()) {
            return 'transparent';
        }

        $status = $agendamentos->last()->status;
        $cores = [
            'agendado' => '#3b82f6',
            'concluido' => '#10b981',
            'cancelado' => '#ef4444'
        ];

        return $cores[$status] ?? '#6b7280';
    }
}