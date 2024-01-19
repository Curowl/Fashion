<?php

namespace App\Livewire\Barcode;

use Livewire\Component;
use Milon\Barcode\Facades\DNS1DFacade;
use Modules\Product\Entities\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductTable extends Component
{
    public $product;
    public $quantity;
    public $barcodes;

    protected $listeners = ['productSelected'];

    public function mount() {
        $this->product = '';
        $this->quantity = 0;
        $this->barcodes = [];
    }

    public function render() {
        return view('livewire.barcode.product-table');
    }

    public function productSelected(Product $product) {
        $this->product = $product;
        $this->quantity = 1;
        $this->barcodes = [];
    }

    public function generateBarcodes(Product $product, $quantity) {
        if ($quantity > 100) {
            return session()->flash('message', 'La cantidad maxima es de 100 códigos de barra!');
        }

        if (!is_numeric($product->product_code)) {
            return session()->flash('message', 'No se puede generar el codigo de barras con este tipo de codigo de producto');
        }

        $this->barcodes = [];

        for ($i = 1; $i <= $quantity; $i++) {
            $barcode = DNS1DFacade::getBarCodeSVG($product->product_code, $product->product_barcode_symbology,2 , 60, 'black', false);
            $barcodeBase64 = base64_encode($barcode);

            array_push($this->barcodes, $barcodeBase64);
           // array_push($this->barcodes, $barcode);
        }
    }

    public function getPdf() {
        $pdf = PDF::loadView('product::barcode.print', [
            'barcodes' => $this->barcodes,
            'price' => $this->product->product_price,
            'name' => $this->product->product_name,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->stream();
        }, 'codigo de producto.pdf');

    }

    public function updatedQuantity() {
        $this->barcodes = [];
    }
}
