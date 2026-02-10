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
        Schema::table('tb_pessoas', function (Blueprint $table) {

            $table->string('telefone')->nullable()->change();
            $table->string('cpfCnpj')->nullable()->change();
            $table->string('tipoPessoa')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pessoas', function (Blueprint $table) {

            $table->string('telefone')->change();
            $table->string('cpfCnpj')->change();
            $table->string('tipoPessoa')->change();

        });
    }
};
