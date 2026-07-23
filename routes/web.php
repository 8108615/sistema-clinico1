<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

//rutas para el admin
Route::get('/dashboard', function (){ return redirect()->route('admin.index'); })->name('dashboard')->middleware('auth');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth', 'can:Ver ajustes');
Route::post('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth', 'can:Guardar ajustes');

// Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth','can:Ver listado de roles');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth', 'can:Ver formulario de creacion de rol');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth', 'can:Guardar rol');
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth', 'can:Ver datos del rol');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth', 'can:Ver formulario de edicion del rol');
Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth', 'can:Ver formulario de permisos del rol');
Route::put('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'guardarPermisos'])->name('admin.roles.guardar_permisos')->middleware('auth', 'can:Guardar permisos del rol');
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth', 'can:Actualizar rol');
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth', 'can:Eliminar rol');

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth', 'can:Ver listado de usuarios');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth', 'can:Ver formulario de creacion de usuario');
Route::post('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth', 'can:Guardar usuario');
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware('auth', 'can:Actualizar usuario');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth', 'can:Ver datos del usuario');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth', 'can:Ver formulario de edicion de usuario');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth', 'can:Actualizar usuario');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth', 'can:Eliminar usuario');

//Rutas para Pacientes
Route::get('/admin/pacientes', [App\Http\Controllers\PacienteController::class, 'index'])->name('admin.pacientes.index')->middleware('auth', 'can:Ver listado de pacientes');
Route::get('/admin/pacientes/create', [App\Http\Controllers\PacienteController::class, 'create'])->name('admin.pacientes.create')->middleware('auth', 'can:Ver formulario de creacion de paciente');
Route::post('/admin/pacientes', [App\Http\Controllers\PacienteController::class, 'store'])->name('admin.pacientes.store')->middleware('auth', 'can:Guardar paciente');
Route::get('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'show'])->name('admin.pacientes.show')->middleware('auth', 'can:Ver datos del paciente');
Route::get('/admin/pacientes/{id}/edit', [App\Http\Controllers\PacienteController::class, 'edit'])->name('admin.pacientes.edit')->middleware('auth', 'can:Ver formulario de edicion de paciente');
Route::put('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'update'])->name('admin.pacientes.update')->middleware('auth', 'can:Actualizar paciente');
Route::get('/admin/pacientes/{id}/confirm-delete', [App\Http\Controllers\PacienteController::class, 'confirmDelete'])->name('admin.pacientes.confirmDelete')->middleware('auth', 'can:Eliminar paciente');
Route::delete('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'destroy'])->name('admin.pacientes.destroy')->middleware('auth', 'can:Eliminar paciente');

//Rutas para consultorios
Route::get('/admin/consultorios', [App\Http\Controllers\ConsultorioController::class, 'index'])->name('admin.consultorios.index')->middleware('auth', 'can:Ver listado de consultorios');
Route::get('/admin/consultorios/create', [App\Http\Controllers\ConsultorioController::class, 'create'])->name('admin.consultorios.create')->middleware('auth', 'can:Ver formulario de creacion de consultorio');
Route::post('/admin/consultorios/create', [App\Http\Controllers\ConsultorioController::class, 'store'])->name('admin.consultorios.store')->middleware('auth', 'can:Guardar consultorio');
Route::get('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'show'])->name('admin.consultorios.show')->middleware('auth', 'can:Ver datos del consultorio');
Route::get('/admin/consultorios/{id}/edit', [App\Http\Controllers\ConsultorioController::class, 'edit'])->name('admin.consultorios.edit')->middleware('auth', 'can:Ver formulario de edicion de consultorio');
Route::put('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'update'])->name('admin.consultorios.update')->middleware('auth', 'can:Actualizar consultorio');
Route::delete('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'destroy'])->name('admin.consultorios.destroy')->middleware('auth', 'can:Eliminar consultorio');

//Rutas para consultas
Route::get('/admin/consultas', [App\Http\Controllers\ConsultaController::class, 'index'])->name('admin.consultas.index')->middleware('auth', 'can:Ver listado de consultas');
Route::get('/admin/consultas/create', [App\Http\Controllers\ConsultaController::class, 'create'])->name('admin.consultas.create')->middleware('auth', 'can:Ver formulario de creacion de consulta');
Route::post('/admin/consultas', [App\Http\Controllers\ConsultaController::class, 'store'])->name('admin.consultas.store')->middleware('auth', 'can:Guardar consulta');
Route::get('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'show'])->name('admin.consultas.show')->middleware('auth', 'can:Ver datos de la consulta');
Route::get('/admin/consultas/{id}/edit', [App\Http\Controllers\ConsultaController::class, 'edit'])->name('admin.consultas.edit')->middleware('auth', 'can:Ver formulario de edicion de consulta');
Route::put('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'update'])->name('admin.consultas.update')->middleware('auth', 'can:Actualizar consulta');
Route::get('/admin/consultas/{id}/ticket', [App\Http\Controllers\ConsultaController::class, 'ticket'])->name('admin.consultas.ticket')->middleware('auth', 'can:Ver ticket de consulta');
Route::delete('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'destroy'])->name('admin.consultas.destroy')->middleware('auth', 'can:Eliminar consulta');

//Rutas para laboratorios
Route::get('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'index'])->name('admin.laboratorios.index')->middleware('auth', 'can:Ver listado de laboratorios');
Route::get('/admin/laboratorios/create', [App\Http\Controllers\LaboratorioController::class, 'create'])->name('admin.laboratorios.create')->middleware('auth', 'can:Ver formulario de creacion de laboratorio');
Route::post('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'store'])->name('admin.laboratorios.store')->middleware('auth', 'can:Guardar laboratorio');
Route::get('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'show'])->name('admin.laboratorios.show')->middleware('auth', 'can:Ver datos del laboratorio');
Route::get('/admin/laboratorios/{id}/edit', [App\Http\Controllers\LaboratorioController::class, 'edit'])->name('admin.laboratorios.edit')->middleware('auth', 'can:Ver formulario de edicion de laboratorio');
Route::put('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'update'])->name('admin.laboratorios.update')->middleware('auth', 'can:Actualizar laboratorio');
Route::delete('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'destroy'])->name('admin.laboratorios.destroy')->middleware('auth', 'can:Eliminar laboratorio');

//Rutas para ordenes de laboratorio
Route::get('/admin/ordenlaboratorios', [App\Http\Controllers\OrdenLaboratorioController::class, 'index'])->name('admin.orden_laboratorios.index')->middleware('auth', 'can:Ver listado de ordenes de laboratorio');
Route::get('/admin/ordenlaboratorios/create', [App\Http\Controllers\OrdenLaboratorioController::class, 'create'])->name('admin.orden_laboratorios.create')->middleware('auth', 'can:Ver formulario de creacion de orden de laboratorio');
Route::post('/admin/ordenlaboratorios', [App\Http\Controllers\OrdenLaboratorioController::class, 'store'])->name('admin.orden_laboratorios.store')->middleware('auth', 'can:Guardar orden de laboratorio');
Route::get('/admin/ordenlaboratorios/{id}', [App\Http\Controllers\OrdenLaboratorioController::class, 'show'])->name('admin.orden_laboratorios.show')->middleware('auth', 'can:Ver datos de la orden de laboratorio');
Route::get('/admin/ordenlaboratorios/{id}/edit', [App\Http\Controllers\OrdenLaboratorioController::class, 'edit'])->name('admin.orden_laboratorios.edit')->middleware('auth', 'can:Ver formulario de edicion de orden de laboratorio');
Route::put('/admin/ordenlaboratorios/{id}', [App\Http\Controllers\OrdenLaboratorioController::class, 'update'])->name('admin.orden_laboratorios.update')->middleware('auth', 'can:Actualizar orden de laboratorio');
Route::delete('/admin/ordenlaboratorios/{id}', [App\Http\Controllers\OrdenLaboratorioController::class, 'destroy'])->name('admin.orden_laboratorios.destroy')->middleware('auth', 'can:Eliminar orden de laboratorio');
Route::get('/admin/ordenlaboratorios/{id}/imprimir', [App\Http\Controllers\OrdenLaboratorioController::class, 'imprimir'])->name('admin.orden_laboratorios.imprimir')->middleware('auth', 'can:Imprimir orden de laboratorio');

//Rutas para Cajas
Route::get('/admin/cajas', [App\Http\Controllers\CajaController::class, 'index'])->name('admin.cajas.index')->middleware('auth', 'can:Ver listado de cajas');
Route::post('/admin/cajas', [App\Http\Controllers\CajaController::class, 'store'])->name('admin.cajas.store')->middleware('auth', 'can:Guardar caja');
Route::get('/admin/cajas/{id}/edit', [App\Http\Controllers\CajaController::class, 'edit'])->name('admin.cajas.edit')->middleware('auth', 'can:Ver formulario de edicion de caja');
Route::put('/admin/cajas/{id}', [App\Http\Controllers\CajaController::class, 'update'])->name('admin.cajas.update')->middleware('auth', 'can:Actualizar caja');
Route::delete('/admin/cajas/{id}', [App\Http\Controllers\CajaController::class, 'destroy'])->name('admin.cajas.destroy')->middleware('auth', 'can:Eliminar caja');
Route::get('/admin/cajas/{id}/pdf', [App\Http\Controllers\CajaController::class, 'pdf'])->name('admin.cajas.pdf')->middleware('auth', 'can:Generar PDF de caja');

//Rutas para Categorias
Route::get('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('admin.categorias.index')->middleware('auth', 'can:Ver listado de categorias');
Route::get('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class, 'create'])->name('admin.categorias.create')->middleware('auth', 'can:Ver formulario de creacion de categoria');
Route::post('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'store'])->name('admin.categorias.store')->middleware('auth', 'can:Guardar categoria');
Route::get('/admin/categorias/{id}/edit', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('admin.categorias.edit')->middleware('auth', 'can:Ver formulario de edicion de categoria');
Route::put('/admin/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('admin.categorias.update')->middleware('auth', 'can:Actualizar categoria');
Route::delete('/admin/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('admin.categorias.destroy')->middleware('auth', 'can:Eliminar categoria');

//Rutas para Insumos
Route::get('/admin/insumos', [App\Http\Controllers\InsumoController::class, 'index'])->name('admin.insumos.index')->middleware('auth', 'can:Ver listado de insumos');
Route::get('/admin/insumos/create', [App\Http\Controllers\InsumoController::class, 'create'])->name('admin.insumos.create')->middleware('auth', 'can:Ver formulario de creacion de insumo');
Route::post('/admin/insumos', [App\Http\Controllers\InsumoController::class, 'store'])->name('admin.insumos.store')->middleware('auth', 'can:Guardar insumo');
Route::get('/admin/insumos/{id}/edit', [App\Http\Controllers\InsumoController::class, 'edit'])->name('admin.insumos.edit')->middleware('auth', 'can:Ver formulario de edicion de insumo');
Route::put('/admin/insumos/{id}', [App\Http\Controllers\InsumoController::class, 'update'])->name('admin.insumos.update')->middleware('auth', 'can:Actualizar insumo');
Route::delete('/admin/insumos/{id}', [App\Http\Controllers\InsumoController::class, 'destroy'])->name('admin.insumos.destroy')->middleware('auth', 'can:Eliminar insumo');

//Rutas para Historias Clinicas
Route::get('/admin/historias-clinicas', [App\Http\Controllers\HistoriaClinicaController::class, 'index'])->name('admin.historias_clinicas.index')->middleware('auth', 'can:Ver listado de historias clinicas');
Route::get('/admin/historias-clinicas/create', [App\Http\Controllers\HistoriaClinicaController::class, 'create'])->name('admin.historias_clinicas.create')->middleware('auth', 'can:Ver formulario de creacion de historia clinica');
Route::post('/admin/historias-clinicas', [App\Http\Controllers\HistoriaClinicaController::class, 'store'])->name('admin.historias_clinicas.store')->middleware('auth', 'can:Guardar historia clinica');
Route::get('/admin/historias-clinicas/{id}/edit', [App\Http\Controllers\HistoriaClinicaController::class, 'edit'])->name('admin.historias_clinicas.edit')->middleware('auth', 'can:Ver formulario de edicion de historia clinica');
Route::put('/admin/historias-clinicas/{id}', [App\Http\Controllers\HistoriaClinicaController::class, 'update'])->name('admin.historias_clinicas.update')->middleware('auth', 'can:Actualizar historia clinica');
Route::delete('/admin/historias-clinicas/{id}', [App\Http\Controllers\HistoriaClinicaController::class, 'destroy'])->name('admin.historias_clinicas.destroy')->middleware('auth', 'can:Eliminar historia clinica');
Route::get('/admin/historias-clinicas/papelera', [App\Http\Controllers\HistoriaClinicaController::class, 'trashed'])->name('admin.historias_clinicas.trashed')->middleware('auth', 'can:Ver papelera de historias clinicas');
Route::patch('/admin/historias-clinicas/{id}/restaurar', [App\Http\Controllers\HistoriaClinicaController::class, 'restore'])->name('admin.historias_clinicas.restore')->middleware('auth', 'can:Restaurar historia clinica');
Route::get('/admin/historias-clinicas/{id}/pdf', [App\Http\Controllers\HistoriaClinicaController::class, 'pdf'])->name('admin.historias_clinicas.pdf')->middleware('auth', 'can:Generar PDF de historia clinica');

// Rutas para Recetas Médicas
Route::get('/admin/recetas', [App\Http\Controllers\RecetaMedicaController::class, 'index'])->name('admin.recetas.index')->middleware('auth', 'can:Ver listado de recetas');
Route::get('/admin/recetas/create', [App\Http\Controllers\RecetaMedicaController::class, 'create'])->name('admin.recetas.create')->middleware('auth', 'can:Ver formulario de creacion de receta');
Route::post('/admin/recetas', [App\Http\Controllers\RecetaMedicaController::class, 'store'])->name('admin.recetas.store')->middleware('auth', 'can:Guardar receta');
Route::get('/admin/recetas/{id}', [App\Http\Controllers\RecetaMedicaController::class, 'show'])->name('admin.recetas.show')->middleware('auth', 'can:Ver datos de la receta');
Route::get('/admin/recetas/{id}/edit', [App\Http\Controllers\RecetaMedicaController::class, 'edit'])->name('admin.recetas.edit')->middleware('auth', 'can:Ver formulario de edicion de receta');
Route::put('/admin/recetas/{id}', [App\Http\Controllers\RecetaMedicaController::class, 'update'])->name('admin.recetas.update')->middleware('auth', 'can:Actualizar receta');
Route::get('/admin/recetas/papelera', [App\Http\Controllers\RecetaMedicaController::class, 'trashed'])->name('admin.recetas.trashed')->middleware('auth', 'can:Ver papelera de recetas');
Route::get('/admin/recetas/{id}/pdf', [App\Http\Controllers\RecetaMedicaController::class, 'pdf'])->name('admin.recetas.pdf')->middleware('auth', 'can:Generar PDF de receta');
Route::delete('/admin/recetas/{id}', [App\Http\Controllers\RecetaMedicaController::class, 'destroy'])->name('admin.recetas.destroy')->middleware('auth', 'can:Eliminar receta');
Route::patch('/admin/recetas/{id}/restaurar', [App\Http\Controllers\RecetaMedicaController::class, 'restore'])->name('admin.recetas.restore')->middleware('auth', 'can:Restaurar receta');

// Rutas para resultados de laboratorio
Route::get('/admin/resultados-laboratorio', [App\Http\Controllers\ResultadoLaboratorioController::class, 'index'])->name('admin.resultados_laboratorios.index')->middleware('auth', 'can:Ver listado de resultados de laboratorio');
Route::get('/admin/resultados-laboratorio/create', [App\Http\Controllers\ResultadoLaboratorioController::class, 'create'])->name('admin.resultados_laboratorios.create')->middleware('auth', 'can:Ver formulario de creacion de resultado de laboratorio');
Route::post('/admin/resultados-laboratorio', [App\Http\Controllers\ResultadoLaboratorioController::class, 'store'])->name('admin.resultados_laboratorios.store')->middleware('auth', 'can:Guardar resultado de laboratorio');
Route::get('/admin/resultados-laboratorio/{id}', [App\Http\Controllers\ResultadoLaboratorioController::class, 'show'])->name('admin.resultados_laboratorios.show')->middleware('auth', 'can:Ver datos del resultado de laboratorio');
Route::get('/admin/resultados-laboratorio/{id}/edit', [App\Http\Controllers\ResultadoLaboratorioController::class, 'edit'])->name('admin.resultados_laboratorios.edit')->middleware('auth', 'can:Ver formulario de edicion de resultado de laboratorio');
Route::put('/admin/resultados-laboratorio/{id}', [App\Http\Controllers\ResultadoLaboratorioController::class, 'update'])->name('admin.resultados_laboratorios.update')->middleware('auth', 'can:Actualizar resultado de laboratorio');
Route::delete('/admin/resultados-laboratorio/{id}', [App\Http\Controllers\ResultadoLaboratorioController::class, 'destroy'])->name('admin.resultados_laboratorios.destroy')->middleware('auth', 'can:Eliminar resultado de laboratorio');
Route::get('/admin/resultados-laboratorio/{id}/imprimir', [App\Http\Controllers\ResultadoLaboratorioController::class, 'imprimir'])->name('admin.resultados_laboratorios.imprimir')->middleware('auth', 'can:Imprimir resultado de laboratorio');

require __DIR__.'/settings.php';
