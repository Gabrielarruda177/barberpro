<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servico;

class ServicoApiController extends Controller
{
    public function show($id)
    {
        $servico = Servico::findOrFail($id);
        return response()->json($servico);
    }
}