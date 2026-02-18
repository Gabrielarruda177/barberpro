<?php

namespace App\Http\Controllers;

use App\Models\Barbeiro;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BarbeiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $barbeiros = Barbeiro::orderBy('nome')->get();
        return view('barbeiros.index', compact('barbeiros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'especialidades' => 'required|string|max:255',
            'inicio_trabalho' => 'required|date_format:H:i',
            'fim_trabalho' => 'required|date_format:H:i|after:inicio_trabalho',
        ], [
            'fim_trabalho.after' => 'O horário de fim deve ser posterior ao horário de início.',
        ]);

        Barbeiro::create($validated);

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro criado com sucesso!');
    }

    /**
     * Return the specified resource in JSON format.
     * Necessário para o JavaScript do modal de edição.
     */
    // MUDOU AQUI: de 'json' para 'getJson'
    public function getJson(Barbeiro $barbeiro): \Illuminate\Http\JsonResponse
    {
        return response()->json($barbeiro);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barbeiro $barbeiro): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'especialidades' => 'required|string|max:255',
            'inicio_trabalho' => 'required|date_format:H:i',
            'fim_trabalho' => 'required|date_format:H:i|after:inicio_trabalho',
        ], [
            'fim_trabalho.after' => 'O horário de fim deve ser posterior ao horário de início.',
        ]);

        $barbeiro->update($validated);

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barbeiro $barbeiro): RedirectResponse
    {
        // Opcional: deletar a foto do barbeiro se existir
        $avatarPath = 'images/barbeiros/' . $barbeiro->id . '.jpg';
        if (Storage::disk('public')->exists($avatarPath)) {
            Storage::disk('public')->delete($avatarPath);
        }

        $barbeiro->delete();

        return redirect()->route('barbeiros.index')
                         ->with('success', 'Barbeiro excluído com sucesso!');
    }
}