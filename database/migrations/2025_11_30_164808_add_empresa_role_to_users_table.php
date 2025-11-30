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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
        $table->string('role')->default('admin')->after('email');
        $table->boolean('status')->default(true)->after('role');

        $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['empresa_id']);
        $table->dropColumn(['empresa_id', 'role', 'status']);
    });
}

};
