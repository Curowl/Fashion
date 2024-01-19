

<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit="generateReport">
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input wire:model="start_date" type="date" class="form-control" name="start_date">
                                    @error('start_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fecha de Fin <span class="text-danger">*</span></label>
                                    <input wire:model="end_date" type="date" class="form-control" name="end_date">
                                    @error('end_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <select wire:model="customer_id" class="form-control" name="customer_id">
                                        <option value="">Seleccionar Cliente</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" hidden>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select wire:model="sale_status" class="form-control" name="sale_status">
                                        <option value="Completado" selected>Completado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Estado de Pago</label>
                                    <select wire:model="payment_status" class="form-control" name="payment_status">
                                        <option value="Pagado" selected>Pagado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                Filtrar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button wire:click="generatePdf" class="btn btn-secondary mb-3">
                        <strong>DESCARGAR PDF</strong>
                    </button>
                    <table class="table table-bordered table-striped text-center mb-0">
                        <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                        </div>
                        <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                           {{-- <th>Pagado</th>
                            <th>Pendiente</th>
                            <th>Estado de Pago</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M, Y') }}</td>
                                <td>{{ $sale->reference }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>
                                    @if ($sale->status == 'Pendiente')
                                        <span class="badge badge-info">
                                    {{ $sale->status }}
                                </span>
                                    @elseif ($sale->status == 'Enviado')
                                        <span class="badge badge-primary">
                                    {{ $sale->status }}
                                </span>
                                    @else
                                        <span class="badge badge-success">
                                    {{ $sale->status }}
                                </span>
                                    @endif
                                </td>
                                <td>{{ format_currency($sale->total_amount) }}</td>
                             {{--  <td>{{ format_currency($sale->paid_amount) }}</td>
                                <td>{{ format_currency($sale->due_amount) }}</td>
                                <td>
                                    @if ($sale->payment_status == 'Parcial')
                                        <span class="badge badge-warning">
                                    {{ $sale->payment_status }}
                                </span>
                                    @elseif ($sale->payment_status == 'Pagado')
                                        <span class="badge badge-success">
                                    {{ $sale->payment_status }}
                                </span>
                                    @else
                                        <span class="badge badge-danger">
                                    {{ $sale->payment_status }}
                                </span>
                                    @endif
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <span class="text-danger">¡No hay datos de ventas disponibles!</span>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div @class(['mt-3' => $sales->hasPages()])>
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
