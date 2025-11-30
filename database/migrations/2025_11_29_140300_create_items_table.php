<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('documento_id');

            $table->string('descripcion');
            $table->string('codigo')->nullable();

            $table->decimal('cantidad', 14, 4)->default(1);
            $table->decimal('precio_unit', 18, 4)->default(0);
            $table->decimal('total', 18, 2)->default(0);

            $table->integer('iva')->default(10); // 10% – 5% – 0%

            $table->foreign('documento_id')->references('id')->on('documentos')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
