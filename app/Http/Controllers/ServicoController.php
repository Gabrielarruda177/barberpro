<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::all();
        
        return view('servicos.index', compact('servicos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'duracao_minutos' => 'required|integer|min:1',
            'preco' => 'required|numeric|min:0'
        ]);
        
        Servico::create($request->all());
        
        return redirect()->route('servicos.index')->with('success', 'Serviço criado com sucesso!');
    }
    
    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'duracao_minutos' => 'required|integer|min:1',
            'preco' => 'required|numeric|min:0'
        ]);
        
        $servico->update($request->all());
        
        return redirect()->route('servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }
    
    public function destroy(Servico $servico)
    {
        $servico->delete();
        
        return redirect()->route('servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }
    
    public function toggleStatus(Servico $servico)
    {
        $servico->ativo = !$servico->ativo;
        $servico->save();
        
        return redirect()->route('servicos.index')->with('success', 'Status do serviço atualizado com sucesso!');
    }
}