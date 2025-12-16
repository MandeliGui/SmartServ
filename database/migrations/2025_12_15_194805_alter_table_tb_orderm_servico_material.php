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
        Schema::table('tb_ordem_servico_material', function (Blueprint $table) {

            $table->text('descricao')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_ordem_servico_material', function (Blueprint $table) {

            $table->dropColumn('descricao');

        });
    }
};
