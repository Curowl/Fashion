<?php

namespace Modules\People\DataTables;


use Modules\People\Entities\Customer;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomersDataTable extends DataTable
{

    public function dataTable($query) {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($data) {
                return view('people::customers.partials.actions', compact('data'));
            });
    }

    public function query(Customer $model) {
        return $model->newQuery();
    }

    public function html() {
        return $this->builder()
            ->setTableId('customers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                       'tr' .
                                 <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(4)
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
            Column::make('customer_name')
            ->title('Nombre')
                ->className('text-center align-middle'),

            Column::make('customer_email')
            ->title('Email')
                ->className('text-center align-middle'),

            Column::make('customer_phone')
            ->title('Telefono')
                ->className('text-center align-middle'),

            Column::computed('action')
            ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->visible(false)
        ];
    }

    protected function filename(): string {
        return 'Customers_' . date('YmdHis');
    }
}
