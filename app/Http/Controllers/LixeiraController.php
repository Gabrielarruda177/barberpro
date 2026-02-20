<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Barbeiro;
use App\Models\Servico;

class LixeiraController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::onlyTrashed()
            ->with(['barbeiro', 'servico'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10, ['*'], 'page_ag');

        $barbeiros = Barbeiro::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10, ['*'], 'page_ba');

        $servicos = Servico::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10, ['*'], 'page_sv');

        $total = $agendamentos->total() + $barbeiros->total() + $servicos->total();

        return view('lixeira.index', compact('agendamentos', 'barbeiros', 'servicos', 'total'));
    }

    /* ── AGENDAMENTOS ── */
    public function restoreAgendamento($id)
    {
        Agendamento::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Agendamento restaurado com sucesso!');
    }

    public function forceDeleteAgendamento($id)
    {
        Agendamento::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Agendamento excluído permanentemente.');
    }

    /* ── BARBEIROS ── */
    public function restoreBarbeiro($id)
    {
        Barbeiro::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Barbeiro restaurado com sucesso!');
    }

    public function forceDeleteBarbeiro($id)
    {
        Barbeiro::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Barbeiro excluído permanentemente.');
    }

    /* ── SERVIÇOS ── */
    public function restoreServico($id)
    {
        Servico::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Serviço restaurado com sucesso!');
    }

    public function forceDeleteServico($id)
    {
        Servico::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Serviço excluído permanentemente.');
        
    }
}