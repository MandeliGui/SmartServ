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
        Schema::table('tb_entradas_saidas', function (Blueprint $table) {

            $table->foreignId('id_fornecedor')->nullable()->constrained('tb_fornecedores', 'id');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_entradas_saidas', function (Blueprint $table) {
            $table->dropForeign(['id_fornecedor']);
            $table->dropColumn('id_fornecedor');
        });
    }
};
