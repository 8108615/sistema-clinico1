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
        Schema::create('detalle_orden_laboratorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_laboratorio_id')->constrained('orden_laboratorios')->onDelete('cascade');
            $table->foreignId('laboratorio_id')->constrained('laboratorios')->onDelete('cascade');
            $table->decimal('precio', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_laboratorios');
    }
};
