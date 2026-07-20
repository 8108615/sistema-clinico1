<?php

namespace Database\Seeders;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Seeder;

class HistoriaClinicaSeeder extends Seeder
{
    public function run(): void
    {
        // Traemos algunos datos existentes para asegurar que las relaciones funcionen
        $pacientes = Paciente::all();
        // Asumiendo que el rol 'Medico' es el que tiene la mayoría de los usuarios
        $medicos = User::role('Medico')->get(); 

        foreach ($pacientes as $index => $paciente) {
            // Creamos 2 historias por paciente para probar paginación
            for ($i = 1; $i <= 2; $i++) {
                HistoriaClinica::create([
                    'paciente_id' => $paciente->id,
                    'user_id'     => $medicos->random()->id, // Asigna un médico aleatorio
                    'fecha_atencion' => now()->subDays($index + $i),
                    'numero_historia' => 'HC-' . str_pad($paciente->id . $i, 5, '0', STR_PAD_LEFT),
                    'subjetivo'   => 'Paciente refiere dolor de cabeza leve.',
                    'objetivo'    => 'Presión arterial 120/80.',
                    'analisis'    => 'Cefalea tensional.',
                    'plan'        => 'Reposo y analgésicos.',
                    'estado'      => 'finalizado',
                ]);
            }
        }
    }
}