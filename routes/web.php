<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Api\AlumnoApiController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::get('/alumnos/create', [AlumnoController::class, 'create'])->name('alumnos.create');
    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::get('/alumnos/eliminar/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

    Route::get('/programas', [ProgramaController::class, 'index'])->name('programas.index');
    Route::post('/programas', [ProgramaController::class, 'store'])->name('programas.store');

    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');

    Route::get('/reportes/deudas', [ReporteController::class, 'exportarDeudas'])->name('reportes.deudas');
    Route::post('/reportes/generar', [ReporteController::class, 'generarReporteAsync'])->name('reportes.generar');

    Route::get('/api/buscar-alumnos', [ReporteController::class, 'buscarAlumnosGlobal'])->name('api.buscar');
});

Route::get('/api/alumnos/{matricula}', [AlumnoApiController::class, 'show']);
Route::get('/api/alumnos/{matricula}/pagos', [AlumnoApiController::class, 'pagos']);

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

require __DIR__.'/auth.php';
