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
        $query = Agendamento::with(['barbeiro', 'servico'])->orderBy('data', 'desc')->orderBy('horario', 'desc');

       
        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('barbeiro_id')) {
            $query->where('barbeiro_id', $request->barbeiro_id);
        }

        $agendamentos = $query->paginate(15);
        $barbeiros = Barbeiro::all();

        return view('agendamentos.index', compact('agendamentos', 'barbeiros'));
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
}