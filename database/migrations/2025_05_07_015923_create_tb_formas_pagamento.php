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
        Schema::create('tb_formas_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->string('removido')->default(false);
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_formas_pagamento');
    }
};
