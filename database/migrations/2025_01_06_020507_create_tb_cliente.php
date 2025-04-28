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
        Schema::create('tb_cliente', function (Blueprint $table): void {
            $table->integer("idCliente");
            $table->integer("idGrupo")->nullable();
            $table->boolean("removido")->default(false);
            $table->foreign("idCliente")->references("id")->on("tb_pessoas");
            $table->foreign("idGrupo")->references("id")->on("tb_grupo_cliente");
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_cliente');
    }
};
