<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barbeiro;

class BarbeiroController extends Controller
{
    public function index()
    {
        $barbeiros = Barbeiro::all();
        
        return view('barbeiros.index', compact('barbeiros'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'especialidades' => 'required|string',
            'inicio_trabalho' => 'required',
            'fim_trabalho' => 'required'
        ]);
        
        Barbeiro::create($request->all());
        
        return redirect()->route('barbeiros.index')->with('success', 'Barbeiro criado com sucesso!');
    }
    
    public function update(Request $request, Barbeiro $barbeiro)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'especialidades' => 'required|string',
            'inicio_trabalho' => 'required',
            'fim_trabalho' => 'required'
        ]);
        
        $barbeiro->update($request->all());
        
        return redirect()->route('barbeiros.index')->with('success', 'Barbeiro atualizado com sucesso!');
    }
    
    public function destroy(Barbeiro $barbeiro)
    {
        $barbeiro->delete();
        
        return redirect()->route('barbeiros.index')->with('success', 'Barbeiro excluído com sucesso!');
    }
}