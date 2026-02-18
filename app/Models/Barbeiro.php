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
        'fim_trabalho',
        'ativo'
    ];
    
    protected $casts = [
        'inicio_trabalho' => 'datetime:H:i',
        'fim_trabalho' => 'datetime:H:i',
        'ativo' => 'boolean'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}