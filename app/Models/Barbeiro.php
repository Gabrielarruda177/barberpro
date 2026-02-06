<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbeiro extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nome',
        'telefone',
        'especialidades',
        'inicio_trabalho',
        'fim_trabalho'
    ];
    
    protected $casts = [
        'inicio_trabalho' => 'datetime:H:i',
        'fim_trabalho' => 'datetime:H:i'
    ];
    
    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}