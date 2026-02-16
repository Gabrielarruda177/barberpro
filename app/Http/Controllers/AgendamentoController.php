<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbeiro;
use App\Models\Servico;
use Carbon\Carbon;

class AgendamentoController extends Controller
{
  public function index(Request $request)
    {
        // --- Parâmetros da Requisição ---
        $viewMode = $request->get('view', 'list'); // Padrão é 'list'
        $selectedDate = $request->get('selected_date') 
            ? Carbon::parse($request->get('selected_date')) 
            : Carbon::today(); // Data selecionada ou hoje

        // Prepara a data base para o calendário e estatísticas (primeiro dia do mês da data selecionada)
        $data = $selectedDate->copy()->startOfMonth();

        // --- Filtros ---
        $barbeiros = Barbeiro::all();
        $barbeiroId = $request->get('barbeiro_id');
        $status = $request->get('status');

        // --- Estatísticas (sempre do mês da data selecionada) ---
        $estatisticasQuery = Agendamento::whereMonth('data', $data->month)
                                       ->whereYear('data', $data->year);
        
        $estatisticas = [
            'total' => (clone $estatisticasQuery)->count(),
            'agendados' => (clone $estatisticasQuery)->where('status', 'agendado')->count(),
            'concluidos' => (clone $estatisticasQuery)->where('status', 'concluido')->count(),
            'cancelados' => (clone $estatisticasQuery)->where('status', 'cancelado')->count(),
            'valor_total' => (clone $estatisticasQuery)->where('status', 'concluido')->sum('valor'),
        ];

        // --- Dados para a View de Lista ---
        $agendamentosQuery = Agendamento::with(['barbeiro', 'servico'])
                                       ->whereNull('deleted_at');

        if ($barbeiroId) {
            $agendamentosQuery->where('barbeiro_id', $barbeiroId);
        }
        if ($status) {
            $agendamentosQuery->where('status', $status);
        }
        
        $agendamentos = $agendamentosQuery->orderBy('data', 'asc')
                                          ->orderBy('horario', 'asc')
                                          ->paginate(15);

        // --- Dados para a View de Calendário ---
        $calendario = [];
        $agendamentosDia = collect(); // Coleção vazia por padrão

        if ($viewMode === 'calendar') {
            // 1. Buscar todos os agendamentos do mês
            $agendamentosDoMes = Agendamento::whereMonth('data', $data->month)
                                            ->whereYear('data', $data->year)
                                            ->with(['barbeiro', 'servico'])
                                            ->get();

            // 2. Agrupar por dia
            $agendamentosPorDia = $agendamentosDoMes->groupBy(function($agendamento) {
                return $agendamento->data->format('Y-m-d');
            });

            // 3. Construir o array do calendário
            for ($i = 0; $i < $data->dayOfWeek; $i++) {
                $calendario[] = null; // Dias vazios antes do mês começar
            }

            for ($dia = 1; $dia <= $data->daysInMonth; $dia++) {
                $dataAtual = $data->copy()->day($dia);
                $dataString = $dataAtual->format('Y-m-d');
                $agendamentosDoDia = $agendamentosPorDia->get($dataString, collect());

                $calendario[] = [
                    'dia' => $dia,
                    'data' => $dataAtual,
                    'hoje' => $dataAtual->isSameDay(Carbon::today()),
                    'fim_de_semana' => $dataAtual->isWeekend(),
                    'passado' => $dataAtual->isPast(),
                    'tem_agendamentos' => $agendamentosDoDia->isNotEmpty(),
                    'total_agendamentos' => $agendamentosDoDia->count(),
                    'agendamentos' => $agendamentosDoDia,
                ];
            }

            // 4. Obter agendamentos para o dia selecionado (para a lista na parte inferior)
            $agendamentosDia = $agendamentosPorDia->get($selectedDate->format('Y-m-d'), collect());
        }

        // --- Retorna a view com todos os dados ---
        // ATENÇÃO: A LINHA ABAIXO É A MAIS IMPORTANTE. ELA DEVE CONTER 'viewMode'.
        return view('agendamentos.index', compact(
            'viewMode',           // <-- ESTA LINHA É ESSENCIAL
            'selectedDate',
            'data',
            'barbeiros',
            'barbeiroId',
            'status',
            'estatisticas',
            'agendamentos',
            'calendario',
            'agendamentosDia'
        ));
    }
    
