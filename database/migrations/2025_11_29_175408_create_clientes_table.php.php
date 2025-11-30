<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // Datos fiscales
            $table->string('ruc', 20);
            $table->string('dv', 5)->nullable();

            // Datos de identificación
            $table->string('razon_social', 200);

            // Contacto
            $table->string('telefono', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('direccion', 200)->nullable();

            // Metadata
            $table->boolean('activo')->default(true);

            $table->timestamps();

            // Índices recomendados
            $table->index(['ruc']);
            $table->index(['razon_social']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
