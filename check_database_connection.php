<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Verificar la conexión actual
    $connection = DB::connection()->getPdo();
    $driver = DB::connection()->getDriverName();
    $database = DB::connection()->getDatabaseName();
    
    echo "=== INFORMACIÓN DE CONEXIÓN A LA BASE DE DATOS ===\n";
    echo "Driver: " . $driver . "\n";
    echo "Base de datos: " . $database . "\n";
    echo "Host: " . config('database.connections.' . $driver . '.host') . "\n";
    echo "Puerto: " . config('database.connections.' . $driver . '.port') . "\n";
    echo "Usuario: " . config('database.connections.' . $driver . '.username') . "\n";
    echo "Conexión establecida correctamente.\n\n";
    
    // Verificar tablas y registros
    echo "=== VERIFICACIÓN DE TABLAS Y REGISTROS ===\n";
    
    // Usuarios
    $users = DB::table('users')->get();
    echo "Tabla 'users': " . count($users) . " registros\n";
    foreach ($users as $user) {
        echo "  - ID: {$user->id}, Nombre: {$user->name}, Email: {$user->email}, Rol: {$user->role}\n";
    }
    echo "\n";
    
    // Salas
    $rooms = DB::table('rooms')->get();
    echo "Tabla 'rooms': " . count($rooms) . " registros\n";
    foreach ($rooms as $room) {
        echo "  - ID: {$room->id}, Nombre: {$room->name}\n";
    }
    echo "\n";
    
    // Reservaciones
    $reservations = DB::table('reservations')
        ->join('users', 'users.id', '=', 'reservations.user_id')
        ->join('rooms', 'rooms.id', '=', 'reservations.room_id')
        ->select('reservations.*', 'users.name as user_name', 'rooms.name as room_name')
        ->get();
    
    echo "Tabla 'reservations': " . count($reservations) . " registros\n";
    foreach ($reservations as $reservation) {
        echo "  - ID: {$reservation->id}, Usuario: {$reservation->user_name}, Sala: {$reservation->room_name}, ";
        echo "Fecha: {$reservation->reservation_date}, Hora: {$reservation->reservation_time}, Estado: {$reservation->status}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
