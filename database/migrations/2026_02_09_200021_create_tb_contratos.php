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
        Schema::create('tb_contratos', function (Blueprint $table) {
            $table->id();
            $table->string("id_cliente");
            $table->string("periodicidade");
            $table->string("valor");
            $table->string("status");
            $table->string("removido")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_contratos');
    }
};
