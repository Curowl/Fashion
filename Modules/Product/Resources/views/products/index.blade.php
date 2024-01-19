@extends('layouts.app')

@section('title', 'Products')

@section('third_party_stylesheets')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="toggleTrash" {{ $showTrash ? 'checked' : '' }}>
                            <label class="custom-control-label" for="toggleTrash">Mostrar Productos Deshabilitados</label>
                        </div>

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    {!! $dataTable->scripts() !!}
    <script>
        document.getElementById('toggleTrash').addEventListener('change', function () {
            window.location.href = '{{ route('products.index') }}?showTrash=' + this.checked;
        });

    </script>


@endpush

