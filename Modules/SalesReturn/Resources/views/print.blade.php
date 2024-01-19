<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalles de Devolución de Venta</title>
    <style>
        /* General styling */
        body {
            font-family: sans-serif;
            margin: 0;
        }

        .container-fluid {
            width: 100%;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header img {
            width: 180px;
            margin-bottom: 10px;
        }

        .header h4 {
            margin-bottom: 20px;
        }

        .sections {
            display: flex;
            justify-content: center;
            text-align: center;
            margin-bottom: 10px;
        }

        .section {
            display: inline-block;
            box-sizing: border-box;
            padding: 15px;
            margin-bottom: 10px;
        }

        .section h4 {
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            font-size: 13px;
        }

        .section div {
            margin-bottom: 5px;
            font-size: 12px;
        }

        .table-container {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-table {
            margin-top: 20px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="header">
        <img src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
        <h4>
            <span>Referencia:</span> <strong>{{ $sale_return->reference }}</strong><br><br>

        </h4>
    </div>

    <div class="sections">
        <div class="section">
            <h4>Información de la Empresa:</h4>
            <div><strong>{{ settings()->company_name }}</strong></div>
            <div>{{ settings()->company_address }}</div>
            <div>Email: {{ settings()->company_email }}</div>
            <div>Teléfono: {{ settings()->company_phone }}</div>
        </div>
        <div class="section">
            <h4>Información del Cliente:</h4>
            <div><strong>{{ $customer->customer_name }}</strong></div>
            <div>{{ $customer->address }}</div>
            <div>Email: {{ $customer->customer_email }}</div>
            <div>Teléfono: {{ $customer->customer_phone }}</div>
        </div>
        <div class="section">
            <h4>Información de la Factura:</h4>
            <div>Factura: <strong>INV/{{ $sale_return->reference }}</strong></div>
            <div>Fecha: {{ \Carbon\Carbon::parse($sale_return->date)->format('d M, Y') }}</div>
            <div>Vendedor: <strong>{{ auth()->user()->name }}</strong></div>
            <div> Ruc: <strong>2020603760002K</strong> </div>
        </div>
    </div>

    <div class="table-container">
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

    <div class="total-table">
        <table class="table">
            <tbody>
            <!-- Uncomment and modify the following lines if needed -->
            <!--
            <tr>
                <td class="left"><strong>Descuento ({{ $sale_return->discount_percentage }}%)</strong></td>
                <td class="right">{{ format_currency($sale_return->discount_amount) }}</td>
            </tr>
            <tr>
                <td class="left"><strong>Impuesto ({{ $sale_return->tax_percentage }}%)</strong></td>
                <td class="right">{{ format_currency($sale_return->tax_amount) }}</td>
            </tr>
            <tr>
                <td class="left"><strong>Envío</strong></td>
                <td class="right">{{ format_currency($sale_return->shipping_amount) }}</td>
            </tr>
            -->
            <tr>
                <td class="left"><strong>Total General</strong></td>
                <td class="right"><strong>{{ format_currency($sale_return->total_amount) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>{{ settings()->company_name }} &copy; {{ date('Y') }}.</p>
    </div>
</div>
</body>
</html>
