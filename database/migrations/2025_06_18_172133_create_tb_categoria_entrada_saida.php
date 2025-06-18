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
        Schema::create('tb_categoria_entrada_saida', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->tinyInteger('tipo')->comment('1 - Entrada, 2 - Saída');
            $table->string('descricao', 255)->nullable();
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
        Schema::dropIfExists('tb_categoria_entrada_saida');
    }
};
