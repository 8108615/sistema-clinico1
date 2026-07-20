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
        Schema::create('historia_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('fecha_atencion')->useCurrent();
            $table->string('numero_historia')->unique();
            $table->text('subjetivo');
            $table->text('objetivo');
            $table->text('analisis');
            $table->text('plan');
            $table->json('signos_vitales')->nullable();
            $table->enum('estado', ['borrador', 'finalizado', 'anulado'])->default('borrador');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_clinicas');
    }
};
