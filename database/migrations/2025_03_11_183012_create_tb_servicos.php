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
        Schema::create('tb_servicos', function (Blueprint $table): void {
            $table->id();
            $table->integer('codigo');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->boolean('removido')->default(false);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_servicos');
    }
};
