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
        Schema::create('tb_fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social', 255);
            $table->string('nome_fantasia', 255)->nullable();
            $table->string('atendente', 255)->nullable();
            $table->string('cnpj', 20)->nullable()->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('id_endereco');
            $table->foreign('id_endereco')->references('id')->on('tb_enderecos');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_fornecedores');
    }
};