    public function create()
    {
        $barbeiros = Barbeiro::all();
        $servicos = Servico::where('ativo', true)->get();
        
        return view('agendamentos.create', compact('barbeiros', 'servicos'));
    }
    
  
    public function store(Request $request)
    {
        $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'telefone_cliente' => 'required|string|max:20',
            'barbeiro_id' => 'required|exists:barbeiros,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date|after_or_equal:today',
            'horario' => 'required',
            'observacoes' => 'nullable|string'
        ]);
        
        // Verificar se o horário está disponível
        $existeAgendamento = Agendamento::where('data', $request->data)
                                      ->where('horario', $request->horario)
                                      ->where('barbeiro_id', $request->barbeiro_id)
                                      ->whereNull('deleted_at')
                                      ->exists();

        if ($existeAgendamento) {
            return back()
                ->withInput()
                ->withErrors(['horario' => 'Este horário já está ocupado para o barbeiro selecionado.']);
        }
        
        $servico = Servico::findOrFail($request->servico_id);
        
        Agendamento::create([
            'nome_cliente' => $request->nome_cliente,
            'telefone_cliente' => $request->telefone_cliente,
            'barbeiro_id' => $request->barbeiro_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
            'horario' => $request->horario,
            'observacoes' => $request->observacoes,
            'valor' => $servico->preco
        ]);
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento criado com sucesso!');
    }

    public function edit(Agendamento $agendamento)
    {
        $barbeiros = Barbeiro::all();
        $servicos = Servico::where('ativo', true)->get();
        
        return view('agendamentos.edit', compact('agendamento', 'barbeiros', 'servicos'));
    }
    
 
    public function update(Request $request, Agendamento $agendamento)
    {
        $request->validate([
            'nome_cliente' => 'required|string|max:255',
            'telefone_cliente' => 'required|string|max:20',
            'barbeiro_id' => 'required|exists:barbeiros,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
            'horario' => 'required',
            'observacoes' => 'nullable|string',
            'status' => 'required|in:agendado,concluido,cancelado'
        ]);

        // Verificar conflito de horário (exceto com o próprio agendamento)
        $existeConflito = Agendamento::where('data', $request->data)
                                    ->where('horario', $request->horario)
                                    ->where('barbeiro_id', $request->barbeiro_id)
                                    ->where('id', '!=', $agendamento->id)
                                    ->whereNull('deleted_at')
                                    ->exists();

        if ($existeConflito) {
            return back()
                ->withInput()
                ->withErrors(['horario' => 'Este horário já está ocupado para o barbeiro selecionado.']);
        }
        
        $servico = Servico::findOrFail($request->servico_id);
        
        $agendamento->update([
            'nome_cliente' => $request->nome_cliente,
            'telefone_cliente' => $request->telefone_cliente,
            'barbeiro_id' => $request->barbeiro_id,
            'servico_id' => $request->servico_id,
            'data' => $request->data,
            'horario' => $request->horario,
            'observacoes' => $request->observacoes,
            'status' => $request->status,
            'valor' => $servico->preco
        ]);
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento atualizado com sucesso!');
    }
    
 
    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento excluído com sucesso!');
    }

    // ===== MÉTODOS DE SOFT DELETE =====

    /**
     * Soft Delete - Apaga o agendamento (não exclui permanentemente)
     */
    public function softDelete(Agendamento $agendamento)
    {
        $agendamento->delete(); // Soft delete
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento apagado com sucesso!');
    }

    /**
     * Restaura um agendamento apagado
     */
    public function restore($id)
    {
        $agendamento = Agendamento::withTrashed()->findOrFail($id);
        $agendamento->restore();
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento restaurado com sucesso!');
    }

    /**
     * Exclui permanentemente o agendamento
     */
    public function forceDelete($id)
    {
        $agendamento = Agendamento::withTrashed()->findOrFail($id);
        $agendamento->forceDelete();
        
        return redirect()->route('agendamentos.index')->with('success', 'Agendamento excluído permanentemente!');
    }

    /**
     * Lista todos os agendamentos (incluindo os apagados)
     */
    public function lixeira()
    {
        $agendamentos = Agendamento::with(['barbeiro', 'servico'])
                                  ->onlyTrashed()
                                  ->orderBy('deleted_at', 'desc')
                                  ->paginate(15);
        
        return view('agendamentos.lixeira', compact('agendamentos'));
    }

    /**
     * API para verificar horários disponíveis
     */
    public function horariosDisponiveis(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'barbeiro_id' => 'required|exists:barbeiros,id'
        ]);

        $data = Carbon::parse($request->data);
        $barbeiroId = $request->barbeiro_id;

        // Obter horários já ocupados
        $horariosOcupados = Agendamento::whereDate('data', $data)
                                      ->where('barbeiro_id', $barbeiroId)
                                      ->whereNull('deleted_at')
                                      ->pluck('horario')
                                      ->toArray();

        // Gerar horários disponíveis (8:00 às 20:00)
        $horarios = [];
        $horarioAtual = Carbon::createFromTime(8, 0);
        $horarioFinal = Carbon::createFromTime(20, 0);

        while ($horarioAtual <= $horarioFinal) {
            $horario = $horarioAtual->format('H:i');
            $horarios[] = [
                'horario' => $horario,
                'disponivel' => !in_array($horario, $horariosOcupados)
            ];
            $horarioAtual->addMinutes(30);
        }

        return response()->json([
            'disponiveis' => $horarios,
            'data' => $data->format('d/m/Y')
        ]);
    }
}