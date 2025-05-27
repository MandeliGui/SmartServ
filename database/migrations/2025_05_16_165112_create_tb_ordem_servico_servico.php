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
        Schema::create('tb_ordem_servico_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idOrdemServico')->constrained('tb_ordem_servico', 'id');
            $table->foreignId('idServico')->constrained('tb_servicos', 'id');
            $table->integer('quantidade')->default(1);
            $table->decimal('valorUnitario', 10, 2)->default(0);
            $table->decimal('valorTotal', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_ordem_servico_servico');
    }
};
