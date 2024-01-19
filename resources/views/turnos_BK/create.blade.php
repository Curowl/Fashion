@extends('layouts.app')

@section('title', 'Abrir Turno de Caja')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #3490dc; color: white; font-size: 18px; font-weight: bold;">Abrir Turno de Caja</div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('turnos.store') }}">
                            @csrf

                            <!-- Card para Usuario -->
                            <div class="card mb-3">
                                <div class="card-header" style="background-color: #f8f9fa; font-weight: bold;">Usuario</div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="usuario_id"><strong>Seleccione un Usuario:</strong></label>
                                        <select name="usuario_id" class="form-control" id="usuario_id">
                                            @foreach ($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Card para Monto Inicial y Fecha de Apertura -->
                            <div class="card">
                                <div class="card-header" style="background-color: #f8f9fa; font-weight: bold;">Monto Inicial y Fecha de Apertura</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="monto_inicial"><strong>Monto Inicial:</strong></label>
                                                <input type="number" name="monto_inicial" class="form-control" id="monto_inicial" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fecha_apertura"><strong>Fecha y Hora de Apertura:</strong></label>
                                                <input type="datetime-local" name="fecha_apertura" class="form-control" id="fecha_apertura" required>
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">Abrir Caja</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




