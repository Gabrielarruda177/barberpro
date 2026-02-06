<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BarbeiroApiController;
use App\Http\Controllers\Api\ServicoApiController;

Route::get('/barbeiros/{id}', [BarbeiroApiController::class, 'show']);
Route::get('/servicos/{id}', [ServicoApiController::class, 'show']);