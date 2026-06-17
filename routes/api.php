<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlumnoApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alumnos/{matricula}', [AlumnoApiController::class, 'show']);
    Route::get('/alumnos/{matricula}/pagos', [AlumnoApiController::class, 'pagos']);
});
