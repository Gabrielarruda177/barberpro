<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barbeiro;

class BarbeiroApiController extends Controller
{
    public function show($id)
    {
        $barbeiro = Barbeiro::findOrFail($id);
        return response()->json($barbeiro);
    }
}