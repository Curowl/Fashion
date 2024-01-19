<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcodes</title>
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

        .row {
            display: inline-block;
            margin: -10px; /* Adjust based on your needs */
        }

        .col-xs-3 {
            display: inline-block;
            box-sizing: border-box;
            width: calc(25% - 20px); /* Adjust based on your needs */
            margin: 15px; /* Adjust based on your needs */
            border: 1px solid #dddddd;
            border-style: dashed;
        }

        p {
            font-size: 15px;
            color: #000;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        div.barcode {
            /* Add any specific styles for the barcode container */
        }

        p.price {
            font-size: 15px;
            color: #000;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        @foreach($barcodes as $barcode)
            <div class="col-xs-3">
                <p>
                    {{ $name }}
                </p>
                <div class="barcode">
                    <img src="data:image/svg+xml;base64,{{ $barcode }}">
                </div>
                <p class="price">
                    Precio: {{ format_currency($price) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>

