<?php

// app/DataTables/TurnoCajaDataTable.php

namespace App\DataTables;

use App\Models\TurnoCaja;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;



class TurnoCajaDataTable extends DataTable
{

    private $cerradas;

    public function setCerradas($cerradas)
    {
        $this->cerradas = $cerradas;

        return $this;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fecha_apertura', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->editColumn('fecha_cierre', function ($data) {
                return $data->fecha_cierre
                    ? ($data->fecha_cierre instanceof \Carbon\Carbon
                        ? $data->fecha_cierre->format('d/m/Y h:i:s A')
                        : date('d/m/Y h:i:s A', strtotime($data->fecha_cierre)))
                    : '<span class="badge badge-success">Caja Abierta</span>';
            })

            ->addColumn('usuario_name', function ($data) {
                return $data->usuario ? $data->usuario->name : 'Usuario no disponible';
            })
            ->addColumn('monto_inicial', function ($data) {
                return number_format($data->monto_inicial, 2, ',', '.');
            })
            ->addColumn('monto_final', function ($data) {
                return number_format($data->monto_final, 2, ',', '.');
            })
            ->addColumn('estado', function ($data) {
                $badgeClass = $data->estado === 'abierto' ? 'badge-success' : 'badge-danger';
                return "<span class='badge $badgeClass'>" . ucfirst($data->estado) . '</span>';
            })->rawColumns(['estado'])
            ->addColumn('actions', function ($data) {
                return view('turnos.datatables.actions', compact('data'));
            })
            ->rawColumns(['estado', 'actions', 'fecha_cierre']);
    }


    public function query(TurnoCaja $model)
    {
        $query = $model->newQuery()->with('usuario')->orderByDesc('created_at');

        // Verifica el estado del toggle y ajusta la consulta en consecuencia
        if ($this->cerradas) {
            $query->where('estado', 'cerrado');
        } else {
            $query->where('estado', 'abierto');
        }

        return $query;
    }




    public function html()
    {


        return $this->builder()
            ->setTableId('turno_caja-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                'tr' .
                <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
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
            Column::make('fecha_apertura')->title('Fecha de apertura'),
            Column::make('fecha_cierre')->title('Fecha de cierre'),
            Column::make('usuario_name')->title('Usuario')->className('text-center align-middle'),
            Column::make('monto_inicial')->title('Monto inicial')->className('text-center align-middle'),
            Column::make('monto_final')->title('Monto final')->className('text-center align-middle'),
            Column::make('estado')->title('Estado')->className('text-center align-middle'),
            Column::computed('actions')
                ->title('Acciones')
                ->orderable(false)
                ->searchable(false)
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),

        ];
    }



    protected function filename(): string {
        return 'Turno_Caja_' . date('YmdHis');
    }


}

