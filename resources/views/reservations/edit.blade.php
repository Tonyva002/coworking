@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Cambiar Estado de Reserva</span>
                        <a href="{{ route('reservations.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Información de la Reserva</h5>
                        <hr>
                        <p><strong>ID:</strong> {{ $reservation->id }}</p>
                        <p><strong>Cliente:</strong> {{ $reservation->user->name }}</p>
                        <p><strong>Sala:</strong> {{ $reservation->room->name }}</p>
                        <p><strong>Fecha:</strong> {{ $reservation->reservation_date }}</p>
                        <p><strong>Hora:</strong> {{ $reservation->reservation_time }}</p>
                        <p><strong>Estado Actual:</strong> 
                            @if($reservation->status == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @elseif($reservation->status == 'aceptada')
                                <span class="badge bg-success">Aceptada</span>
                            @elseif($reservation->status == 'rechazada')
                                <span class="badge bg-danger">Rechazada</span>
                            @endif
                        </p>
                    </div>

                    <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="status">Cambiar Estado</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pendiente" {{ $reservation->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="aceptada" {{ $reservation->status == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                                <option value="rechazada" {{ $reservation->status == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
