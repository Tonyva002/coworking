<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "Iniciando migración de reservaciones de SQLite a MySQL...\n";
    
    // Configurar conexión a SQLite
    $sqliteConfig = [
        'driver' => 'sqlite',
        'database' => __DIR__.'/database/database.sqlite',
        'prefix' => '',
    ];
    
    // Crear una conexión temporal a SQLite
    DB::purge('sqlite');
    config(['database.connections.sqlite' => $sqliteConfig]);
    
    // Obtener todos los usuarios de SQLite para mapear IDs
    $sqliteUsers = DB::connection('sqlite')->table('users')->get();
    $userMap = [];
    
    echo "Mapeando usuarios de SQLite a MySQL...\n";
    foreach ($sqliteUsers as $sqliteUser) {
        // Buscar el usuario correspondiente en MySQL por email
        $mysqlUser = User::where('email', $sqliteUser->email)->first();
        if ($mysqlUser) {
            $userMap[$sqliteUser->id] = $mysqlUser->id;
            echo "Usuario SQLite ID: {$sqliteUser->id} ({$sqliteUser->email}) mapeado a MySQL ID: {$mysqlUser->id}\n";
        } else {
            echo "Usuario SQLite ID: {$sqliteUser->id} ({$sqliteUser->email}) no encontrado en MySQL.\n";
        }
    }
    
    // Obtener todas las salas de SQLite para mapear IDs
    $sqliteRooms = DB::connection('sqlite')->table('rooms')->get();
    $roomMap = [];
    
    echo "\nMapeando salas de SQLite a MySQL...\n";
    foreach ($sqliteRooms as $sqliteRoom) {
        // Buscar la sala correspondiente en MySQL por nombre
        $mysqlRoom = Room::where('name', $sqliteRoom->name)->first();
        if ($mysqlRoom) {
            $roomMap[$sqliteRoom->id] = $mysqlRoom->id;
            echo "Sala SQLite ID: {$sqliteRoom->id} ({$sqliteRoom->name}) mapeada a MySQL ID: {$mysqlRoom->id}\n";
        } else {
            echo "Sala SQLite ID: {$sqliteRoom->id} ({$sqliteRoom->name}) no encontrada en MySQL.\n";
        }
    }
    
    // Obtener todas las reservaciones de SQLite
    $sqliteReservations = DB::connection('sqlite')->table('reservations')->get();
    
    echo "\nSe encontraron " . count($sqliteReservations) . " reservaciones en SQLite.\n";
    
    if (count($sqliteReservations) > 0) {
        // Cambiar a la conexión MySQL
        DB::purge('mysql');
        DB::reconnect('mysql');
        
        // Verificar si la tabla de reservaciones existe en MySQL
        if (Schema::connection('mysql')->hasTable('reservations')) {
            echo "Migrando reservaciones a MySQL...\n";
            
            // Migrar cada reservación
            foreach ($sqliteReservations as $reservation) {
                // Verificar si tenemos los mapeos necesarios
                if (!isset($userMap[$reservation->user_id])) {
                    echo "Error: No se encontró mapeo para el usuario ID: {$reservation->user_id} en la reservación ID: {$reservation->id}, omitiendo.\n";
                    continue;
                }
                
                if (!isset($roomMap[$reservation->room_id])) {
                    echo "Error: No se encontró mapeo para la sala ID: {$reservation->room_id} en la reservación ID: {$reservation->id}, omitiendo.\n";
                    continue;
                }
                
                // Verificar si la reservación ya existe en MySQL
                $exists = Reservation::where('reservation_date', $reservation->reservation_date)
                    ->where('reservation_time', $reservation->reservation_time)
                    ->where('user_id', $userMap[$reservation->user_id])
                    ->where('room_id', $roomMap[$reservation->room_id])
                    ->exists();
                
                if (!$exists) {
                    // Crear una nueva reservación en MySQL
                    $newReservation = new Reservation([
                        'user_id' => $userMap[$reservation->user_id],
                        'room_id' => $roomMap[$reservation->room_id],
                        'reservation_date' => $reservation->reservation_date,
                        'reservation_time' => $reservation->reservation_time,
                        'status' => $reservation->status,
                    ]);
                    
                    // Establecer timestamps si existen
                    if ($reservation->created_at) {
                        $newReservation->created_at = $reservation->created_at;
                    }
                    
                    if ($reservation->updated_at) {
                        $newReservation->updated_at = $reservation->updated_at;
                    }
                    
                    $newReservation->save();
                    
                    echo "Reservación SQLite ID: {$reservation->id} migrada correctamente a MySQL ID: {$newReservation->id}\n";
                } else {
                    echo "Reservación con mismos datos ya existe en MySQL, omitiendo SQLite ID: {$reservation->id}\n";
                }
            }
            
            echo "\nMigración de reservaciones completada exitosamente.\n";
        } else {
            echo "Error: La tabla 'reservations' no existe en la base de datos MySQL.\n";
        }
    } else {
        echo "No hay reservaciones para migrar.\n";
    }
    
} catch (Exception $e) {
    echo "Error durante la migración: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
