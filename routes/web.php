<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Redirigir /home a /reservations
    Route::get('/home', function () {
        return redirect()->route('reservations.index');
    })->name('home');

    // Rutas para salas
    Route::resource('rooms', RoomController::class);

    // Rutas para reservaciones
    Route::resource('reservations', ReservationController::class);

    // Ruta para exportar reservaciones a Excel
    Route::get('/reservations-export', [ReservationController::class, 'export'])->name('reservations.export');
});
