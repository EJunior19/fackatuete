<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->string('establecimiento', 3)->nullable();
            $table->string('punto_expedicion', 3)->nullable();
            $table->string('numero', 10)->nullable();
        });
    }

    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn(['establecimiento', 'punto_expedicion', 'numero']);
        });
    }

};
