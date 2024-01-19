@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-lg-12">
                    @include('utils.alerts')
                    <div class="form-group">
                        <button class="btn btn-primary">Actualizar Cliente <i class="bi bi-check"></i></button>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="customer_name">Nombre del Cliente <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" required value="{{ $customer->customer_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="customer_email">Correo Electrónico <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="customer_email" required value="{{ $customer->customer_email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="customer_phone">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" required value="{{ $customer->customer_phone }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city">Ciudad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="city" required value="{{ $customer->city }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="country">País <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="country" required value="{{ $customer->country }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="address">Dirección <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" required value="{{ $customer->address }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
