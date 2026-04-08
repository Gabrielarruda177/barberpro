<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Servico;

class ServicoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an authenticated user
        $this->user = User::factory()->create();
    }

    public function test_can_list_servicos(): void
    {
        $response = $this->actingAs($this->user)->get(route('servicos.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_servico(): void
    {
        $data = [
            'nome' => 'Corte Simples',
            'descricao' => 'Corte de cabelo e finalização',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => 1
        ];

        $response = $this->actingAs($this->user)->post(route('servicos.store'), $data);
        $response->assertRedirect(route('servicos.index'));

        $this->assertDatabaseHas('servicos', [
            'nome' => 'Corte Simples'
        ]);
    }

    public function test_can_update_servico(): void
    {
        $servico = Servico::create([
            'nome' => 'Corte Simples',
            'descricao' => 'Corte',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => true
        ]);

        $data = [
            'nome' => 'Corte Completo Modificado',
            'descricao' => 'Corte',
            'duracao_minutos' => 45,
            'preco' => 40.00,
            'ativo' => true
        ];

        $response = $this->actingAs($this->user)->put(route('servicos.update', $servico), $data);
        $response->assertRedirect(route('servicos.index'));

        $this->assertDatabaseHas('servicos', [
            'id' => $servico->id,
            'nome' => 'Corte Completo Modificado'
        ]);
    }

    public function test_can_soft_delete_servico(): void
    {
        $servico = Servico::create([
            'nome' => 'Corte Teste Delete',
            'descricao' => 'Corte',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => true
        ]);

        $response = $this->actingAs($this->user)->delete(route('servicos.destroy', $servico));
        $response->assertRedirect(route('servicos.index'));

        $this->assertSoftDeleted('servicos', [
            'id' => $servico->id
        ]);
    }

    public function test_can_restore_servico(): void
    {
        $servico = Servico::create([
            'nome' => 'Corte Deletado',
            'descricao' => 'Corte',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => true
        ]);
        $servico->delete();

        $response = $this->actingAs($this->user)->post(route('servicos.restore', $servico->id));
        $response->assertRedirect(route('servicos.index'));

        $this->assertNotSoftDeleted('servicos', [
            'id' => $servico->id
        ]);
    }

    public function test_can_force_delete_servico(): void
    {
        $servico = Servico::create([
            'nome' => 'Corte Deletado',
            'descricao' => 'Corte',
            'duracao_minutos' => 30,
            'preco' => 35.00,
            'ativo' => true
        ]);
        $servico->delete();

        $response = $this->actingAs($this->user)->delete(route('servicos.forceDelete', $servico->id));
        $response->assertRedirect(route('servicos.index'));

        $this->assertDatabaseMissing('servicos', [
            'id' => $servico->id
        ]);
    }
}
