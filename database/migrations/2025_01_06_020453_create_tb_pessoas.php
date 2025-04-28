<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_pessoas', function (Blueprint $table): void {
            $table->integer("id")->autoIncrement();
            $table->string("nomeRazaoSocial");
            $table->string("nomeFantasia")->nullable();
            $table->string("telefone");
            $table->string("cpfCnpj")->unique();
            $table->string("email")->nullable()->unique();
            $table->date("dataNascimento")->nullable();
            $table->enum("tipoPessoa", ["PF", "PJ"]);
            $table->integer("idEndereco");
            $table->boolean("removido")->default(false);
            $table->foreign("idEndereco")->references("id")->on("tb_enderecos");
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pessoas');
    }
};
