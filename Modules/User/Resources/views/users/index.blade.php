@extends('layouts.app')

@section('title', 'Users')

@section('third_party_stylesheets')
    <!-- Asegúrate de tener los estilos de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
@endsection

@section('breadcrumb')
    <!-- Breadcrumb... -->
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Toggle para mostrar usuarios inactivos -->
                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="toggleInactivos">
                            <label class="custom-control-label" for="toggleInactivos">Mostrar Usuarios Inactivos</label>
                        </div>

                        <!-- Button trigger modal -->

                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            Agregar Usuario <i class="bi bi-plus"></i>
                        </a>

                        <hr>

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
        document.getElementById('toggleInactivos').addEventListener('change', function () {
            // Obtiene el valor actual del toggle
            var isChecked = this.checked;

            // Convierte el valor booleano a un entero (1 para true, 0 para false)
            var mostrarInactivosValue = isChecked ? 1 : 0;

            // Solo actualiza la URL si el valor es 0 o 1
            if (mostrarInactivosValue === 0 || mostrarInactivosValue === 1) {
                // Actualiza la URL con el nuevo valor del toggle
                window.location.href = '{{ route('users.index') }}?mostrarInactivos=' + mostrarInactivosValue;
            }
        });

        // Recupera el valor inicial del toggle de la URL y lo establece
        var urlParams = new URLSearchParams(window.location.search);
        var mostrarInactivosParam = urlParams.get('mostrarInactivos');
        if (mostrarInactivosParam !== null && (mostrarInactivosParam === '0' || mostrarInactivosParam === '1')) {
            document.getElementById('toggleInactivos').checked = mostrarInactivosParam === '1';
        }
    </script>




@endpush

