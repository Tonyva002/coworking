@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Detalles de la Reserva</span>
                        <div>
                            <a href="{{ route('reservations.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">Cambiar Estado</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Información de la Reserva</h5>
                        <hr>
                        <p><strong>ID:</strong> {{ $reservation->id }}</p>
                        <p><strong>Cliente:</strong> {{ $reservation->user->name }}</p>
                        <p><strong>Sala:</strong> {{ $reservation->room->name }}</p>
                        <p><strong>Fecha:</strong> {{ $reservation->reservation_date }}</p>
                        <p><strong>Hora:</strong> {{ $reservation->reservation_time }}</p>
                        <p><strong>Estado:</strong> 
                            @if($reservation->status == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @elseif($reservation->status == 'aceptada')
                                <span class="badge bg-success">Aceptada</span>
                            @elseif($reservation->status == 'rechazada')
                                <span class="badge bg-danger">Rechazada</span>
                            @endif
                        </p>
                        <p><strong>Creada el:</strong> {{ $reservation->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Actualizada el:</strong> {{ $reservation->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    <div class="d-flex justify-content-between">
                        @if(Auth::user()->id === $reservation->user_id || Auth::user()->isAdmin())
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar Reserva</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
