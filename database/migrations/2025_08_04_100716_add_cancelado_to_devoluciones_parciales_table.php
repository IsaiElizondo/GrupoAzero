<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devoluciones_parciales', function (Blueprint $table) {
            
            $table->boolean('cancelado')->default(false)->after('file');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devoluciones_parciales', function (Blueprint $table) {
            
            $table->dropColumn('cancelado');

        });
    }
};
