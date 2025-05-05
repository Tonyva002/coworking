<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservationsExport;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            // Si es administrador, mostrar todas las reservas
            $reservations = Reservation::with(['user', 'room'])->latest()->get();
            return view('reservations.index', compact('reservations'));
        } else {
            // Si es cliente, mostrar solo sus reservas
            $reservations = Auth::user()->reservations()->with('room')->latest()->get();
            return view('reservations.index', compact('reservations'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Solo los clientes pueden crear reservas
        if (Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Los administradores no pueden crear reservas.');
        }

        $rooms = Room::all();
        return view('reservations.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Solo los clientes pueden crear reservas
        if (Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Los administradores no pueden crear reservas.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
        ]);

        // Verificar si ya existe una reserva para la misma sala, fecha y hora
        $existingReservation = Reservation::where('room_id', $request->room_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->first();

        if ($existingReservation) {
            return redirect()->back()
                ->with('error', 'Ya existe una reserva para esta sala en la fecha y hora seleccionadas.')
                ->withInput();
        }

        // Crear la reserva
        $reservation = new Reservation([
            'room_id' => $request->room_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'status' => 'pendiente',
        ]);

        Auth::user()->reservations()->save($reservation);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva creada exitosamente. Estado: Pendiente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'room'])->findOrFail($id);

        // Verificar si el usuario actual es el propietario de la reserva o es administrador
        if (Auth::user()->id !== $reservation->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para ver esta reserva.');
        }

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Solo los administradores pueden editar reservas
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para editar esta reserva.');
        }

        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        // Solo los administradores pueden actualizar el estado de las reservas
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para actualizar esta reserva.');
        }

        $request->validate([
            'status' => 'required|in:pendiente,aceptada,rechazada',
        ]);

        $reservation->update([
            'status' => $request->status,
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Estado de la reserva actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Verificar si el usuario actual es el propietario de la reserva o es administrador
        if (Auth::user()->id !== $reservation->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para eliminar esta reserva.');
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }

    /**
     * Exportar reservaciones a Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'Solo los administradores pueden exportar reservaciones.');
        }

        $export = new ReservationsExport();
        return $export->download('reservaciones', 'xlsx');
    }
}
