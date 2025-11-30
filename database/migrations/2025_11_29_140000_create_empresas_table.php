<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->unique();
            $table->string('razon_social');
            $table->string('nombre_fantasia')->nullable();
            $table->string('dv', 1);
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            // CERTIFICADOS DIGITALES
            $table->text('cert_publico')->nullable();
            $table->text('cert_privado')->nullable();
            $table->string('cert_password')->nullable();

            // CONFIGURACION SIFEN
            $table->enum('ambiente', ['test', 'prod'])->default('test');
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
