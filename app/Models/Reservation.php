<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'room_id',
        'reservation_date',
        'reservation_time',
        'status',
    ];

    /**
     * Obtener el usuario que realizó la reserva.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener la sala reservada.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
