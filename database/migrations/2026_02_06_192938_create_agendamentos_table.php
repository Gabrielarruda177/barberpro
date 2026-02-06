<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente');
            $table->string('telefone_cliente');
            $table->foreignId('barbeiro_id')->constrained();
            $table->foreignId('servico_id')->constrained();
            $table->date('data');
            $table->time('horario');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['agendado', 'concluido', 'cancelado'])->default('agendado');
            $table->decimal('valor', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agendamentos');
    }
};