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
    Schema::table('documentos', function (Blueprint $table) {

        if (!Schema::hasColumn('documentos', 'establecimiento')) {
            $table->string('establecimiento', 3)->nullable();
        }

        if (!Schema::hasColumn('documentos', 'punto_expedicion')) {
            $table->string('punto_expedicion', 3)->nullable();
        }

        if (!Schema::hasColumn('documentos', 'numero')) {
            $table->string('numero', 10)->nullable();
        }

        if (!Schema::hasColumn('documentos', 'fecha')) {
            $table->date('fecha')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'cliente_ruc')) {
            $table->string('cliente_ruc')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'cliente_dv')) {
            $table->string('cliente_dv')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'cliente_nombre')) {
            $table->string('cliente_nombre')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'total_gravado')) {
            $table->integer('total_gravado')->default(0);
        }

        if (!Schema::hasColumn('documentos', 'total_iva')) {
            $table->integer('total_iva')->default(0);
        }

        if (!Schema::hasColumn('documentos', 'total')) {
            $table->integer('total')->default(0);
        }

        if (!Schema::hasColumn('documentos', 'cdc')) {
            $table->string('cdc')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'xml_generado')) {
            $table->longText('xml_generado')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'xml_firmado')) {
            $table->longText('xml_firmado')->nullable();
        }

        if (!Schema::hasColumn('documentos', 'estado_sifen')) {
            $table->string('estado_sifen')->default('pendiente');
        }
    });
}

public function down()
{
    Schema::table('documentos', function (Blueprint $table) {
        $table->dropColumn([
            'establecimiento',
            'punto_expedicion',
            'numero',
            'fecha',
            'cliente_ruc',
            'cliente_dv',
            'cliente_nombre',
            'total_gravado',
            'total_iva',
            'total',
            'cdc',
            'xml_generado',
            'xml_firmado',
            'estado_sifen',
        ]);
    });
}
    
};
