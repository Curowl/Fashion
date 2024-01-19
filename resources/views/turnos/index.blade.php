@extends('layouts.app')

@section('title', 'Lista de Turnos de Caja')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @can('delete_expenses')
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="toggleCerradas" {{ $cerradas ? 'checked' : '' }}>
                                <label class="custom-control-label" for="toggleCerradas">Turnos cerrados</label>
                            </div>
                        @endcan
                        <h1 class="mb-0">Turnos de Caja</h1>
                    </div>
                    <div class="card-body">
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
        {!! $dataTable->scripts() !!}
        <script>
            document.getElementById('toggleCerradas').addEventListener('change', function () {
                // Obtiene el valor actual del toggle
                var isChecked = this.checked;

                // Convierte el valor booleano a un entero (1 para true, 0 para false)
                var cerradasValue = isChecked ? 1 : 0;

                // Actualiza la URL con el nuevo valor del toggle
                window.location.href = '{{ route('turnos.index') }}?cerradas=' + cerradasValue;
            });
        </script>


    @endpush
@endsection


