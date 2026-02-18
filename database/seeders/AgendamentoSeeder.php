<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Agendamento;
use App\Models\Servico;
use Carbon\Carbon;

class AgendamentoSeeder extends Seeder
{
    public function run(): void
    {
        // Arrays para geração de dados aleatórios
        $nomesClientes = [
            'João Pedro', 'Maria Santos', 'José Silva', 'Ana Costa',
            'Carlos Oliveira', 'Paulo Souza', 'Lucas Rodrigues', 'Fernanda Lima',
            'Ricardo Almeida', 'Juliana Pereira', 'Marcos Cardoso', 'Camila Ferreira',
            'Bruno Gomes', 'Amanda Ribeiro', 'Diego Martins', 'Beatriz Castro',
            'Gabriel Rocha', 'Larissa Barbosa', 'Vinícius Lima', 'Renata Costa',
            'Felipe Santos', 'Isabela Oliveira', 'Leonardo Dias', 'Patrícia Martins',
            'André Castro', 'Tatiane Reis', 'Sérgio Borges', 'Vanessa Mendes',
            'Rafael Augusto', 'Priscila Freire', 'Thiago Moreira', 'Sabrina Vargas',
            'Gustavo Lima', 'Adriana Pires', 'Victor Hugo', 'Renan Teixeira',
            'Simone Duarte', 'Rodrigo Macedo', 'Michele Chaves', 'Eduardo Benfica'
        ];

        $telefones = [
            '(11) 98765-0001', '(11) 98765-0002', '(11) 98765-0003', '(11) 98765-0004',
            '(11) 98765-0005', '(11) 98765-0006', '(11) 98765-0007', '(11) 98765-0008',
            '(11) 98765-0009', '(11) 98765-0010', '(11) 98765-0011', '(11) 98765-0012',
            '(11) 98765-0013', '(11) 98765-0014', '(11) 98765-0015', '(11) 98765-0016',
            '(11) 98765-0017', '(11) 98765-0018', '(11) 98765-0019', '(11) 98765-0020'
        ];

        $horarios = [
            '09:00:00', '09:30:00', '10:00:00', '10:30:00', '11:00:00', '11:30:00',
            '12:00:00', '12:30:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00',
            '15:00:00', '15:30:00', '16:00:00', '16:30:00', '17:00:00', '17:30:00',
            '18:00:00', '18:30:00', '19:00:00'
        ];

        $observacoes = [
            'Cliente preferencial', 'Primeira vez', 'Cliente fidelizado',
            'Solicitou atenção especial', 'Pagamento em dinheiro', null, null, null
        ];

        // Obter serviços disponíveis
        $servicos = Servico::all();
        $barbeiroIds = [1, 2, 3, 4]; // IDs dos barbeiros

        // Data base: hoje
        $hoje = Carbon::today();

        // ===== 24 AGENDAMENTOS CONCLUÍDOS =====
        for ($i = 0; $i < 24; $i++) {
            // Distribuir pelos últimos 7 dias
            $diasAtras = rand(0, 6);
            $data = $hoje->copy()->subDays($diasAtras);

            $servico = $servicos->random();
            $barbeiroId = $barbeiroIds[array_rand($barbeiroIds)];
            $nomeCliente = $nomesClientes[array_rand($nomesClientes)];
            $telefone = $telefones[array_rand($telefones)];
            $horario = $horarios[array_rand($horarios)];
            $observacao = $observacoes[array_rand($observacoes)];

            // Para serviços concluídos, o valor pode ter pequenas variações
            $valor = $servico->preco + (rand(-5, 5)); // Variação de -5 a +5 reais

            Agendamento::create([
                'nome_cliente' => $nomeCliente,
                'telefone_cliente' => $telefone,
                'barbeiro_id' => $barbeiroId,
                'servico_id' => $servico->id,
                'data' => $data,
                'horario' => $horario,
                'observacoes' => $observacao,
                'status' => 'concluido',
                'valor' => $valor
            ]);
        }

        // ===== 16 AGENDAMENTOS CANCELADOS =====
        for ($i = 0; $i < 16; $i++) {
            // Distribuir pelos últimos 14 dias (alguns mais antigos)
            $diasAtras = rand(0, 13);
            $data = $hoje->copy()->subDays($diasAtras);

            $servico = $servicos->random();
            $barbeiroId = $barbeiroIds[array_rand($barbeiroIds)];
            $nomeCliente = $nomesClientes[array_rand($nomesClientes)];
            $telefone = $telefones[array_rand($telefones)];
            $horario = $horarios[array_rand($horarios)];
            $observacao = $observacoes[array_rand($observacoes)];

            Agendamento::create([
                'nome_cliente' => $nomeCliente,
                'telefone_cliente' => $telefone,
                'barbeiro_id' => $barbeiroId,
                'servico_id' => $servico->id,
                'data' => $data,
                'horario' => $horario,
                'observacoes' => $observacao,
                'status' => 'cancelado',
                'valor' => $servico->preco
            ]);
        }

        // ===== 30 AGENDAMENTOS EM ESPERA (AGENDADOS) =====
        for ($i = 0; $i < 30; $i++) {
            // Distribuir entre hoje e próximos 7 dias
            $diasFrente = rand(0, 7);
            $data = $hoje->copy()->addDays($diasFrente);

            $servico = $servicos->random();
            $barbeiroId = $barbeiroIds[array_rand($barbeiroIds)];
            $nomeCliente = $nomesClientes[array_rand($nomesClientes)];
            $telefone = $telefones[array_rand($telefones)];
            $horario = $horarios[array_rand($horarios)];
            $observacao = $observacoes[array_rand($observacoes)];

            Agendamento::create([
                'nome_cliente' => $nomeCliente,
                'telefone_cliente' => $telefone,
                'barbeiro_id' => $barbeiroId,
                'servico_id' => $servico->id,
                'data' => $data,
                'horario' => $horario,
                'observacoes' => $observacao,
                'status' => 'agendado',
                'valor' => $servico->preco
            ]);
        }
    }
}
