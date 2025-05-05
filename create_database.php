<?php
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '!01angie01');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS cowork_reservation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    echo "Base de datos 'cowork_reservation' creada exitosamente.\n";
} catch (PDOException $e) {
    die("Error al crear la base de datos: " . $e->getMessage() . "\n");
}
