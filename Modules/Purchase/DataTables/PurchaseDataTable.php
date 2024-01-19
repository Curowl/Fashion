<?php

namespace Modules\Purchase\DataTables;

use Modules\Purchase\Entities\Purchase;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PurchaseDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->addColumn('total_amount', function ($data) {
                return format_currency($data->total_amount);
            })
            ->addColumn('paid_amount', function ($data) {
                return format_currency($data->paid_amount);
            })
            ->addColumn('due_amount', function ($data) {
                return format_currency($data->due_amount);
            })
           ->addColumn('status', function ($data) {
                return view('purchase::partials.status', compact('data'));
            })
            ->addColumn('payment_status', function ($data) {
                return view('purchase::partials.payment-status', compact('data'));
            })
            ->addColumn('action', function ($data) {
                return view('purchase::partials.actions', compact('data'));
            });
    }

    public function query(Purchase $model) {
        return $model->newQuery()->orderBy('created_at', 'desc');
    }

    public function html() {
        return $this->builder()
            ->setTableId('purchases-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(8)
            ->language([
                'lengthMenu' => 'Mostrar _MENU_ entradas por página',
                'zeroRecords' => 'No se encontraron registros coincidentes',
                'info' => 'Mostrando _START_ a _END_ de _TOTAL_ entradas',
                'infoEmpty' => 'Mostrando 0 a 0 de 0 entradas',
                'infoFiltered' => '(filtrado de _MAX_ entradas totales)',
                'search' => 'Buscar:',
                'paginate' => [
                    'first' => 'Primero',
                    'last' => 'Último',
                    'next' => 'Siguiente',
                    'previous' => 'Anterior',
                ],
            ])
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Imprimir'),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Resetear'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Recargar')
            );
    }

    protected function getColumns() {
        return [
            Column::make('reference')
                ->title('Referencia')
                ->className('text-center align-middle'),

            Column::make('supplier_name')
                ->title('Proveedor')
                ->className('text-center align-middle'),

            Column::computed('status')
                ->title('Estado')
                ->className('text-center align-middle'),

            Column::computed('total_amount')
                ->title('Monto Total')
                ->className('text-center align-middle'),

            Column::computed('paid_amount')
                ->title('Monto pagado')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

            Column::computed('due_amount')
                ->title('Monto de Débito')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false)->printable(false),

            Column::make('created_at')
                ->title('Creado'),


            Column::computed('action')
                ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->title('Creado')
                ->visible(false),

            Column::computed('payment_status')
                ->title('Estado del pago')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false)->printable(false),


        ];
    }

    protected function filename(): string {
        return 'Purchase_' . date('YmdHis');
    }
}
