<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('timbrado_id')->constrained('timbrados')->cascadeOnDelete();

            // Datos principales del documento
            $table->string('tipo_documento', 5); // FE, NCR, NDE, etc.
            $table->string('cdc', 44)->nullable()->unique();
            $table->dateTime('fecha_emision')->nullable();

            // Estado del documento
            $table->string('estado_sifen')->default('pendiente'); // pendiente, enviado, aprobado, rechazado
            $table->string('resultado')->nullable();
            $table->string('numero_lote')->nullable();

            // Datos del receptor
            $table->string('receptor_ruc')->nullable();
            $table->string('receptor_razon_social')->nullable();

            // Totales
            $table->decimal('total_gravada_10', 18, 2)->default(0);
            $table->decimal('total_gravada_5', 18, 2)->default(0);
            $table->decimal('total_exenta', 18, 2)->default(0);
            $table->decimal('total_iva', 18, 2)->default(0);
            $table->decimal('total_general', 18, 2)->default(0);

            // XML
            $table->longText('xml_generado')->nullable();
            $table->longText('xml_firmado')->nullable();
            $table->longText('xml_enviado')->nullable();
            $table->longText('xml_respuesta')->nullable();

            // Mensajes de SIFEN
            $table->text('mensaje_sifen')->nullable();
            $table->text('codigo_respuesta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
