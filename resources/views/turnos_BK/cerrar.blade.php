@extends('layouts.app')

@section('title', 'Cerrar Turno de Caja')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>
                        <i class="fas fa-hand-holding-usd"></i> Cerrar Turno de Caja
                    </h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($turno->estado === 'cerrado')
                        <!-- Si la caja está cerrada, mostrar un mensaje -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">Caja Cerrada</div>
                            <div class="card-body">
                                La caja ya está cerrada.
                            </div>
                        </div>
                    @else

                        <div class="row">
                            <!-- Información del Usuario con Caja Abierta -->
                            @if($usuarioConCajaAbierta)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-user"></i> Usuario con Caja Abierta
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <strong>Nombre:</strong> {{ optional($usuarioConCajaAbierta->usuario)->name }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Estadísticas de Ventas del Turno -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-chart-bar"></i> Estadísticas de Ventas del Turno
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Cantidad de Ventas:</strong> {{ $ventasTurno }}</p>
                                        <p><strong>Total de Ventas:</strong> {{ $totalVentasTurno }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Monto Inicial de Caja -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-dollar-sign"></i> Monto Inicial de Caja
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        {{ $montoInicial }}
                                    </div>
                                </div>
                            </div>

                            <!-- Monto Total (Inicial + Ventas) -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light text-dark">
                                        <h5>
                                            <i class="fas fa-coins"></i> Monto Total (Inicial + Ventas)
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        {{ $montoTotal }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario para Cerrar Caja -->
                        <form method="POST" action="{{ route('turnos.cerrar', $turno->id) }}">
                            @csrf
                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto_final"><strong>Monto Final:</strong></label>
                                        <input type="number" name="monto_final" class="form-control" id="monto_final" required>
                                    </div>
                                </div>
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_cierre"><strong>Fecha y Hora de Cierre:</strong></label>
                                        <input type="datetime-local" name="fecha_cierre" class="form-control" id="fecha_cierre" required>
                                    </div>
                                </div>
                            </div>
                        
                            <button type="submit" class="btn btn-primary btn-lg">Cerrar Caja</button>
                        </form>
                        
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

