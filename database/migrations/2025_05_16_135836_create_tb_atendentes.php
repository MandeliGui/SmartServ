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
        Schema::create('tb_atendentes', function (Blueprint $table) {
            $table->integer('idAtendente');
            $table->boolean("removido")->default(false);
            $table->foreign('idAtendente')->references('id')->on('tb_pessoas');
            $table->foreignId('user_id')->constrained('users', 'id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_atendentes');
    }
};
