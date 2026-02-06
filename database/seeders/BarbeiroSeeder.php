<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barbeiro; 

class BarbeiroSeeder extends Seeder
{
   
    public function run(): void
    {
        $barbeiros = [
            [
                'nome' => 'Carlos Silva',
                'telefone' => '(11) 98765-4321',
                'especialidades' => 'Cortes clássicos, Barba',
                'inicio_trabalho' => '09:00',
                'fim_trabalho' => '19:00'
            ],
            [
                'nome' => 'Roberto Santos',
                'telefone' => '(11) 98765-4322',
                'especialidades' => 'Cortes modernos, Sobrancelha',
                'inicio_trabalho' => '10:00',
                'fim_trabalho' => '20:00'
            ],
            [
                'nome' => 'Pedro Oliveira',
                'telefone' => '(11) 98765-4323',
                'especialidades' => 'Cortes, Barba, Pigmentação',
                'inicio_trabalho' => '08:00',
                'fim_trabalho' => '18:00'
            ],
            [
                'nome' => 'João Ferreira',
                'telefone' => '(11) 98765-4324',
                'especialidades' => 'Todos os serviços',
                'inicio_trabalho' => '09:00',
                'fim_trabalho' => '19:00'
            ]
        ];
        
        foreach ($barbeiros as $barbeiro) {
            Barbeiro::create($barbeiro);
        }
    }
}