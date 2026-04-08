<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Servico;
use App\Models\Barbeiro;
use App\Models\Agendamento;
use Carbon\Carbon;

class AgendamentoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an authenticated user
        $this->user = User::factory()->create();

        // Create base requirements
        $this->barbeiro = Barbeiro::create([
            'nome' => 'Barbeiro 1',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ]);

        $this->servico = Servico::create([
            'nome' => 'Corte',
            'descricao' => 'Corte de cabelo',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => true
        ]);
    }

    public function test_can_list_agendamentos(): void
    {
        $response = $this->actingAs($this->user)->get(route('agendamentos.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_agendamento(): void
    {
        // Tomorrow at 10 AM
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $data = [
            'nome_cliente' => 'Cliente Teste',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:00',
            'observacoes' => 'Nenhuma'
        ];

        $response = $this->actingAs($this->user)->post(route('agendamentos.store'), $data);
        $response->assertRedirect(route('agendamentos.index'));

        $this->assertDatabaseHas('agendamentos', [
            'nome_cliente' => 'Cliente Teste',
            'data' => $tomorrow . ' 00:00:00',
            'horario' => '10:00',
        ]);
    }

    public function test_cannot_create_agendamento_at_occupied_time(): void
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Primeito agendamento
        Agendamento::create([
            'nome_cliente' => 'Cliente 1',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:00',
            'valor' => 35.00,
            'status' => 'agendado'
        ]);

        // Tentativa no mesmo horario
        $data = [
            'nome_cliente' => 'Cliente Conflito',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:00',
            'observacoes' => 'Nenhuma'
        ];

        $response = $this->actingAs($this->user)->post(route('agendamentos.store'), $data);
        
        // Assert it failed validation
        $response->assertSessionHasErrors(['horario']);

        $this->assertDatabaseMissing('agendamentos', [
            'nome_cliente' => 'Cliente Conflito'
        ]);
    }

    public function test_can_update_agendamento(): void
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        
        $agendamento = Agendamento::create([
            'nome_cliente' => 'Cliente 1',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:00',
            'valor' => 35.00,
            'status' => 'agendado'
        ]);

        $data = [
            'nome_cliente' => 'Cliente Modificado',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:30',
            'status' => 'concluido'
        ];

        $response = $this->actingAs($this->user)->put(route('agendamentos.update', $agendamento), $data);
        $response->assertRedirect(route('agendamentos.index'));

        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamento->id,
            'nome_cliente' => 'Cliente Modificado',
            'status' => 'concluido',
            'horario' => '10:30'
        ]);
    }

    public function test_can_soft_delete_agendamento(): void
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $agendamento = Agendamento::create([
            'nome_cliente' => 'Cliente Deletar',
            'telefone_cliente' => '11988888888',
            'barbeiro_id' => $this->barbeiro->id,
            'servico_id' => $this->servico->id,
            'data' => $tomorrow,
            'horario' => '10:00',
            'valor' => 35.00,
            'status' => 'agendado'
        ]);

        $response = $this->actingAs($this->user)->delete(route('agendamentos.destroy', $agendamento));
        $response->assertRedirect(route('agendamentos.index'));

        $this->assertSoftDeleted('agendamentos', [
            'id' => $agendamento->id
        ]);
    }
}
