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
        Schema::create('unidad_chofer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->foreignId('chofer_id')->constrained('users')->onDelete('cascade');
            $table->unsignedInteger('uso_count')->default(1);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            $table->unique(['unidad_id', 'chofer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_chofer');
    }
};
