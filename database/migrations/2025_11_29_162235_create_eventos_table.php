<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();

            // Relación con documento (opcional)
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->foreign('documento_id')->references('id')->on('documentos')->nullOnDelete();

            // Relación con lote (opcional)
            $table->unsignedBigInteger('lote_id')->nullable();
            $table->foreign('lote_id')->references('id')->on('lotes')->nullOnDelete();

            // Información del evento SET
            $table->string('codigo');          // Ej: 0300, 0361, 051, etc
            $table->string('tipo');            // Ej: lote_envio, lote_consulta, doc_error, anulacion
            $table->string('descripcion')->nullable();
            $table->text('mensaje')->nullable();
            $table->longText('xml')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eventos');
    }
};
