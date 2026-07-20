<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Consultorio;
use App\Models\Laboratorio;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $this->call([
            RoleSeeder::class,
        ]);

        User::create([
            'name' => 'Erick Fernando Morales Gil',
            'email' => 'erick@gmail.com',
            'tipo_documento' => 'cedula de identidad',
            'numero_documento' => '8108615',
            'celular' => '76658531',
            'direccion' => 'Av Cumavi',
            'fecha_nacimiento' => '1991-12-20',
            'genero' => 'Masculino',
            'foto_perfil' => null,
            'estado' => 'Activo',
            'password' => bcrypt('12345678'),
        ])->assignRole('SUPER ADMIN');

        User::create([
            'name' => 'Anahi Morales',
            'email' => 'anahi@gmail.com',
            'tipo_documento' => 'cedula de identidad',
            'numero_documento' => '8111111',
            'celular' => '75555551',
            'direccion' => 'Av Cumavi barrio San juan Calle 5',
            'fecha_nacimiento' => '1996-04-13',
            'genero' => 'Femenino',
            'foto_perfil' => null,
            'estado' => 'ACTIVO',
            'password' => bcrypt('12345678'),
        ])->assignRole('ADMINISTRADOR');


        User::create([
            'name' => 'Sergio Gonzales',
            'email' => 'sergio@gmail.com',
            'tipo_documento' => 'cedula de identidad',
            'numero_documento' => '8111112',
            'celular' => '75555552',
            'direccion' => 'villa primero de mayo calle 7 nro 60',
            'fecha_nacimiento' => '1987-04-28',
            'genero' => 'Masculino',
            'foto_perfil' => null,
            'estado' => 'ACTIVO',
            'password' => bcrypt('12345678'),
        ])->assignRole('MEDICO');

        Ajuste::create([
            'nombre' => 'CLINICA GONZALES',
            'descripcion' => 'Sistema Clinico',
            'direccion' => 'Villa primero de mayo',
            'telefono' => '76658532',
            'email' => 'erickfer@gmail.com',
            'divisa' => 'Bs',
            'logo' => null,
            'web' => 'https://www.erick.com',
        ]);

        Paciente::create([
            'nombres' => 'Mario',
            'apellidos' => 'Perez',
            'ci' => '8111112',
            'fecha_nacimiento' => '1992-01-20',
            'genero' => 'Masculino',
            'celular' => '70000001',
            'correo' => 'mario@gmail.com',
            'direccion' => 'Av Päragua',
            'grupo_sanguineo' => 'O+',
            'peso' => '65',
            'talla' => '170',
            'alergias' => 'Ninguno',
            'contacto_emergencia' => 'Juan Perez',
            'parentesco_emergencia' => 'PADRE',
            'observaciones' => 'Ninguno',
            'estado' => 'ACTIVO',
        ]);

        Paciente::create([
            'nombres' => 'Maria',
            'apellidos' => 'Mamani',
            'ci' => '8111113',
            'fecha_nacimiento' => '1996-01-05',
            'genero' => 'Femenino',
            'celular' => '70000002',
            'correo' => 'maria@gmail.com',
            'direccion' => 'Av Banzer',
            'grupo_sanguineo' => 'O+',
            'peso' => '55',
            'talla' => '150',
            'alergias' => 'Ninguno',
            'contacto_emergencia' => 'Rosa Mamani',
            'parentesco_emergencia' => 'HERMANA',
            'observaciones' => 'Ninguno',
            'estado' => 'ACTIVO',
        ]);

        Paciente::create([
            'nombres' => 'Darwin',
            'apellidos' => 'Beier',
            'ci' => '8111114',
            'fecha_nacimiento' => '1988-01-05',
            'genero' => 'Masculino',
            'celular' => '70000003',
            'correo' => 'darwin@gmail.com',
            'direccion' => '3pasos al Frente',
            'grupo_sanguineo' => 'O+',
            'peso' => '95',
            'talla' => '178',
            'alergias' => 'Ninguno',
            'contacto_emergencia' => 'Arturo Beie',
            'parentesco_emergencia' => 'HERMANO',
            'observaciones' => 'Ninguno',
            'estado' => 'ACTIVO',
        ]);

        Paciente::create([
            'nombres' => 'Ruth',
            'apellidos' => 'Alvares',
            'ci' => '8111115',
            'fecha_nacimiento' => '1998-01-05',
            'genero' => 'Femenino',
            'celular' => '70000004',
            'correo' => 'ruth@gmail.com',
            'direccion' => 'doble via la guardia',
            'grupo_sanguineo' => 'O+',
            'peso' => '80',
            'talla' => '160',
            'alergias' => 'Ninguno',
            'contacto_emergencia' => 'Elva Martines',
            'parentesco_emergencia' => 'MADRE',
            'observaciones' => 'Ninguno',
            'estado' => 'ACTIVO',
        ]);

        Consultorio::create([
            'nombre' => 'PEDIATRIA',
            'ubicacion' => 'A-01',
            'telefono' => '75555551',
            'especialidad' => 'PEDIATRIA',
            'capacidad_consultas' => '5',
            'estado' => 'ACTIVO',
        ]);

        Consultorio::create([
            'nombre' => 'GINECOLOGIA',
            'ubicacion' => 'A-02',
            'telefono' => '75555552',
            'especialidad' => 'GINECOLOGIA',
            'capacidad_consultas' => '7',
            'estado' => 'ACTIVO',
        ]);

        Consultorio::create([
            'nombre' => 'MEDICO GENERAL',
            'ubicacion' => 'A-03',
            'telefono' => '75555553',
            'especialidad' => 'MEDICO GENERAL',
            'capacidad_consultas' => '10',
            'estado' => 'ACTIVO',
        ]);

        Consultorio::create([
            'nombre' => 'ENFERMERIA',
            'ubicacion' => 'A-04',
            'telefono' => '75555554',
            'especialidad' => 'ENFERMERIA',
            'capacidad_consultas' => '20',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'HEMOGRAMA',
            'codigo' => '1001',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => 'LLEGAR 30 minutos antes',
            'precio' => '50',
            'dias_entrega' => '1',
            'requiere_ayuno' => '1',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'GLUCOSA',
            'codigo' => '1002',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => 'LLEGAR 30 minutos antes',
            'precio' => '70',
            'dias_entrega' => '1',
            'requiere_ayuno' => '1',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'INMUNOGLOBULINA',
            'codigo' => '1003',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => 'LLEGAR 30 minutos antes',
            'precio' => '80',
            'dias_entrega' => '1',
            'requiere_ayuno' => '1',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'CUALITATIVA',
            'codigo' => '1004',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => '',
            'precio' => '40',
            'dias_entrega' => '0',
            'requiere_ayuno' => '0',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'GLICEMIA',
            'codigo' => '1005',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => '',
            'precio' => '80',
            'dias_entrega' => '1',
            'requiere_ayuno' => '1',
            'estado' => 'ACTIVO',
        ]);

        Laboratorio::create([
            'nombre' => 'COLESTEROL',
            'codigo' => '1006',
            'categoria' => 'ANALISIS DE SANGRE',
            'descripcion' => '',
            'precio' => '65',
            'dias_entrega' => '1',
            'requiere_ayuno' => '1',
            'estado' => 'ACTIVO',
        ]);
    }

}
