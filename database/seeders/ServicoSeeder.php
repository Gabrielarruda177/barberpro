<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servico; 

class ServicoSeeder extends Seeder
{
    
    public function run(): void
    {
        $servicos = [
            [
                'nome' => 'Corte de Cabelo',
                'descricao' => 'Corte de cabelo masculino',
                'duracao_minutos' => 30,
                'preco' => 45.00,
            ],
            [
                'nome' => 'Barba',
                'descricao' => 'Barba completa',
                'duracao_minutos' => 20,
                'preco' => 30.00,
            ],
            [
                'nome' => 'Corte + Barba',
                'descricao' => 'Pacote com corte e barba',
                'duracao_minutos' => 45,
                'preco' => 65.00,
            ],
            [
                'nome' => 'Pigmentação',
                'descricao' => 'Pigmentação de barba',
                'duracao_minutos' => 30,
                'preco' => 50.00,
            ],
            [
                'nome' => 'Platinado',
                'descricao' => 'Platinado completo',
                'duracao_minutos' => 90,
                'preco' => 120.00,
            ]
        ];
        
        foreach ($servicos as $servico) {
            Servico::create($servico);
        }
    }
}