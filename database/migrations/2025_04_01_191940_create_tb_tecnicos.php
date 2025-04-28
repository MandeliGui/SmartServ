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
        Schema::create('tb_tecnicos', function (Blueprint $table): void {
            $table->integer('idTecnico');
            $table->boolean("removido")->default(false);
            $table->foreign('idTecnico')->references('id')->on('tb_pessoas');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tecnicos');
    }
};
