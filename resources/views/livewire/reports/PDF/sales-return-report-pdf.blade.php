<!-- resources/views/livewire/reports/sales-return-report-pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe de Devoluciones de Ventas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #666;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            background-color: #fff;
        }

        .badge {
            display: inline-block;
            font-size: 0.75em;
            padding: 0.375em 0.5625em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .badge-info {
            color: #fff;
            background-color: #17a2b8;
        }

        .badge-primary {
            color: #fff;
            background-color: #007bff;
        }

        .badge-success {
            color: #fff;
            background-color: #28a745;
        }
    </style>
</head>
<body>



<div class="container-fluid">

    <center>
    <div class="header">
        <img src="{{ public_path('images/logo-dark.png') }}" alt="Logo" height="20%">
    </div>
    </center>

    <h2>Informe de Devoluciones de Ventas</h2>

    <div class="sections">
        <div class="section">
            <h4>Información de la Empresa:</h4>
            <div><strong>{{ settings()->company_name }}</strong></div>
            <div>{{ settings()->company_address }}</div>
            <div>Email: {{ settings()->company_email }}</div>
            <div>Teléfono: {{ settings()->company_phone }}</div>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Referencia</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sale_returns as $sale_return)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale_return->date)->format('d M, Y') }}</td>
                <td>{{ $sale_return->reference }}</td>
                <td>{{ $sale_return->customer_name }}</td>
                <td>
                    @if ($sale_return->status == 'Pendiente')
                        <span class="badge badge-info">
                            {{ $sale_return->status }}
                        </span>
                    @elseif ($sale_return->status == 'Enviado')
                        <span class="badge badge-primary">
                            {{ $sale_return->status }}
                        </span>
                    @else
                        <span class="badge badge-success">
                            {{ $sale_return->status }}
                        </span>
                    @endif
                </td>
                <td>{{ format_currency($sale_return->total_amount) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    ¡No hay datos de devolución de ventas disponibles!
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
