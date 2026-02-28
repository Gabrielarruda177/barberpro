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
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'duracao_minutos'  => 'required|integer|min:1',
            'preco'            => 'required|numeric|min:0',
        ]);

        Servico::create($request->all());

        return redirect()->route('servicos.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'duracao_minutos'  => 'required|integer|min:1',
            'preco'            => 'required|numeric|min:0',
        ]);

        $servico->update($request->all());

        return redirect()->route('servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico)
    {
        $servico->delete(); // Soft delete

        return redirect()->route('servicos.index')->with('success', 'Serviço apagado com sucesso!');
    }

    public function softDelete(Servico $servico)
    {
        $servico->delete(); // Soft delete

        return redirect()->route('servicos.index')->with('success', 'Serviço apagado com sucesso!');
    }

    public function restore($id)
    {
        $servico = Servico::withTrashed()->findOrFail($id);
        $servico->restore();

        return redirect()->route('servicos.index')->with('success', 'Serviço restaurado com sucesso!');
    }

    public function forceDelete($id)
    {
        $servico = Servico::withTrashed()->findOrFail($id);
        $servico->forceDelete();

        return redirect()->route('servicos.index')->with('success', 'Serviço excluído permanentemente!');
    }

    public function lixeira()
    {
        $servicos = Servico::onlyTrashed()
                           ->orderBy('deleted_at', 'desc')
                           ->paginate(15);

        return view('servicos.lixeira', compact('servicos'));
    }

    public function getJson(Servico $servico)
    {
        return response()->json($servico);
    }

    public function toggleStatus(Servico $servico)
    {
        $servico->ativo = !$servico->ativo;
        $servico->save();

        return redirect()->route('servicos.index')->with('success', 'Status do serviço atualizado com sucesso!');
    }
}