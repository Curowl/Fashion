<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            margin: 0;
        }
        * {
            font-size: 12px;
            line-height: 14px;
            font-family: 'Ubuntu', sans-serif;
            margin: 5px;
        }
        h3 {
            font-size: 12px;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dashed #ddd;}
        td,th {padding: 7px 0;width: 40%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 4px 0;}
            .hidden-print {
                display: none !important;
            }
            tbody::after {
                content: '';
                display: block;
                page-break-after: always;
                page-break-inside: auto;
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>

<div style="max-width:400px;margin:0 auto">
    <div id="receipt-data">
        <div class="centered">

            <img width="80" src="{{ public_path('images/logo-dark-mini.png') }}" alt="Logo">
            <h3 style="margin-bottom: 1px">{{ settings()->company_name }}</h3>

            <p style="font-size: 9px;line-height: 10px;margin-top: 0">
                <br><span>Ruc: 2020603760002K</span>
               <br> {{ settings()->company_email }}, {{ settings()->company_phone }}
                <br>{{ settings()->company_address }}

                <br>

        </div>
        <p >
            Fecha: {{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}<br>
            Referencia: {{ $sale->reference }}<br>
            Nombre: {{ $sale->customer_name }}<br>
            Vendedor: <strong>{{ auth()->user()->name }}</strong>
        </p>
        <table class="table-data">
            <tbody>

            @foreach($sale->saleDetails as $saleDetail)
                <tr>
                    <td colspan="2">
                        @if($saleDetail->product)
                            {{ $saleDetail->product->product_name }}
                            ({{ $saleDetail->quantity }} x {{ format_currency($saleDetail->price) }})
                        @else
                            Producto no disponible
                        @endif
                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{ format_currency($saleDetail->sub_total) }}</td>
                </tr>
            @endforeach

            @if($sale->tax_percentage)
                <tr>
                    <th colspan="2" style="text-align:left">Impuesto ({{ $sale->tax_percentage }}%)</th>
                    <th style="text-align:right">{{ format_currency($sale->tax_amount) }}</th>
                </tr>
            @endif
            @if($sale->discount_percentage)
                <tr>
                    <th colspan="2" style="text-align:left">Descuento ({{ $sale->discount_percentage }}%)</th>
                    <th style="text-align:right">{{ format_currency($sale->discount_amount) }}</th>
                </tr>
            @endif
            @if($sale->shipping_amount)
                <tr>
                    <th colspan="2" style="text-align:left">Envío</th>
                    <th style="text-align:right">{{ format_currency($sale->shipping_amount) }}</th>
                </tr>
            @endif
            <tr>
                <th colspan="2" style="text-align:left">Total</th>
                <th style="text-align:right">{{ format_currency($sale->total_amount) }}</th>
            </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr style="background-color:#ddd;">
                    <td class="centered" style="padding: 5px; width: 100%">
                        Pagado con: {{ $sale->payment_method }}
                    </td>
                    <td class="centered" style="padding: 5px; width: 100%">
                        Monto: {{ format_currency($sale->paid_amount) }}
                    </td>

                </tr>

                <tr style="border-bottom: 0;">
                    <td class="centered" colspan="3">
                        <div style="margin-top: 10px;">
                            {!! $barcodeSvg = \Milon\Barcode\Facades\DNS1DFacade::getBarcodeSVG($sale->reference, 'C128', 1, 25, 'black', false);
                                    $barcodeBase64 = base64_encode($barcodeSvg);
                            !!}
                            <img src="data:image/svg+xml;base64,<?php echo $barcodeBase64 ?>">
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>

