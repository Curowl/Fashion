@php
    $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping
@endphp

<div class="modal fade" id="checkoutModal"  tabindex="-1" role="dialog"  aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> Confirmar Venta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="checkout-form" action="{{ route('app.pos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if (session()->has('checkout_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <span>{{ session('checkout_message') }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-7">
                            <input type="hidden" value="{{ $customer_id }}" name="customer_id">
                            <input type="hidden" value="{{ $global_tax }}" name="tax_percentage">
                            <input type="hidden" value="{{ $global_discount }}" name="discount_percentage">
                            <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="total_amount">Monto Total <span class="text-danger">*</span></label>
                                        <input id="total_amount" type="text" class="form-control" name="total_amount" value="{{ $total_with_shipping }}" readonly required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="paid_amount">Monto Recibido <span class="text-danger">*</span></label>
                                        <input id="paid_amount" type="text" class="form-control" name="paid_amount" value="{{ $total_with_shipping }}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="change_amount">Cambio</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">C$</span> </div>
                                            <input type="text" class="form-control" id="change_amount" name="change_amount" readonly >
                                        </div>
                                    </div>
                                </div>



                            </div>



                            <div class="form-group">
                                <label for="payment_method">Método de Pago <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_method" id="payment_method" required>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="note">Nota (Si es necesario)</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Total de Productos</th>
                                        <td>
                                                <span class="badge badge-success">
                                                    {{ Cart::instance($cart_instance)->count() }}
                                                </span>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                         <th>Impuesto de la Orden ({{ $global_tax }}%)</th>
                                         <td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</td>
                                     </tr>
                                     <tr>
                                         <th>Descuento ({{ $global_discount }}%)</th>
                                         <td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</td>
                                     </tr>
                                     <tr>
                                         <th>Envío</th>
                                         <input type="hidden" value="{{ $shipping }}" name="shipping_amount">
                                         <td>(+) {{ format_currency($shipping) }}</td>
                                     </tr>--}}
                                    <tr class="text-primary">
                                        <th>Total General</th>

                                        <th>
                                            (=) {{ format_currency($total_with_shipping) }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>


