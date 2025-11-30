<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                  ->constrained('empresas')
                  ->onDelete('cascade');

            // Código SIFEN: 001, 002, etc.
            $table->string('codigo', 3);

            $table->string('descripcion')->nullable();
            $table->string('direccion')->nullable();

            $table->boolean('activo')->default(true);

            $table->timestamps();

            $table->unique(['empresa_id', 'codigo'], 'establecimiento_unico_empresa_codigo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
