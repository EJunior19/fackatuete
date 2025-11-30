<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('numeraciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade');

            $table->foreignId('timbrado_id')
                  ->nullable()
                  ->constrained('timbrados')
                  ->nullOnDelete();

            $table->foreignId('establecimiento_id')
                  ->nullable()
                  ->constrained('establecimientos')
                  ->nullOnDelete();

            $table->foreignId('punto_expedicion_id')
                  ->nullable()
                  ->constrained('puntos_expedicion')
                  ->nullOnDelete();

            // FE / NC / ND
            $table->string('tipo_documento', 2);

            $table->unsignedBigInteger('numero_inicial')->default(1);
            $table->unsignedBigInteger('numero_actual')->default(0);
            $table->unsignedBigInteger('numero_final')->nullable(); // opcional

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(
                [
                    'empresa_id',
                    'timbrado_id',
                    'establecimiento_id',
                    'punto_expedicion_id',
                    'tipo_documento',
                ],
                'numeracion_unica_por_contexto'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numeraciones');
    }
};
