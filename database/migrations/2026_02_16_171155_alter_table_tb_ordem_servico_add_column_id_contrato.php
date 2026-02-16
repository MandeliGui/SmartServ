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
        Schema::table('tb_ordem_servico', function (Blueprint $table) {
            $table->foreignId('contratoId')->nullable()->after('idAtendente')->constrained('tb_contratos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_ordem_servico', function (Blueprint $table) {
            $table->dropForeign(['contratoId']);
            $table->dropColumn('contrato_id');
        });
    }
};
