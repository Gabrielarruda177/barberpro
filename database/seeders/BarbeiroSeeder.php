<?php

namespace Database\Seeders;

use App\Models\Barbeiro;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BarbeiroSeeder extends Seeder
{
   
    public function run(): void
    {
        $now = Carbon::now();

        Barbeiro::insert([
            [
                'nome' => 'Carlos Silva',
                'telefone' => '(11) 98765-4321',
                'especialidades' => 'Cortes classicos, barba e navalhado',
                'inicio_trabalho' => '09:00',
                'fim_trabalho' => '19:00',
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(18),
                'updated_at' => $now->copy()->subDays(12),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Roberto Santos',
                'telefone' => '(11) 98765-4322',
                'especialidades' => 'Fade, cortes modernos e sobrancelha',
                'inicio_trabalho' => '10:00',
                'fim_trabalho' => '20:00',
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(17),
                'updated_at' => $now->copy()->subDays(8),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Pedro Oliveira',
                'telefone' => '(11) 98765-4323',
                'especialidades' => 'Barba, pigmentacao e acabamento premium',
                'inicio_trabalho' => '08:00',
                'fim_trabalho' => '18:00',
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(16),
                'updated_at' => $now->copy()->subDays(5),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Joao Ferreira',
                'telefone' => '(11) 98765-4324',
                'especialidades' => 'Corte social, tesoura e atendimento executivo',
                'inicio_trabalho' => '09:00',
                'fim_trabalho' => '19:00',
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(15),
                'updated_at' => $now->copy()->subDays(3),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Marcos Vinicius',
                'telefone' => '(11) 98765-4325',
                'especialidades' => 'Degrade, freestyle e desenho',
                'inicio_trabalho' => '11:00',
                'fim_trabalho' => '21:00',
                'ativo' => true,
                'created_at' => $now->copy()->subMonths(10),
                'updated_at' => $now->copy()->subDay(),
                'deleted_at' => null,
            ],
            [
                'nome' => 'Andre Luiz',
                'telefone' => '(11) 98765-4326',
                'especialidades' => 'Barba tradicional e toalha quente',
                'inicio_trabalho' => '09:00',
                'fim_trabalho' => '17:00',
                'ativo' => false,
                'created_at' => $now->copy()->subMonths(18),
                'updated_at' => $now->copy()->subMonths(2),
                'deleted_at' => $now->copy()->subMonths(2),
            ],
        ]);

        return;

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
