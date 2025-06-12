<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['Orcamento', 'OrdemServico']);
            $table->date('dataAbertura')->default(now());
            $table->date('dataEntrega')->nullable();
            $table->enum('status', ['Pendente', 'EmAndamento', 'Finalizado', 'Cancelado']);
            $table->decimal('valorTotal', 10, 2)->default(0);
            $table->integer('idCliente');
            $table->integer('idTecnico')->nullable();
            $table->integer('idAtendente')->nullable();
            $table->foreign('idCliente')->references('idCliente')->on('tb_cliente');
            $table->foreign('idTecnico')->references('idTecnico')->on('tb_tecnicos');
            $table->foreign('idAtendente')->references('idAtendente')->on('tb_atendentes');


            $table->boolean('removido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_ordem_servico');
    }
};
