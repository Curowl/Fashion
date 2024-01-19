<?php

namespace Modules\SalesReturn\DataTables;

use Modules\SalesReturn\Entities\SaleReturn;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SaleReturnsDataTable extends DataTable
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
                return view('salesreturn::partials.status', compact('data'));
            })
            ->addColumn('payment_status', function ($data) {
                return view('salesreturn::partials.payment-status', compact('data'));
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->addColumn('action', function ($data) {
                return view('salesreturn::partials.actions', compact('data'));
            });
    }

    public function query(SaleReturn $model) {
        return $model->newQuery()->orderBy('created_at', 'desc');
    }

    public function html() {
        return $this->builder()
            ->setTableId('sale-returns-table')
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
            ->title('referencia')
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
            ->title('Monto del cambio')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

            Column::computed('payment_status')
            ->title('Estado del pago')
                ->className('text-center align-middle')
                ->visible(false)->exportable(false) ->printable(false),

            Column::make('created_at')
                ->title('Creado'),

            Column::computed('action')
            ->title('Accion')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),



        ];
    }

    protected function filename(): string {
        return 'SaleReturns_' . date('YmdHis');
    }
}
