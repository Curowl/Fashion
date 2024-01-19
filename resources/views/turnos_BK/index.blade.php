@extends('layouts.app')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">Turnos</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha de apertura</th>
                                    <th>Fecha de Cierre</th>
                                    <th>Usuario</th>
                                    <th>Monto inicial</th>
                                    <th>Monto final</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($turnos as $turno)
                                    <tr>
                                        <td>{{ $turno->fecha_apertura }}</td>
                                        <td>{{ $turno->fecha_cierre }}</td>
                                        <td>
                                            @if($turno->usuario)
                                                {{ $turno->usuario->name }}
                                            @else
                                                Usuario no disponible
                                            @endif
                                        </td>
                                        <td>{{ $turno->monto_inicial }}</td>
                                        <td>{{ $turno->monto_final }}</td>
                                        <td>{{ $turno->estado }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


