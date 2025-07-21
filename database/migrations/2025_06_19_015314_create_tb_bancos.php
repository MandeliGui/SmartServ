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
        Schema::create('tb_bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('saldo_inicial', 10, 2)->default(0);
            $table->decimal('saldo', 10, 2)->default(0);

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
        Schema::dropIfExists('tb_bancos');
    }
};
