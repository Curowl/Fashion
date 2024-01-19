<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalles de la Cotización</title>

    <style>
        /* General styling */
        body {
            font-family: sans-serif;
            margin: 0;
        }

        .container {
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
<div class="container">
    <div class="header">
        <img src="{{ public_path('images/logo-dark.png') }}" alt="Logo">
        <h4>
            <span>Referencia:</span> <strong>{{ $quotation->reference }}</strong><br><br>
        </h4>
    </div>

    <div class="sections">
        <div class="section"  >
            <h4>Información de la Empresa:</h4>
            <div><strong>{{ settings()->company_name }}</strong></div>
            <div>{{ settings()->company_address }}</div>
            <div>Email: {{ settings()->company_email }}</div>
            <div>Teléfono: {{ settings()->company_phone }}</div>
        </div>
        <div class="section"    >
            <h4>Información del Cliente:</h4>
            <div><strong>{{ $customer->customer_name }}</strong></div>
            <div>{{ $customer->address }}</div>
            <div>Email: {{ $customer->customer_email }}</div>
            <div>Teléfono: {{ $customer->customer_phone }}</div>
        </div>
        <div class="section"   >
            <h4>Información de la Factura:</h4>
            <div>Factura: <strong>INV/{{ $quotation->reference }}</strong></div>
            <div>Fecha: {{ \Carbon\Carbon::parse($quotation->date)->format('d M, Y') }}</div>
            <div>Vendedor: <strong>{{ auth()->user()->name }}</strong></div>
            <div>Ruc: <strong>2020603760002K</strong> </div>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th class="align-middle">Producto</th>
                <th class="align-middle">Precio Neto Unitario</th>
                <th class="align-middle">Cantidad</th>
                <th class="align-middle">Descuento</th>
                <th class="align-middle">Impuesto</th>
                <th class="align-middle">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quotation->quotationDetails as $item)
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
                        {{ format_currency($item->product_discount_amount) }}</td>

                    <td class="align-middle">
                        {{ format_currency($item->product_tax_amount) }}</td>

                    <td class="align-middle">
                        {{ format_currency($item->sub_total) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-table">
        <table class="table">
            <tbody>
            <!--
            <tr>
                <td class="left"><strong>Descuento ({{ $quotation->discount_percentage }}%)</strong></td>
                <td class="right">{{ format_currency($quotation->discount_amount) }}</td>
            </tr>
            <tr>
                <td class="left"><strong>Impuesto ({{ $quotation->tax_percentage }}%)</strong></td>
                <td class="right">{{ format_currency($quotation->tax_amount) }}</td>
            </tr>
            <tr>
                <td class="left"><strong>Envío)</strong></td>
                <td class="right">{{ format_currency($quotation->shipping_amount) }}</td>
            </tr>
            -->
            <tr>
                <td class="left"><strong>Total General</strong></td>
                <td class="right"><strong>{{ format_currency($quotation->total_amount) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p style="font-style: italic;text-align: center">{{ settings()->company_name }} &copy; {{ date('Y') }}</p>
    </div>
</div>
</body>
</html>
