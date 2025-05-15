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
        Schema::table('orders', function(Blueprint $table){

            $table->dateTime('recibido_embarques_at')->nullable()->after('status_id');
            $table->date('entrega_programada_at')->nullable()->after('recibido_embarques_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function(Blueprint $table){

            $table->dropColumn('recibido_emabarques_at');
            $table->dropColumn('entrega_programada_at');

        });
    }
};
