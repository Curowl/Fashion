@extends('layouts.app')

@section('title', 'Punto de Venta (POS)')

@section('third_party_stylesheets')

@endsection

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Punto de Venta (POS)</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @include('utils.alerts')
            </div>
            <div class="col-lg-7">
                <livewire:search-product/>
                <livewire:pos.product-list :categories="$product_categories"/>
            </div>
            <div class="col-lg-5">
                <livewire:pos.checkout :cart-instance="'sale'" :customers="$customers"/>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script src="{{ asset('js/jquery-mask-money.js') }}"></script>
    <script>
        $(document).ready(function () {
            window.addEventListener('showCheckoutModal', event => {
                $('#checkoutModal').modal('show');

                $('#paid_amount').maskMoney({
                    prefix:'{{ settings()->currency->symbol }}',
                    thousands:'{{ settings()->currency->thousand_separator }}',
                    decimal:'{{ settings()->currency->decimal_separator }}',
                    allowZero: false,
                });

                $('#total_amount').maskMoney({
                    prefix:'{{ settings()->currency->symbol }}',
                    thousands:'{{ settings()->currency->thousand_separator }}',
                    decimal:'{{ settings()->currency->decimal_separator }}',
                    allowZero: true,
                });

                $('#paid_amount').maskMoney('mask');
                $('#total_amount').maskMoney('mask');

                $('#checkout-form').submit(function () {
                    var paid_amount = $('#paid_amount').maskMoney('unmasked')[0];
                    $('#paid_amount').val(paid_amount);
                    var total_amount = $('#total_amount').maskMoney('unmasked')[0];
                    $('#total_amount').val(total_amount);
                });
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            // Obtén los elementos relevantes
            var paidAmountInput = $('#paid_amount');
            var totalAmountInput = $('#total_amount');
            var changeAmountInput = $('#change_amount');

            // Función para calcular y actualizar el cambio
            function updateChangeAmount() {
                // Obtén los valores no enmascarados
                var paidAmountUnmasked = parseFloat(paidAmountInput.maskMoney('unmasked')[0]) || 0;
                var totalAmountUnmasked = parseFloat(totalAmountInput.maskMoney('unmasked')[0]) || 0;

                // Calcula el cambio
                var change = Math.max(0, paidAmountUnmasked - totalAmountUnmasked);

                // Actualiza el campo de cambio
                changeAmountInput.val(change.toFixed(2));
            }

            // Maneja eventos de cambio en el monto pagado (escucha el evento change)
            paidAmountInput.on('change', function () {
                updateChangeAmount();
            });

            // Maneja eventos de cambio en el monto total (escucha el evento change)
            totalAmountInput.on('change', function () {
                updateChangeAmount();
            });

            // Inicializa el cambio al cargar la página
            updateChangeAmount();
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('checkout-form').addEventListener('submit', function () {
                document.getElementById('submitBtn').setAttribute('disabled', 'disabled');
            });
        });
    </script>







@endpush
