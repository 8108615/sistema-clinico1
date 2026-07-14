<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');


//rutas para el admin
Route::get('/dashboard', function (){ return redirect()->route('admin.index'); })->name('dashboard')->middleware('auth');


Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

//Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware('auth');
Route::post('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware('auth');

//Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware('auth');
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');
//Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth');
//Route::put('/admin/rol/{id}/update_permisos', [App\Http\Controllers\RoleController::class, 'updatePermisos'])->name('admin.roles.updatePermisos')->middleware('auth');
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');

//Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware('auth');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware('auth');
Route::post('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware('auth');
Route::post('/admin/usuario/{id}/restaurar', [App\Http\Controllers\UsuarioController::class, 'restaurar'])->name('admin.usuarios.restaurar')->middleware('auth');
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware('auth');
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth');
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware('auth');
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth');

//Rutas para Pacientes
Route::get('/admin/pacientes', [App\Http\Controllers\PacienteController::class, 'index'])->name('admin.pacientes.index')->middleware('auth');
Route::get('/admin/pacientes/create', [App\Http\Controllers\PacienteController::class, 'create'])->name('admin.pacientes.create')->middleware('auth');
Route::post('/admin/pacientes', [App\Http\Controllers\PacienteController::class, 'store'])->name('admin.pacientes.store')->middleware('auth');
Route::get('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'show'])->name('admin.pacientes.show')->middleware('auth');
Route::get('/admin/pacientes/{id}/edit', [App\Http\Controllers\PacienteController::class, 'edit'])->name('admin.pacientes.edit')->middleware('auth');
Route::put('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'update'])->name('admin.pacientes.update')->middleware('auth');
Route::get('/admin/pacientes/{id}/confirm-delete', [App\Http\Controllers\PacienteController::class, 'confirmDelete'])->name('admin.pacientes.confirmDelete')->middleware('auth');
Route::delete('/admin/pacientes/{id}', [App\Http\Controllers\PacienteController::class, 'destroy'])->name('admin.pacientes.destroy')->middleware('auth');

//Rutas para consultorios
Route::get('/admin/consultorios', [App\Http\Controllers\ConsultorioController::class, 'index'])->name('admin.consultorios.index')->middleware('auth');
Route::get('/admin/consultorios/create', [App\Http\Controllers\ConsultorioController::class, 'create'])->name('admin.consultorios.create')->middleware('auth');
Route::post('/admin/consultorios/create', [App\Http\Controllers\ConsultorioController::class, 'store'])->name('admin.consultorios.store')->middleware('auth');
Route::get('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'show'])->name('admin.consultorios.show')->middleware('auth');
Route::get('/admin/consultorios/{id}/edit', [App\Http\Controllers\ConsultorioController::class, 'edit'])->name('admin.consultorios.edit')->middleware('auth');
Route::put('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'update'])->name('admin.consultorios.update')->middleware('auth');
Route::delete('/admin/consultorios/{id}', [App\Http\Controllers\ConsultorioController::class, 'destroy'])->name('admin.consultorios.destroy')->middleware('auth');

//Rutas para consultas
Route::get('/admin/consultas', [App\Http\Controllers\ConsultaController::class, 'index'])->name('admin.consultas.index')->middleware('auth');
Route::get('/admin/consultas/create', [App\Http\Controllers\ConsultaController::class, 'create'])->name('admin.consultas.create')->middleware('auth');
Route::post('/admin/consultas', [App\Http\Controllers\ConsultaController::class, 'store'])->name('admin.consultas.store')->middleware('auth');
Route::get('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'show'])->name('admin.consultas.show')->middleware('auth');
Route::get('/admin/consultas/{id}/edit', [App\Http\Controllers\ConsultaController::class, 'edit'])->name('admin.consultas.edit')->middleware('auth');
Route::put('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'update'])->name('admin.consultas.update')->middleware('auth');
Route::get('/admin/consultas/{id}/ticket', [App\Http\Controllers\ConsultaController::class, 'ticket'])->name('admin.consultas.ticket')->middleware('auth');
Route::delete('/admin/consultas/{id}', [App\Http\Controllers\ConsultaController::class, 'destroy'])->name('admin.consultas.destroy')->middleware('auth');

//Rutas para consultas
Route::get('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'index'])->name('admin.laboratorios.index')->middleware('auth');
Route::get('/admin/laboratorios/create', [App\Http\Controllers\LaboratorioController::class, 'create'])->name('admin.laboratorios.create')->middleware('auth');
Route::post('/admin/laboratorios', [App\Http\Controllers\LaboratorioController::class, 'store'])->name('admin.laboratorios.store')->middleware('auth');
Route::get('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'show'])->name('admin.laboratorios.show')->middleware('auth');
Route::get('/admin/laboratorios/{id}/edit', [App\Http\Controllers\LaboratorioController::class, 'edit'])->name('admin.laboratorios.edit')->middleware('auth');
Route::put('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'update'])->name('admin.laboratorios.update')->middleware('auth');
Route::delete('/admin/laboratorios/{id}', [App\Http\Controllers\LaboratorioController::class, 'destroy'])->name('admin.laboratorios.destroy')->middleware('auth');

require __DIR__.'/settings.php';
