<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->bigInteger('erp_sale_id')
                ->nullable()
                ->after('cliente_id')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('documentos', function (Blueprint $table) {
            $table->dropColumn('erp_sale_id');
        });
    }
};

