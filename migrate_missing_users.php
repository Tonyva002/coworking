<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    echo "Iniciando migración de usuarios faltantes de SQLite a MySQL...\n";
    
    // Configurar conexión a SQLite
    $sqliteConfig = [
        'driver' => 'sqlite',
        'database' => __DIR__.'/database/database.sqlite',
        'prefix' => '',
    ];
    
    // Crear una conexión temporal a SQLite
    DB::purge('sqlite');
    config(['database.connections.sqlite' => $sqliteConfig]);
    
    // Obtener todos los usuarios de SQLite
    $sqliteUsers = DB::connection('sqlite')->table('users')->get();
    
    // Cambiar a la conexión MySQL
    DB::purge('mysql');
    DB::reconnect('mysql');
    
    // Migrar cada usuario que no exista en MySQL
    foreach ($sqliteUsers as $sqliteUser) {
        // Verificar si el usuario ya existe en MySQL
        $exists = User::where('email', $sqliteUser->email)->exists();
        
        if (!$exists) {
            // Crear el usuario en MySQL
            $user = new User([
                'name' => $sqliteUser->name,
                'email' => $sqliteUser->email,
                'password' => $sqliteUser->password, // Ya está hasheada
                'role' => $sqliteUser->role,
            ]);
            
            // Establecer timestamps si existen
            if (isset($sqliteUser->created_at)) {
                $user->created_at = $sqliteUser->created_at;
            }
            
            if (isset($sqliteUser->updated_at)) {
                $user->updated_at = $sqliteUser->updated_at;
            }
            
            // Guardar el usuario
            $user->save();
            
            echo "Usuario '{$sqliteUser->name}' ({$sqliteUser->email}) creado en MySQL con ID: {$user->id}\n";
        } else {
            echo "Usuario '{$sqliteUser->name}' ({$sqliteUser->email}) ya existe en MySQL, omitiendo.\n";
        }
    }
    
    echo "\nMigración de usuarios completada exitosamente.\n";
    
} catch (Exception $e) {
    echo "Error durante la migración: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
