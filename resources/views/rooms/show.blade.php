@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Detalles de la Sala: {{ $room->name }}</span>
                        <div>
                            <a href="{{ route('rooms.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Información de la Sala</h5>
                            <hr>
                            <p><strong>ID:</strong> {{ $room->id }}</p>
                            <p><strong>Nombre:</strong> {{ $room->name }}</p>
                            <p><strong>Descripción:</strong> {{ $room->description ?: 'Sin descripción' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Reservaciones</h5>
                            <hr>
                            @if($reservations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Fecha</th>
                                                <th>Hora</th>
                                                <th>Estado</th>
                                                @if(Auth::user()->isAdmin())
                                                    <th>Acciones</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservations as $reservation)
                                                <tr>
                                                    <td>{{ $reservation->id }}</td>
                                                    <td>{{ $reservation->user->name }}</td>
                                                    <td>{{ $reservation->reservation_date }}</td>
                                                    <td>{{ $reservation->reservation_time }}</td>
                                                    <td>
                                                        @if($reservation->status == 'pendiente')
                                                            <span class="badge bg-warning">Pendiente</span>
                                                        @elseif($reservation->status == 'aceptada')
                                                            <span class="badge bg-success">Aceptada</span>
                                                        @elseif($reservation->status == 'rechazada')
                                                            <span class="badge bg-danger">Rechazada</span>
                                                        @endif
                                                    </td>
                                                    @if(Auth::user()->isAdmin())
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">Cambiar Estado</a>
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>No hay reservaciones para esta sala.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
