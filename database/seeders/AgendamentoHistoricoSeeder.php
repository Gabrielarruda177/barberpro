<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\Barbeiro;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AgendamentoHistoricoSeeder extends Seeder
{
    private array $nomes = [
        'Joao Pedro', 'Miguel Henrique', 'Arthur Gomes', 'Heitor Martins', 'Davi Lucca',
        'Gabriel Souza', 'Matheus Silva', 'Lucas Almeida', 'Rafael Costa', 'Pedro Araujo',
        'Bruno Ferreira', 'Thiago Nunes', 'Vinicius Rocha', 'Caio Mendes', 'Murilo Santos',
        'Leonardo Campos', 'Enzo Ribeiro', 'Felipe Barros', 'Guilherme Lopes', 'Diego Teixeira',
        'Rodrigo Moraes', 'Samuel Batista', 'Eduardo Freitas', 'Daniel Cardoso', 'Anderson Lima',
        'Ricardo Xavier', 'Vitor Hugo', 'Renato Pires', 'Marcos Paulo', 'Jean Oliveira',
        'Alan Castro', 'Wesley Dias', 'Julio Cesar', 'Cristiano Alves', 'Alex Sandro',
        'Carlos Eduardo', 'Fernando Rezende', 'Igor Melo', 'Nathan Vieira', 'Otavio Prado',
        'Kevin Moura', 'Yuri Sampaio', 'Adriano Farias', 'Breno Cunha', 'Cesar Augusto',
        'Douglas Soares', 'Erick Figueiredo', 'Fabricio Dantas', 'Henrique Teles', 'Jeferson Brito',
    ];

    private array $sobrenomes = [
        'Silva', 'Santos', 'Oliveira', 'Souza', 'Pereira', 'Costa', 'Rodrigues', 'Almeida',
        'Nascimento', 'Lima', 'Araujo', 'Fernandes', 'Carvalho', 'Gomes', 'Martins', 'Rocha',
        'Barbosa', 'Dias', 'Ribeiro', 'Freitas',
    ];

    private array $observacoes = [
        null,
        null,
        null,
        'Cliente prefere atendimento rapido.',
        'Pagar no pix.',
        'Primeira visita na barbearia.',
        'Cliente fiel, costuma agendar no mesmo barbeiro.',
        'Solicitou acabamento na navalha.',
        'Chega sempre 10 minutos antes.',
        'Confirmado pelo WhatsApp.',
    ];

    public function run(): void
    {
        $barbeiros = Barbeiro::query()->where('ativo', true)->get();
        $servicos = Servico::query()->where('ativo', true)->get();

        if ($barbeiros->isEmpty() || $servicos->isEmpty()) {
            return;
        }

        $inicioHistorico = Carbon::today()->subMonths(18)->startOfMonth();
        $fimHistorico = Carbon::today();
        $inicioFuturo = Carbon::today()->addDay();
        $fimFuturo = Carbon::today()->addDays(21);

        $ocupacao = [];
        $agendamentos = [];

        for ($data = $inicioHistorico->copy(); $data->lte($fimHistorico); $data->addDay()) {
            if ($data->isSunday()) {
                continue;
            }

            $agendamentos = array_merge(
                $agendamentos,
                $this->gerarAgendamentosDoDia(
                    $data->copy(),
                    $this->definirVolumeHistorico($data),
                    $barbeiros,
                    $servicos,
                    $ocupacao,
                    false
                )
            );
        }

        for ($data = $inicioFuturo->copy(); $data->lte($fimFuturo); $data->addDay()) {
            if ($data->isSunday()) {
                continue;
            }

            $agendamentos = array_merge(
                $agendamentos,
                $this->gerarAgendamentosDoDia(
                    $data->copy(),
                    $this->definirVolumeFuturo($data),
                    $barbeiros,
                    $servicos,
                    $ocupacao,
                    true
                )
            );
        }

        foreach (array_chunk($agendamentos, 500) as $lote) {
            Agendamento::insert($lote);
        }
    }

    private function gerarAgendamentosDoDia(
        Carbon $data,
        int $quantidade,
        Collection $barbeiros,
        Collection $servicos,
        array &$ocupacao,
        bool $somenteFuturos
    ): array {
        $registros = [];
        $tentativas = 0;

        while (count($registros) < $quantidade && $tentativas < ($quantidade * 20) + 50) {
            $tentativas++;

            $barbeiro = $barbeiros->random();
            $servico = $this->sortearServico($servicos, $data);
            $horario = $this->sortearHorario($data, $barbeiro, (int) $servico->duracao_minutos, $ocupacao);

            if ($horario === null) {
                continue;
            }

            [$status, $deletedAt] = $this->definirStatus($data, $somenteFuturos);
            $cliente = $this->gerarCliente();
            $valor = $this->definirValor($servico, $status, $data);
            $createdAt = $this->gerarCreatedAt($data, $status);
            $updatedAt = $this->gerarUpdatedAt($createdAt, $data, $status, $deletedAt);

            $registros[] = [
                'nome_cliente' => $cliente['nome'],
                'telefone_cliente' => $cliente['telefone'],
                'barbeiro_id' => $barbeiro->id,
                'servico_id' => $servico->id,
                'data' => $data->format('Y-m-d'),
                'horario' => $horario,
                'observacoes' => $this->observacoes[array_rand($this->observacoes)],
                'status' => $status,
                'valor' => $valor,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'deleted_at' => $deletedAt,
            ];
        }

        return $registros;
    }

    private function definirVolumeHistorico(Carbon $data): int
    {
        $base = match ($data->dayOfWeekIso) {
            1 => rand(7, 10),
            2 => rand(8, 12),
            3 => rand(9, 13),
            4 => rand(10, 14),
            5 => rand(12, 18),
            6 => rand(8, 12),
            default => 0,
        };

        if ($data->isSameMonth(Carbon::today())) {
            $base += 2;
        }

        if (in_array((int) $data->month, [11, 12], true)) {
            $base += rand(2, 4);
        }

        if ((int) $data->month === 1) {
            $base += rand(1, 3);
        }

        return $base;
    }

    private function definirVolumeFuturo(Carbon $data): int
    {
        return match ($data->dayOfWeekIso) {
            1 => rand(3, 5),
            2 => rand(4, 7),
            3 => rand(5, 8),
            4 => rand(5, 8),
            5 => rand(7, 11),
            6 => rand(4, 7),
            default => 0,
        };
    }

    private function sortearServico(Collection $servicos, Carbon $data): Servico
    {
        $pesos = [];

        foreach ($servicos as $servico) {
            $peso = match ($servico->nome) {
                'Corte Tradicional' => 34,
                'Barba Completa' => 18,
                'Corte + Barba' => 24,
                'Pigmentacao' => 10,
                'Platinado' => $data->month >= 10 || $data->month <= 2 ? 8 : 4,
                'Sobrancelha' => 12,
                default => 5,
            };

            $pesos[] = ['servico' => $servico, 'peso' => $peso];
        }

        $sorteio = rand(1, array_sum(array_column($pesos, 'peso')));
        $acumulado = 0;

        foreach ($pesos as $item) {
            $acumulado += $item['peso'];
            if ($sorteio <= $acumulado) {
                return $item['servico'];
            }
        }

        return $servicos->random();
    }

    private function sortearHorario(Carbon $data, Barbeiro $barbeiro, int $duracao, array &$ocupacao): ?string
    {
        $inicio = Carbon::parse($barbeiro->inicio_trabalho);
        $fim = Carbon::parse($barbeiro->fim_trabalho);
        $ultimoInicio = $fim->copy()->subMinutes($duracao);

        $slots = [];
        for ($cursor = $inicio->copy(); $cursor->lte($ultimoInicio); $cursor->addMinutes(30)) {
            $hora = $cursor->format('H:i:s');

            if ($this->horarioDisponivel($data->format('Y-m-d'), $barbeiro->id, $hora, $duracao, $ocupacao)) {
                $slots[] = $hora;
            }
        }

        if ($slots === []) {
            return null;
        }

        usort($slots, function (string $a, string $b) {
            return $this->pesoHorario($a) <=> $this->pesoHorario($b);
        });

        $top = array_slice($slots, 0, min(count($slots), 6));
        $horario = $top[array_rand($top)];
        $this->reservarHorario($data->format('Y-m-d'), $barbeiro->id, $horario, $duracao, $ocupacao);

        return $horario;
    }

    private function horarioDisponivel(string $data, int $barbeiroId, string $inicio, int $duracao, array $ocupacao): bool
    {
        $inicioMin = $this->paraMinutos($inicio);
        $fimMin = $inicioMin + $duracao;

        foreach ($ocupacao[$data][$barbeiroId] ?? [] as [$ocupadoInicio, $ocupadoFim]) {
            if ($inicioMin < $ocupadoFim && $fimMin > $ocupadoInicio) {
                return false;
            }
        }

        return true;
    }

    private function reservarHorario(string $data, int $barbeiroId, string $inicio, int $duracao, array &$ocupacao): void
    {
        $ocupacao[$data][$barbeiroId][] = [
            $this->paraMinutos($inicio),
            $this->paraMinutos($inicio) + $duracao,
        ];
    }

    private function pesoHorario(string $hora): int
    {
        $preferidos = [
            '09:00:00', '09:30:00', '10:00:00', '11:00:00', '13:30:00',
            '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00',
        ];

        $indice = array_search($hora, $preferidos, true);

        return $indice === false ? 999 : $indice;
    }

    private function definirStatus(Carbon $data, bool $somenteFuturos): array
    {
        if ($somenteFuturos || $data->isFuture()) {
            return [rand(1, 100) <= 90 ? 'agendado' : 'cancelado', null];
        }

        if ($data->isToday()) {
            $sorteio = rand(1, 100);

            return match (true) {
                $sorteio <= 55 => ['concluido', null],
                $sorteio <= 85 => ['agendado', null],
                default => ['cancelado', null],
            };
        }

        $sorteio = rand(1, 100);
        $status = match (true) {
            $sorteio <= 78 => 'concluido',
            $sorteio <= 92 => 'cancelado',
            default => 'agendado',
        };

        $deletedAt = null;
        if ($status === 'cancelado' && rand(1, 100) <= 18) {
            $deletedAt = $data->copy()->addDays(rand(0, 12))->setTime(rand(8, 19), rand(0, 1) * 30);
        }

        return [$status, $deletedAt];
    }

    private function definirValor(Servico $servico, string $status, Carbon $data): float
    {
        $preco = (float) $servico->preco;

        if ($status !== 'concluido') {
            return $preco;
        }

        $variacao = rand(-300, 800) / 100;
        $mesesAtras = Carbon::today()->diffInMonths($data);
        $ajusteHistorico = $mesesAtras > 12 ? -2.0 : 0.0;

        return max(15, round($preco + $variacao + $ajusteHistorico, 2));
    }

    private function gerarCreatedAt(Carbon $data, string $status): Carbon
    {
        $diasAntecedencia = match ($status) {
            'concluido', 'cancelado' => rand(1, 35),
            default => rand(0, 20),
        };

        $minutos = [0, 10, 20, 30, 40, 50];

        $createdAt = $data->copy()
            ->subDays($diasAntecedencia)
            ->setTime(rand(8, 21), $minutos[array_rand($minutos)]);

        return $createdAt->greaterThan(Carbon::now())
            ? Carbon::now()->copy()->subMinutes(rand(10, 180))
            : $createdAt;
    }

    private function gerarUpdatedAt(Carbon $createdAt, Carbon $data, string $status, ?Carbon $deletedAt): Carbon
    {
        if ($deletedAt) {
            return $deletedAt->copy();
        }

        $limite = $status === 'agendado' && $data->isFuture()
            ? Carbon::now()
            : $data->copy()->endOfDay();

        $updatedAt = $createdAt->copy()->addDays(rand(0, 7))->addHours(rand(0, 12));

        if ($updatedAt->greaterThan($limite)) {
            $updatedAt = $limite->copy()->subMinutes(rand(5, 180));
        }

        if ($updatedAt->lessThan($createdAt)) {
            $updatedAt = $createdAt->copy()->addMinutes(5);
        }

        return $updatedAt;
    }

    private function gerarCliente(): array
    {
        $nomeBase = $this->nomes[array_rand($this->nomes)];
        $nome = rand(1, 100) <= 35
            ? $nomeBase
            : $nomeBase . ' ' . $this->sobrenomes[array_rand($this->sobrenomes)];

        return [
            'nome' => $nome,
            'telefone' => $this->gerarTelefone(),
        ];
    }

    private function gerarTelefone(): string
    {
        return sprintf(
            '(%02d) 9%04d-%04d',
            rand(11, 19),
            rand(1000, 9999),
            rand(1000, 9999)
        );
    }

    private function paraMinutos(string $hora): int
    {
        [$h, $m] = array_map('intval', explode(':', substr($hora, 0, 5)));

        return ($h * 60) + $m;
    }
}
