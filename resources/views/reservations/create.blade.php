@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Crear Nueva Reserva</span>
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

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservations.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="room_id">Sala</label>
                            <select class="form-control" id="room_id" name="room_id" required>
                                <option value="">Seleccione una sala</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reservation_date">Fecha</label>
                            <input type="date" class="form-control" id="reservation_date" name="reservation_date" value="{{ old('reservation_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="reservation_time">Hora</label>
                            <select class="form-control" id="reservation_time" name="reservation_time" required>
                                <option value="">Seleccione una hora</option>
                                @for($i = 8; $i <= 20; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}:00:00" {{ old('reservation_time') == sprintf('%02d', $i) . ':00:00' ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }}:00
                                    </option>
                                @endfor
                            </select>
                            <small class="form-text text-muted">Las reservas son de una hora de duración.</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Reservar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
