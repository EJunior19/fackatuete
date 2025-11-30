<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('puntos_expedicion', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade');

            $table->foreignId('establecimiento_id')
                  ->constrained('establecimientos')
                  ->onDelete('cascade');

            // Código SIFEN: 001, 002, etc.
            $table->string('codigo', 3);

            $table->string('descripcion')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(
                ['empresa_id', 'establecimiento_id', 'codigo'],
                'punto_unico_empresa_estab_codigo'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('puntos_expedicion');
    }
};
