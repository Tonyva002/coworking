@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mis Reservaciones</span>
                    <div>
                        @if(!Auth::user()->isAdmin())
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-sm">Nueva Reserva</a>
                        @else
                            <a href="{{ route('reservations.export') }}" class="btn btn-success btn-sm">Exportar a Excel</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    @if(Auth::user()->isAdmin())
                                        <th>Cliente</th>
                                    @endif
                                    <th>Sala</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->id }}</td>
                                        @if(Auth::user()->isAdmin())
                                            <td>{{ $reservation->user->name }}</td>
                                        @endif
                                        <td>{{ $reservation->room->name }}</td>
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
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-sm">Ver</a>
                                                @if(Auth::user()->isAdmin())
                                                    <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning btn-sm">Cambiar Estado</a>
                                                @endif
                                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->isAdmin() ? '7' : '6' }}" class="text-center">No hay reservaciones registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
