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
        $data = $request->get('data', Carbon::today());
        $data = Carbon::parse($data);
        
        $barbeiroId = $request->get('barbeiro_id');
        $status = $request->get('status');

        $query = Agendamento::whereMonth('data', $data->month)
                            ->whereYear('data', $data->year)
                            ->with(['barbeiro', 'servico']);

        if ($barbeiroId) {
            $query->where('barbeiro_id', $barbeiroId);
        }
        
        if ($status) {
            $query->where('status', $status);
        }

        $agendamentos = $query->orderBy('data')->orderBy('horario')->get();
        
        $calendario = [];
        $startOfMonth = $data->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfMonth = $data->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
        
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);
        
        foreach ($period as $date) {
            $agendamentosDoDia = $agendamentos->where('data', $date->format('Y-m-d'));
            
            $calendario[] = [
                'data' => $date,
                'dia' => $date->day,
                'hoje' => $date->isToday(),
                'fim_de_semana' => $date->isWeekend(),
                'tem_agendamentos' => $agendamentosDoDia->isNotEmpty(),
                'total' => $agendamentosDoDia->count(),
                'agendamentos' => $agendamentosDoDia,
            ];
        }

        $agendamentosDoDia = $agendamentos->where('data', $data->format('Y-m-d'))->values();
        $stats = $this->calcularEstatisticas($agendamentos);
        $barbeiros = Barbeiro::orderBy('nome')->get();
        
        return view('agendamentos.agenda', compact(
            'data', 'agendamentos', 'agendamentosDoDia', 
            'calendario', 'stats', 'barbeiros'
        ));
    }

    // NOVO: Método AJAX para modal
    public function agendamentosDiaAjax(Request $request, $data)
    {
        $data = Carbon::parse($data);
        $agendamentos = Agendamento::whereDate('data', $data)
                                  ->with(['barbeiro', 'servico'])
                                  ->orderBy('horario')
                                  ->get();
        
        return response()->json([
            'data' => $data->translatedFormat('d \d\e F \d\e Y'),
            'total' => $agendamentos->count(),
            'agendamentos' => $agendamentos
        ]);
    }

    public function dia($data)
    {
        $data = Carbon::parse($data);
        $agendamentos = Agendamento::whereDate('data', $data)
                                  ->with(['barbeiro', 'servico'])
                                  ->orderBy('horario')
                                  ->get();
        return view('agendamentos.dia', compact('agendamentos', 'data'));
    }

    private function calcularEstatisticas($agendamentos)
    {
        return [
            'total' => $agendamentos->count(),
            'agendados' => $agendamentos->where('status', 'agendado')->count(),
            'concluidos' => $agendamentos->where('status', 'concluido')->count(),
            'cancelados' => $agendamentos->where('status', 'cancelado')->count(),
            'valor_total' => $agendamentos->where('status', 'concluido')->sum('valor'),
        ];
    }
}
