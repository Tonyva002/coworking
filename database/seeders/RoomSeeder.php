<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear salas de ejemplo
        $rooms = [
            [
                'name' => 'Sala de Reuniones A',
                'description' => 'Sala amplia con capacidad para 10 personas, proyector y pizarra.',
            ],
            [
                'name' => 'Sala de Conferencias',
                'description' => 'Sala grande con capacidad para 20 personas, sistema de audio y proyector.',
            ],
            [
                'name' => 'Oficina Privada 1',
                'description' => 'Oficina individual con escritorio, silla ergonómica y conexión a internet de alta velocidad.',
            ],
            [
                'name' => 'Sala Creativa',
                'description' => 'Espacio abierto con mesas de trabajo, pizarras y material para lluvia de ideas.',
            ],
            [
                'name' => 'Sala de Reuniones B',
                'description' => 'Sala mediana con capacidad para 6 personas, pantalla y pizarra.',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
