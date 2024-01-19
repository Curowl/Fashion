@extends('layouts.app')

@section('title', 'Print Barcode')

@push('page_css')
    @livewireStyles
@endpush

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Imprimir Código de Barra</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <livewire:search-product/>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>NOTA: El código de producto debe ser un número para generar un código de barras</strong>
                </div>
            </div>
            <div class="col-md-12">
                <livewire:barcode.product-table/>
            </div>
        </div>
    </div>
@endsection
