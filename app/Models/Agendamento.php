<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome_cliente',
        'telefone_cliente',
        'barbeiro_id',
        'servico_id',
        'data',
        'horario',
        'observacoes',
        'status',
        'valor'
    ];
    
    protected $casts = [
        'data' => 'date',
        'horario' => 'datetime:H:i',
        'valor' => 'decimal:2'
    ];
    
    public function barbeiro()
    {
        return $this->belongsTo(Barbeiro::class);
    }
    
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }
}