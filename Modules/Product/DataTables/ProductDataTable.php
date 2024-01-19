<?php

namespace Modules\Product\DataTables;

use Modules\Product\Entities\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{

    

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)->with('category')
            /*->addColumn('action', function ($data) {
                return view('product::products.partials.actions', compact('data'));
            })*/
            ->addColumn('action', function ($data) {
                $view = 'product::products.partials.actions';
                if ($data->trashed()) {
                    $view = 'product::products.partials.actions_trashed';
                }
                return view($view, compact('data'));
            })
            ->addColumn('product_image', function ($data) {
                $url = $data->getFirstMediaUrl('images', 'thumb');
                return '<img src="'.$url.'" border="0" width="50" class="img-thumbnail" align="center"/>';
            })
            ->addColumn('product_price', function ($data) {
                return format_currency($data->product_price);
            })
            ->addColumn('product_cost', function ($data) {
                return format_currency($data->product_cost);
            })
            ->addColumn('product_quantity', function ($data) {
                return $data->product_quantity . ' ' . $data->product_unit;
            })
            ->rawColumns(['product_image']);
    }

    public function query(Product $model)
    {
        $query = $model->newQuery()->with('category');

        if ($this->showTrash) {
            $query->onlyTrashed();
        }

        return $query;
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                'tr' .
                                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
                    ->orderBy(7)
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

    protected function getColumns()
    {
        return [
            Column::computed('product_image')
                ->title('Imagen')
                ->className('text-center align-middle'),

            Column::make('category.category_name')
                ->title('Categoría')
                ->className('text-center align-middle'),

            Column::make('product_code')
                ->title('Código')
                ->className('text-center align-middle'),

            Column::make('product_name')
                ->title('Nombre')
                ->className('text-center align-middle'),

            Column::computed('product_cost')
                ->title('Costo')
                ->className('text-center align-middle'),

            Column::computed('product_price')
                ->title('Precio')
                ->className('text-center align-middle'),

            Column::computed('product_quantity')
                ->title('Cantidad')
                ->className('text-center align-middle'),

            Column::computed('action')
                ->title('Acciones')
                ->exportable(false)
                ->printable(false)
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->title('Creado')
                ->visible(false)
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
