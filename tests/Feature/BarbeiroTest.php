<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Barbeiro;

class BarbeiroTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an authenticated user
        $this->user = User::factory()->create();
    }

    public function test_can_list_barbeiros(): void
    {
        $response = $this->actingAs($this->user)->get(route('barbeiros.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_barbeiro(): void
    {
        $data = [
            'nome' => 'João Barbeiro',
            'telefone' => '11999999999',
            'especialidades' => 'Corte, Barba',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => 1
        ];

        $response = $this->actingAs($this->user)->post(route('barbeiros.store'), $data);
        $response->assertRedirect(route('barbeiros.index'));

        $this->assertDatabaseHas('barbeiros', [
            'nome' => 'João Barbeiro'
        ]);
    }

    public function test_can_update_barbeiro(): void
    {
        $barbeiro = Barbeiro::create([
            'nome' => 'Barbeiro Teste',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ]);

        $data = [
            'nome' => 'Barbeiro Editado',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ];

        $response = $this->actingAs($this->user)->put(route('barbeiros.update', $barbeiro), $data);
        $response->assertRedirect(route('barbeiros.index'));

        $this->assertDatabaseHas('barbeiros', [
            'id' => $barbeiro->id,
            'nome' => 'Barbeiro Editado'
        ]);
    }

    public function test_can_soft_delete_barbeiro(): void
    {
        $barbeiro = Barbeiro::create([
            'nome' => 'Barbeiro Teste',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ]);

        $response = $this->actingAs($this->user)->delete(route('barbeiros.destroy', $barbeiro));
        $response->assertRedirect(route('barbeiros.index'));

        $this->assertSoftDeleted('barbeiros', [
            'id' => $barbeiro->id
        ]);
    }

    public function test_can_restore_barbeiro(): void
    {
        $barbeiro = Barbeiro::create([
            'nome' => 'Barbeiro Deletado',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ]);
        $barbeiro->delete();

        $response = $this->actingAs($this->user)->post(route('barbeiros.restore', $barbeiro->id));
        $response->assertRedirect(route('barbeiros.index'));

        $this->assertNotSoftDeleted('barbeiros', [
            'id' => $barbeiro->id
        ]);
    }

    public function test_can_force_delete_barbeiro(): void
    {
        $barbeiro = Barbeiro::create([
            'nome' => 'Barbeiro Deletado',
            'telefone' => '11999999999',
            'especialidades' => 'Corte',
            'inicio_trabalho' => '08:00',
            'fim_trabalho' => '18:00',
            'ativo' => true
        ]);
        $barbeiro->delete();

        $response = $this->actingAs($this->user)->delete(route('barbeiros.forceDelete', $barbeiro->id));
        $response->assertRedirect(route('barbeiros.index'));

        $this->assertDatabaseMissing('barbeiros', [
            'id' => $barbeiro->id
        ]);
    }
}
