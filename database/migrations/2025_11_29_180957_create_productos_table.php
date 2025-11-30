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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();
            $table->string('descripcion');
            $table->string('categoria')->nullable();

            $table->string('unidad_medida')->default('UNI'); // unidad por defecto

            $table->decimal('precio_1', 12, 2)->default(0);
            $table->decimal('precio_2', 12, 2)->default(0);
            $table->decimal('precio_3', 12, 2)->default(0);

            $table->integer('iva')->default(10); // 5, 10 o 0

            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

};
