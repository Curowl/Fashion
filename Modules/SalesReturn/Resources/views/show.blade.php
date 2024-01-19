@extends('layouts.app')

@section('title', 'Detalles de Devoluciones de Venta')

@section('breadcrumb')
    <ol class="breadcrumb border-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">Devoluciones de Venta</a></li>
        <li class="breadcrumb-item active">Detalles</li>
    </ol>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center">
                        <div>
                            Referencia: <strong>{{ $sale_return->reference }}</strong>
                        </div>
                        <a target="_blank" class="btn btn-sm btn-secondary mfs-auto mfe-1 d-print-none" href="{{ route('sale-returns.pdf', $sale_return->id) }}">
                            <i class="bi bi-printer"></i> Imprimir
                        </a>
                        <a target="_blank" class="btn btn-sm btn-info mfe-1 d-print-none" href="{{ route('sale-returns.pdf', $sale_return->id) }}">
                            <i class="bi bi-save"></i> Guardar
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Información de la Empresa:</h5>
                                <div><strong>{{ settings()->company_name }}</strong></div>
                                <div>{{ settings()->company_address }}</div>
                                <div>Email: {{ settings()->company_email }}</div>
                                <div>Teléfono: {{ settings()->company_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Información del Cliente:</h5>
                                <div><strong>{{ $customer->customer_name }}</strong></div>
                                <div>{{ $customer->address }}</div>
                                <div>Email: {{ $customer->customer_email }}</div>
                                <div>Teléfono: {{ $customer->customer_phone }}</div>
                            </div>

                            <div class="col-sm-4 mb-3 mb-md-0">
                                <h5 class="mb-2 border-bottom pb-2">Información de la Factura:</h5>
                                <div>Factura: <strong>INV/{{ $sale_return->reference }}</strong></div>
                                <div>Fecha: {{ \Carbon\Carbon::parse($sale_return->date)->format('d M, Y') }}</div>
                                <div>
                                    Vendedor: <strong>{{ auth()->user()->name }}</strong>
                                </div>

                            </div>
                        </div>

                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="align-middle">Producto</th>
                                    <th class="align-middle">Precio Unitario Neto</th>
                                    <th class="align-middle">Cantidad</th>
                                    <th class="align-middle">Descuento</th>
                                    <th class="align-middle">Impuesto</th>
                                    <th class="align-middle">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sale_return->saleReturnDetails as $item)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $item->product_name }} <br>
                                            <span class="badge badge-success">
                                                {{ $item->product_code }}
                                            </span>
                                        </td>

                                        <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                                        <td class="align-middle">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_discount_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->product_tax_amount) }}
                                        </td>

                                        <td class="align-middle">
                                            {{ format_currency($item->sub_total) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-5 ml-md-auto">
                                <table class="table">
                                    <tbody>
                                    {{--
                                    <tr>
                                        <td class="left"><strong>Descuento ({{ $sale_return->discount_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale_return->discount_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Impuesto ({{ $sale_return->tax_percentage }}%)</strong></td>
                                        <td class="right">{{ format_currency($sale_return->tax_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="left"><strong>Envío)</strong></td>
                                        <td class="right">{{ format_currency($sale_return->shipping_amount) }}</td>
                                    </tr>
                                    --}}
                                    <tr>
                                        <td class="left"><strong>Total General</strong></td>
                                        <td class="right"><strong>{{ format_currency($sale_return->total_amount) }}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
