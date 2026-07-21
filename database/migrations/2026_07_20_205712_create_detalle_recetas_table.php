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
        Schema::create('detalle_recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_medica_id')->constrained('receta_medicas')->onDelete('cascade');
            $table->string('medicamento'); // Nombre del fármaco (ej. Paracetamol 500mg)
            $table->string('dosis');        // Ej. 1 tableta cada 8 horas
            $table->string('duracion');     // Ej. 5 días
            $table->text('indicaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_recetas');
    }
};
