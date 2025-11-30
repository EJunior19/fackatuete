<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->foreignId('cliente_id')
                  ->nullable() // por si tenés docs viejos sin cliente
                  ->constrained('clientes')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');
        });
    }
};
