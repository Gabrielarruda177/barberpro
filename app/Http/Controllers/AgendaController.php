<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->get('data') ? Carbon::parse($request->get('data')) : Carbon::today();
        $mes = $data->month;
        $ano = $data->year;
        
        $agendamentos = Agendamento::with(['barbeiro', 'servico'])
                                  ->whereMonth('data', $mes)
                                  ->whereYear('data', $ano)
                                  ->get()
                                  ->groupBy(function($item) {
                                      return $item->data->format('Y-m-d');
                                  });
        
        $agendamentosDia = $agendamentos->get($data->format('Y-m-d'), collect());
        
        return view('agenda', compact('data', 'agendamentos', 'agendamentosDia'));
    }
}