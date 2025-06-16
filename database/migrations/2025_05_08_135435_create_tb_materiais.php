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
        Schema::create('tb_materiais', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->enum('unidade', ['UN', 'KG', 'L', 'M', 'CM'])->default('UN');
            $table->decimal('valor', 10, 2)->default(0);
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
        Schema::dropIfExists('tb_materiais');
    }
};
