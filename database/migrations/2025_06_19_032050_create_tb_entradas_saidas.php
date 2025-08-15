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
        Schema::create('tb_entradas_saidas', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo')->comment('1 - Entrada, 2 - Saída');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->decimal('valor_original', 10, 2);
            $table->integer('status')->default(1)->comment('1 - Pendente, 2 - Pago, 3 - Cancelado');
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->integer('quantidade_meses')->default(1);
            $table->string('descricao', 255)->nullable();

            $table->bigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('tb_categoria_entrada_saida');
            $table->foreignId('forma_pagamento_id')->nullable()->constrained('tb_formas_pagamento', 'id');

            $table->foreignId('ordem_servico_id')->nullable()->constrained('tb_ordem_servico', 'id');

            $table->foreignId('banco_id')->constrained('tb_bancos', 'id');

            $table->boolean('removido')->default(false);
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_entradas_saidas');
    }
};
