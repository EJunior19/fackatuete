<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_id');

            $table->string('numero_lote')->unique();
            $table->integer('cantidad')->default(0);

            $table->string('estado')->default('pendiente'); // pendiente / enviado / procesado
            $table->string('respuesta')->nullable();

            $table->dateTime('fecha_envio')->nullable();
            $table->dateTime('fecha_respuesta')->nullable();

            $table->longText('xml_enviado')->nullable();
            $table->longText('xml_respuesta')->nullable();

            $table->foreign('empresa_id')->references('id')->on('empresas')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
