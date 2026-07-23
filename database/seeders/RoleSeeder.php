<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creación de Roles
        $super_admin = Role::create(['name' => 'SUPER ADMIN', 'guard_name' => 'web']);
        $admin = Role::create(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $medico = Role::create(['name' => 'MEDICO', 'guard_name' => 'web']);

        // ==========================================
        // 1. Permisos para Roles
        // ==========================================
        Permission::create(['name' => 'Ver listado de roles'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Ver formulario de creacion de rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Guardar rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Ver datos del rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Ver formulario de edicion del rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Actualizar rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Eliminar rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Ver formulario de permisos del rol'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Guardar permisos del rol'])->syncRoles([$super_admin]);

        // ==========================================
        // 2. Permisos para Pacientes
        // ==========================================
        Permission::create(['name' => 'Ver listado de pacientes'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de paciente'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar paciente'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver datos del paciente'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de paciente'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar paciente'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar paciente'])->syncRoles([$super_admin]);

        // ==========================================
        // 3. Permisos para Usuarios
        // ==========================================
        Permission::create(['name' => 'Ver listado de usuarios'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de creacion de usuario'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar usuario'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver datos del usuario'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de edicion de usuario'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar usuario'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar usuario'])->syncRoles([$super_admin]);

        // ==========================================
        // 4. Permisos para Consultorios
        // ==========================================
        Permission::create(['name' => 'Ver listado de consultorios'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de creacion de consultorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar consultorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver datos del consultorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de edicion de consultorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar consultorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar consultorio'])->syncRoles([$super_admin]);

        // ==========================================
        // 5. Permisos para Consultas
        // ==========================================
        Permission::create(['name' => 'Ver listado de consultas'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de consulta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Guardar consulta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver datos de la consulta'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de consulta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Actualizar consulta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver ticket de consulta'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Eliminar consulta'])->syncRoles([$super_admin]);

        // ==========================================
        // 6. Permisos para Laboratorios y Órdenes
        // ==========================================
        Permission::create(['name' => 'Ver listado de laboratorios'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de laboratorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar laboratorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver datos del laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de laboratorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar laboratorio'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar laboratorio'])->syncRoles([$super_admin]);

        Permission::create(['name' => 'Ver listado de ordenes de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de orden de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Guardar orden de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver datos de la orden de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de orden de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Actualizar orden de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Imprimir orden de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Eliminar orden de laboratorio'])->syncRoles([$super_admin]);

        Permission::create(['name' => 'Ver listado de resultados de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de resultado de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Guardar resultado de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver datos del resultado de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de resultado de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Actualizar resultado de laboratorio'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Imprimir resultado de laboratorio'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Eliminar resultado de laboratorio'])->syncRoles([$super_admin]);

        // ==========================================
        // 7. Permisos para Cajas y Pagos
        // ==========================================
        Permission::create(['name' => 'Ver listado de cajas'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar caja'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de edicion de caja'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar caja'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Generar PDF de caja'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar caja'])->syncRoles([$super_admin]);

        // ==========================================
        // 8. Permisos para Categorías
        // ==========================================
        Permission::create(['name' => 'Ver listado de categorias'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de creacion de categoria'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar categoria'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de edicion de categoria'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar categoria'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar categoria'])->syncRoles([$super_admin]);

        // ==========================================
        // 9. Permisos para Insumos
        // ==========================================
        Permission::create(['name' => 'Ver listado de insumos'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de creacion de insumo'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Guardar insumo'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Ver formulario de edicion de insumo'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Actualizar insumo'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar insumo'])->syncRoles([$super_admin]);

        // ==========================================
        // 10. Permisos para Historias Clínicas
        // ==========================================
        Permission::create(['name' => 'Ver listado de historias clinicas'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de historia clinica'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Guardar historia clinica'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de historia clinica'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Actualizar historia clinica'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver papelera de historias clinicas'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Restaurar historia clinica'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Generar PDF de historia clinica'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Eliminar historia clinica'])->syncRoles([$super_admin]);

        // ==========================================
        // 11. Permisos para Recetas Médicas
        // ==========================================
        Permission::create(['name' => 'Ver listado de recetas'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de creacion de receta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Guardar receta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver datos de la receta'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Ver formulario de edicion de receta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Actualizar receta'])->syncRoles([$super_admin, $medico]);
        Permission::create(['name' => 'Ver papelera de recetas'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Generar PDF de receta'])->syncRoles([$super_admin, $admin, $medico]);
        Permission::create(['name' => 'Restaurar receta'])->syncRoles([$super_admin, $admin]);
        Permission::create(['name' => 'Eliminar receta'])->syncRoles([$super_admin]);

        // ==========================================
        // 12. Permisos para Ajustes
        // ==========================================
        Permission::create(['name' => 'Ver ajustes'])->syncRoles([$super_admin]);
        Permission::create(['name' => 'Guardar ajustes'])->syncRoles([$super_admin]);
    }
}
