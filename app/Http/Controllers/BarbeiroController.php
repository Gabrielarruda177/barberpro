<?php

namespace App\Http\Controllers;

use App\Models\Barbeiro;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarbeiroController extends Controller
{
    public function index(): View
    {
        $barbeiros = Barbeiro::orderBy('nome')->get();

        return view('barbeiros.index', compact('barbeiros'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'telefone'        => 'required|string|max:20',
            'especialidades'  => 'required|string|max:255',
            'inicio_trabalho' => 'required|date_format:H:i',
            'fim_trabalho'    => 'required|date_format:H:i|after:inicio_trabalho',
        ], [
            'fim_trabalho.after' => 'O horário de fim deve ser posterior ao horário de início.',
        ]);

        Barbeiro::create($validated);

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro criado com sucesso!');
    }

    public function getJson(Barbeiro $barbeiro): \Illuminate\Http\JsonResponse
    {
        return response()->json($barbeiro);
    }

    public function update(Request $request, Barbeiro $barbeiro): RedirectResponse
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'telefone'        => 'required|string|max:20',
            'especialidades'  => 'required|string|max:255',
            'inicio_trabalho' => 'required|date_format:H:i',
            'fim_trabalho'    => 'required|date_format:H:i|after:inicio_trabalho',
        ], [
            'fim_trabalho.after' => 'O horário de fim deve ser posterior ao horário de início.',
        ]);

        $barbeiro->update($validated);

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro atualizado com sucesso!');
    }

    public function destroy(Barbeiro $barbeiro): RedirectResponse
    {
        $barbeiro->delete(); // Soft delete

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro apagado com sucesso!');
    }

    public function softDelete(Barbeiro $barbeiro): RedirectResponse
    {
        $barbeiro->delete(); // Soft delete

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro apagado com sucesso!');
    }

    public function restore($id): RedirectResponse
    {
        $barbeiro = Barbeiro::withTrashed()->findOrFail($id);
        $barbeiro->restore();

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro restaurado com sucesso!');
    }

    public function forceDelete($id): RedirectResponse
    {
        $barbeiro = Barbeiro::withTrashed()->findOrFail($id);
        $barbeiro->forceDelete();

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro excluído permanentemente!');
    }

    public function lixeira(): View
    {
        $barbeiros = Barbeiro::onlyTrashed()
                             ->orderBy('deleted_at', 'desc')
                             ->paginate(15);

        return view('barbeiros.lixeira', compact('barbeiros'));
    }

    public function toggleStatus(Barbeiro $barbeiro): RedirectResponse
    {
        $barbeiro->ativo = !$barbeiro->ativo;
        $barbeiro->save();

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Status do barbeiro atualizado com sucesso!');
    }
}