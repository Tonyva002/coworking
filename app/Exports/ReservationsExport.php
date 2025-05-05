<?php

namespace App\Exports;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\Response;

class ReservationsExport
{
    /**
     * Exportar todas las reservas a CSV.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename = 'reservaciones', $type = 'csv')
    {
        // Crear un archivo CSV temporal para las reservaciones
        $reservationsFile = tempnam(sys_get_temp_dir(), 'reservations');
        $reservationsHandle = fopen($reservationsFile, 'w');
        
        // Agregar BOM para UTF-8
        fputs($reservationsHandle, "\xEF\xBB\xBF");
        
        // Agregar cabeceras para las reservaciones
        fputcsv($reservationsHandle, ['ID', 'Cliente', 'Sala', 'Fecha', 'Hora', 'Estado']);
        
        // Obtener todas las reservas con sus relaciones
        $reservations = Reservation::with(['user', 'room'])->get();
        
        // Agregar datos de reservas
        foreach ($reservations as $reservation) {
            fputcsv($reservationsHandle, [
                $reservation->id,
                $reservation->user->name,
                $reservation->room->name,
                $reservation->reservation_date,
                $reservation->reservation_time,
                $reservation->status
            ]);
        }
        
        // Cerrar el archivo
        fclose($reservationsHandle);
        
        // Crear un archivo ZIP para contener ambos archivos CSV
        $zipFile = tempnam(sys_get_temp_dir(), 'reservations_zip');
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFile, \ZipArchive::OVERWRITE) === TRUE) {
            // Agregar el archivo de reservaciones al ZIP
            $zip->addFile($reservationsFile, 'Reservaciones.csv');
            
            // Crear un archivo CSV temporal para el resumen por sala
            $summaryFile = tempnam(sys_get_temp_dir(), 'summary');
            $summaryHandle = fopen($summaryFile, 'w');
            
            // Agregar BOM para UTF-8
            fputs($summaryHandle, "\xEF\xBB\xBF");
            
            // Agregar cabeceras para el resumen
            fputcsv($summaryHandle, ['Sala', 'Total de Horas Reservadas']);
            
            // Obtener todas las salas
            $rooms = Room::all();
            
            // Agregar datos de resumen
            foreach ($rooms as $room) {
                // Contar el número de reservas para esta sala
                $totalReservations = $room->reservations()->count();
                
                fputcsv($summaryHandle, [
                    $room->name,
                    $totalReservations
                ]);
            }
            
            // Cerrar el archivo
            fclose($summaryHandle);
            
            // Agregar el archivo de resumen al ZIP
            $zip->addFile($summaryFile, 'Resumen_por_Sala.csv');
            
            // Cerrar el ZIP
            $zip->close();
            
            // Eliminar los archivos temporales CSV
            @unlink($reservationsFile);
            @unlink($summaryFile);
            
            // Descargar el archivo ZIP
            return Response::download($zipFile, $filename . '.zip', [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.zip"',
            ])->deleteFileAfterSend(true);
        }
        
        // Si algo falla, devolver solo el archivo de reservaciones
        return Response::download($reservationsFile, $filename . '.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ])->deleteFileAfterSend(true);
    }
}
