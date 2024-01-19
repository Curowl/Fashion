{{ $ocultarCampos = true  }}

@extends('layouts.app')

@section('title', 'Crear Venta')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Ventas</a></li>
        <li class="breadcrumb-item active">Agregar</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid mb-4">
        <div class="row">
            <div class="col-12">
                <livewire:search-product/>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @include('utils.alerts')
                        <form id="sale-form" action="{{ route('sales.store') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="reference">Referencia <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="reference" required readonly value="SL">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="customer_id">Cliente <span class="text-danger">*</span></label>
                                            <select class="form-control" name="customer_id" id="customer_id" required>
                                                @foreach(\Modules\People\Entities\Customer::all() as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="from-group">
                                        <div class="form-group">
                                            <label for="date">Fecha <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <livewire:product-cart :cartInstance="'sale'"/>

                            <div class="form-row">
                                <div class="col-lg-4"  @if($ocultarCampos) hidden @endif>
                                    <div class="form-group">
                                        <label for="status">Estado <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="status" required @if($ocultarCampos) hidden @endif>
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Enviado">Enviado</option>
                                            <option value="Completado" @if($ocultarCampos) selected @endif>Completado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4" @if($ocultarCampos) hidden @endif>
                                    <div class="from-group" @if($ocultarCampos) hidden @endif>
                                        <div class="form-group">
                                            <label for="payment_method">Método de Pago <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment_method" id="payment_method" required>
                                                <option value="Efectivo" @if($ocultarCampos) selected @endif>Efectivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="paid_amount">Cantidad Recibida <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input id="paid_amount" type="text" class="form-control" name="paid_amount" required>
                                            <div class="input-group-append">
                                                <button id="getTotalAmount" class="btn btn-danger" type="button">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-lg-12">
                                        <div class="form-group">

                                            <label for="change">Cambio</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">C$</span> </div>
                                                <input type="text" class="form-control" id="change" name="change" readonly >
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="note">Nota (si es necesario)</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Crear Venta <i class="bi bi-check"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#paid_amount').maskMoney({
                prefix: '{{ settings()->currency->symbol }}',
                thousands: '{{ settings()->currency->thousand_separator }}',
                decimal: '{{ settings()->currency->decimal_separator }}',
                allowZero: true,
            });




            $('#getTotalAmount').click(function () {
                var totalAmount = parseFloat("{{ str_replace(',', '', Cart::instance('sale')->total()) }}");
                $('#paid_amount').maskMoney('mask', totalAmount).trigger('input');
            });

            $('#paid_amount').on('keyup', function () {
                updateChange();
            });

            function updateChange() {
                var totalAmount = parseFloat($('input[name="total_amount"]').val().replace(',', ''));

                var unmaskedValue = $('#paid_amount').maskMoney('unmasked')[0];

                // Verificar si el valor es un número antes de continuar
                if (isNaN(unmaskedValue)) {
                    console.error('Error: El valor no es un número válido');
                    return;
                }

                var paidAmount = parseFloat(unmaskedValue);
                var change = paidAmount - totalAmount;

                // Verificar si el cambio es negativo (no se puede dar cambio negativo)
                if (change < 0) {
                    console.error('Error: No se puede dar cambio negativo');
                    return;
                }

                var changeString = change.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                $('#change').val(changeString);
            }

            updateChange(); // Llamar a la función al cargar la página para inicializar el cambio
        });
    </script>



@endpush
