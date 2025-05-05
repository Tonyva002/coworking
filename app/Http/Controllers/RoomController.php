<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Sala creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);
        $reservations = $room->reservations()->with('user')->get();

        return view('rooms.show', compact('room', 'reservations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
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
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Sala actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('reservations.index')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        $room = Room::findOrFail($id);
        
        // Verificar si hay reservas asociadas a esta sala
        if ($room->reservations()->count() > 0) {
            return redirect()->route('rooms.index')
                ->with('error', 'No se puede eliminar la sala porque tiene reservas asociadas.');
        }
        
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Sala eliminada exitosamente.');
    }
}
