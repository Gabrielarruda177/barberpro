<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = [
        'nome',
        'descricao',
        'duracao_minutos',
        'preco',
        'ativo'
    ];
    
    protected $casts = [
        'preco' => 'decimal:2',
        'ativo' => 'boolean'
    ];
    
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}