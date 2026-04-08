<?php

namespace Database\Seeders;

use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    
    public function run(): void
    {
        $now = Carbon::now();

        Servico::insert([
            [
                'nome' => 'Corte Tradicional',
                'descricao' => 'Corte masculino classico com acabamento na navalha.',
                'duracao_minutos' => 30,
                'preco' => 45.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(18),
                'updated_at' => $now->copy()->subDays(10),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Barba Completa',
                'descricao' => 'Barba desenhada com toalha quente e finalizacao.',
                'duracao_minutos' => 25,
                'preco' => 35.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(18),
                'updated_at' => $now->copy()->subDays(10),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Corte + Barba',
                'descricao' => 'Combo com corte completo e barba desenhada.',
                'duracao_minutos' => 50,
                'preco' => 75.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(17),
                'updated_at' => $now->copy()->subDays(8),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Pigmentacao',
                'descricao' => 'Pigmentacao de barba ou falhas leves.',
                'duracao_minutos' => 30,
                'preco' => 55.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(14),
                'updated_at' => $now->copy()->subDays(6),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Platinado',
                'descricao' => 'Descoloracao com tratamento e finalizacao.',
                'duracao_minutos' => 100,
                'preco' => 160.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(12),
                'updated_at' => $now->copy()->subDays(4),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Sobrancelha',
                'descricao' => 'Alinhamento e acabamento de sobrancelha masculina.',
                'duracao_minutos' => 15,
                'preco' => 20.00,
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(11),
                'updated_at' => $now->copy()->subDays(2),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Hidratacao Premium',
                'descricao' => 'Tratamento capilar rapido para pos-corte.',
                'duracao_minutos' => 20,
                'preco' => 28.00,
                'ativo' => false,
                'created_at' => $now->copy()->subMonths(9),
                'updated_at' => $now->copy()->subMonths(1),
                'deleted_at' => $now->copy()->subMonths(1),
            ],
        ]);

        return;

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
