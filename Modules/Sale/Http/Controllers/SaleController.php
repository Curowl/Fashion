<?php

namespace Modules\Sale\Http\Controllers;

use Modules\Quotation\Entities\Quotation;
use Modules\Sale\DataTables\SalesDataTable;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Sale\Entities\Sale;
use Modules\Sale\Entities\SaleDetails;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Http\Requests\StoreSaleRequest;
use Modules\Sale\Http\Requests\UpdateSaleRequest;
use App\Models\TurnoCaja;


class SaleController extends Controller
{

    public function index(SalesDataTable $dataTable) {
        abort_if(Gate::denies('access_sales'), 403);

        return $dataTable->render('sale::index');
    }


    public function create() {
        abort_if(Gate::denies('create_sales'), 403);

        Cart::instance('sale')->destroy();

        return view('sale::create');
    }


    public function store(StoreSaleRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $due_amount = $request->total_amount - $request->paid_amount;

                // Validación
                if ($request->paid_amount <= 0 || $request->paid_amount < $request->total_amount) {
                    throw new \Exception('El monto recibido no puede ser menor o igual a cero, y no puede ser menor al total.');
                }


                // Validación del ID de la cotización
                if ($request->has('quotation_id')) {
                    $quotation = Quotation::findOrFail($request->quotation_id);

                    // Cambiar el estado de la cotización de "pending" a "completed"
                    if ($quotation->status == 'Pending') {
                        $quotation->update(['status' => 'Completed']);
                    }
                }

                if ($due_amount == $request->total_amount) {
                    $payment_status = 'Unpaid';
                } elseif ($due_amount > 0) {
                    $payment_status = 'Partial';
                } else {
                    $payment_status = 'Paid';
                }

                $sale = Sale::create([
                    'date' => $request->date,
                    'customer_id' => $request->customer_id,
                    'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                    'tax_percentage' => $request->tax_percentage,
                    'discount_percentage' => $request->discount_percentage,
                    'shipping_amount' => $request->shipping_amount * 100,
                    'paid_amount' => $request->paid_amount * 100,
                    'total_amount' => $request->total_amount * 100,
                    'due_amount' => $due_amount * 100,
                    'status' => $request->status,
                    'payment_status' => $payment_status,
                    'payment_method' => $request->payment_method,
                    'note' => $request->note,
                    'tax_amount' => Cart::instance('sale')->tax() * 100,
                    'discount_amount' => Cart::instance('sale')->discount() * 100,
                ]);

                foreach (Cart::instance('sale')->content() as $cart_item) {
                    SaleDetails::create([
                        'sale_id' => $sale->id,
                        'product_id' => $cart_item->id,
                        'product_name' => $cart_item->name,
                        'product_code' => $cart_item->options->code,
                        'quantity' => $cart_item->qty,
                        'price' => $cart_item->price * 100,
                        'unit_price' => $cart_item->options->unit_price * 100,
                        'sub_total' => $cart_item->options->sub_total * 100,
                        'product_discount_amount' => $cart_item->options->product_discount * 100,
                        'product_discount_type' => $cart_item->options->product_discount_type,
                        'product_tax_amount' => $cart_item->options->product_tax * 100,
                    ]);

                    if ($request->status == 'Shipped' || $request->status == 'Completado') {
                        $product = Product::findOrFail($cart_item->id);
                        $product->update([
                            'product_quantity' => $product->product_quantity - $cart_item->qty
                        ]);
                    }
                }

                // Obtener el turno de caja abierto para el usuario actual
                $turnoAbierto = TurnoCaja::where('usuario_id', auth()->user()->id)->where('estado', 'abierto')->first();

                if ($turnoAbierto) {
                    $sale->turno_caja_id = $turnoAbierto->id;
                    $sale->save();
                } else {
                    // Manejar la ausencia de un turno abierto
                }

                Cart::instance('sale')->destroy();

                if ($sale->paid_amount > 0) {
                    SalePayment::create([
                        'date' => $request->date,
                        'reference' => 'INV/'.$sale->reference,
                        'amount' => $sale->paid_amount,
                        'sale_id' => $sale->id,
                        'payment_method' => $request->payment_method
                    ]);
                }
            });

            toast('Venta Creada!', 'success');

            return redirect()->route('sales.index');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->route('sales.create');
        }
    }



    public function show(Sale $sale) {
        abort_if(Gate::denies('show_sales'), 403);

        $customer = Customer::findOrFail($sale->customer_id);

        return view('sale::show', compact('sale', 'customer'));
    }


    public function edit(Sale $sale) {
        abort_if(Gate::denies('edit_sales'), 403);

        $sale_details = $sale->saleDetails;

        Cart::instance('sale')->destroy();

        $cart = Cart::instance('sale');

        foreach ($sale_details as $sale_detail) {
            $cart->add([
                'id'      => $sale_detail->product_id,
                'name'    => $sale_detail->product_name,
                'qty'     => $sale_detail->quantity,
                'price'   => $sale_detail->price,
                'weight'  => 1,
                'options' => [
                    'product_discount' => $sale_detail->product_discount_amount,
                    'product_discount_type' => $sale_detail->product_discount_type,
                    'sub_total'   => $sale_detail->sub_total,
                    'code'        => $sale_detail->product_code,
                    'stock'       => Product::findOrFail($sale_detail->product_id)->product_quantity,
                    'product_tax' => $sale_detail->product_tax_amount,
                    'unit_price'  => $sale_detail->unit_price
                ]
            ]);
        }

        return view('sale::edit', compact('sale'));
    }


    public function update(UpdateSaleRequest $request, Sale $sale) {
        DB::transaction(function () use ($request, $sale) {

            $due_amount = $request->total_amount - $request->paid_amount;

            if ($due_amount == $request->total_amount) {
                $payment_status = 'Unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'Partial';
            } else {
                $payment_status = 'Paid';
            }

            foreach ($sale->saleDetails as $sale_detail) {
                if ($sale->status == 'Shipped' || $sale->status == 'Completado') {
                    $product = Product::findOrFail($sale_detail->product_id);
                    $product->update([
                        'product_quantity' => $product->product_quantity + $sale_detail->quantity
                    ]);
                }
                $sale_detail->delete();
            }

            $sale->update([
                'date' => $request->date,
                'reference' => $request->reference,
                'customer_id' => $request->customer_id,
                'customer_name' => Customer::findOrFail($request->customer_id)->customer_name,
                'tax_percentage' => $request->tax_percentage,
                'discount_percentage' => $request->discount_percentage,
                'shipping_amount' => $request->shipping_amount * 100,
                'paid_amount' => $request->paid_amount * 100,
                'total_amount' => $request->total_amount * 100,
                'due_amount' => $due_amount * 100,
                'status' => $request->status,
                'payment_status' => $payment_status,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
                'tax_amount' => Cart::instance('sale')->tax() * 100,
                'discount_amount' => Cart::instance('sale')->discount() * 100,
            ]);

            foreach (Cart::instance('sale')->content() as $cart_item) {
                SaleDetails::create([
                    'sale_id' => $sale->id,
                    'product_id' => $cart_item->id,
                    'product_name' => $cart_item->name,
                    'product_code' => $cart_item->options->code,
                    'quantity' => $cart_item->qty,
                    'price' => $cart_item->price * 100,
                    'unit_price' => $cart_item->options->unit_price * 100,
                    'sub_total' => $cart_item->options->sub_total * 100,
                    'product_discount_amount' => $cart_item->options->product_discount * 100,
                    'product_discount_type' => $cart_item->options->product_discount_type,
                    'product_tax_amount' => $cart_item->options->product_tax * 100,
                ]);

                if ($request->status == 'Shipped' || $request->status == 'Completado') {
                    $product = Product::findOrFail($cart_item->id);
                    $product->update([
                        'product_quantity' => $product->product_quantity - $cart_item->qty
                    ]);
                }
            }

            Cart::instance('sale')->destroy();
        });

        toast('Venta Actualizada!', 'info');

        return redirect()->route('sales.index');
    }


    public function destroy(Sale $sale) {
        abort_if(Gate::denies('delete_sales'), 403);

        $sale->delete();

        toast('Venta Eliminada!', 'warning');

        return redirect()->route('sales.index');
    }
}
