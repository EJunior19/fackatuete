<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sifen_logs', function (Blueprint $table) {
            $table->id();

            $table->string('accion'); // envio_lote, consulta_lote, firma, xml, etc.
            $table->string('resultado')->nullable(); // success, error

            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->text('error')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sifen_logs');
    }
};
