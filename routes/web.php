<?php

use App\Http\Controllers\CorreoController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\PreguntaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EncuestaController::class, 'index'])->name('home');
Route::get('/editar-preguntas/{id}', [EncuestaController::class, 'editPreguntas'])
    ->name('form-preguntas-encuesta')
    ->whereNumber('id');
Route::get('/crear-encuesta/{grupo}', [EncuestaController::class, 'create'])
    ->name('form-crear-encuesta')
    ->whereNumber('grupo');
Route::post('/crear-encuesta/{grupo}', [EncuestaController::class, 'store'])
    ->name('crear-encuesta')
    ->whereNumber('grupo');
Route::get('/editar-encuesta/{id}', [EncuestaController::class, 'edit'])
    ->name('editar-encuesta')
    ->whereNumber('id');
Route::put('/editar-encuesta/{id}', [EncuestaController::class, 'update'])
    ->name('actualizar-encuesta')
    ->whereNumber('id');
Route::delete('/eliminar-encuesta/{id}', [EncuestaController::class, 'destroy'])
    ->name('eliminar-encuesta')
    ->whereNumber('id');

Route::post('/crear-pregunta', [PreguntaController::class, 'store'])->name('crear-pregunta');
Route::delete('/eliminar-pregunta/{id}', [PreguntaController::class, 'destroy'])
    ->name('eliminar-pregunta')
    ->whereNumber('id');
Route::put('editar-pregunta/{id}', [PreguntaController::class, 'update'])
    ->name('editar-pregunta')
    ->whereNumber('id');

Route::get('correos/{id}', [CorreoController::class, 'index'])
    ->name('correos-encuesta')
    ->whereNumber('id');

// Route::middleware(['auth', 'verified'])->group(function () {
//    Route::view('dashboard', 'dashboard')->name('dashboard');
// });

// require __DIR__.'/settings.php';
