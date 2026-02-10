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
        Schema::create('tb_contrato_servicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("idContrato")->constrained("tb_contratos", 'id');
            $table->integer("quantidade");
            $table->foreignId("idServico")->constrained("tb_servicos", 'id');
            $table->decimal("valorUnitario", 10, 2);
            $table->decimal("valorTotal", 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_contrato_servicos');
    }
};
