



@extends('layouts.app')

@section('title', 'Ver Cierre de Turno de Caja')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>
                            <i class="fas fa-hand-holding-usd"></i> Detalles del Cierre del Turno de Caja
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Lado Izquierdo -->
                            <div class="col-md-6 mb-4">
                                <!-- Información del Usuario con Caja Abierta -->
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-user"></i> Usuario con Caja Abierta
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <strong>Nombre:</strong> {{ $usuario ? $usuario->name : 'Usuario no disponible' }}

                                    </div>
                                </div>

                                <!-- Estadísticas de Ventas del Turno -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-chart-bar"></i> Estadísticas de Ventas del Turno
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Cantidad de Ventas:</strong> {{ $ventasTurno }}</p>
                                        <p><strong>Total de Ventas:</strong> C$ {{ number_format($totalVentasTurnoDecimal, 2, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Monto Total (Inicial + Ventas) -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-coins"></i> Monto Total (Inicial + Ventas)
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        C$  {{ number_format($montoTotal, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Lado Derecho -->
                            <div class="col-md-6 mb-4">
                                <!-- Información de Fechas -->
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-calendar-alt"></i> Información de Fechas
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Fecha de Apertura:</strong> {{ $turno->created_at->format('d/m/Y h:i:s A') }}</p>
                                        <p><strong>Fecha de Cierre:</strong>
                                            @if ($turno->fecha_cierre)
                                                {{ $turno->fecha_cierre instanceof \Carbon\Carbon ? $turno->fecha_cierre->format('d/m/Y h:i:s A') : date('d/m/Y h:i:s A', strtotime($turno->fecha_cierre)) }}
                                            @else
                                                <span class="badge badge-success">Caja Abierta</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Monton Inicial de Caja -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-dollar-sign"></i> Monto Inicial de Caja
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        C$  {{ number_format($montoInicial, 2, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Información del Monto Final -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-dollar-sign"></i> Información del Monto Final
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Monto Final:</strong>
                                            @if ($turno->monto_final)
                                                C$ {{ number_format($turno->monto_final, 2, ',', '.') }}
                                            @else
                                                <span class="badge badge-success">Caja Abierta</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Comentario -->
                            <div class="col-md-12 mt-4">
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-comment"></i> Comentario
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($turno->comentario)
                                            {{ $turno->comentario }}
                                        @else
                                            No hay comentario para este cierre de caja.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
