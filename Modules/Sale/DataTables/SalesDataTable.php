<?php

namespace Modules\Sale\DataTables;


use Modules\Sale\Entities\Sale;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalesDataTable extends DataTable
{


    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
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
                return view('sale::partials.status', compact('data'));
            })
            ->addColumn('payment_status', function ($data) {
                return view('sale::partials.payment-status', compact('data'));
            })
            ->addColumn('created_at', function ($request) {
                return $request->created_at->locale('es_ES')->isoFormat('LL h:mm A');
            })

            ->addColumn('action', function ($data) {
                return view('sale::partials.actions', compact('data'));
            });
    }

    public function query(Sale $model) {

        return $model->newQuery()->orderByDesc('created_at');
    }

    public function html() {
        return $this->builder()
            ->setTableId('sales-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(8, 'desc')
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

            Column::make('customer_name')
            ->title('Cliente')
                ->className('text-center align-middle'),

            Column::computed('status')
            ->title('Estado')
                ->className('text-center align-middle'),

            Column::computed('total_amount')
            ->title('Monto Total')
                ->className('text-center align-middle'),

            Column::computed('paid_amount')
            ->title('Monto de Pago')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

            Column::computed('due_amount')
            ->title('Monto de cambio')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

            Column::computed('payment_status')
            ->title('Estado del pago')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

                Column::computed('created_at')
            ->title('Fecha')
                ->className('text-center align-middle'),


            Column::computed('action')
            ->title('Accion')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),
        ];
    }

    protected function filename(): string {
        return 'Sales_' . date('YmdHis');
    }
}
