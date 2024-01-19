<div class="btn-group" role="group">
    @if ((auth()->user()->id === $data->usuario_id || auth()->user()->can('delete_expenses')))
        <a href="{{ route('turnos.show', $data->id) }}" class="btn btn-sm btn-info mr-1" title="Ver">
            Ver
        </a>
    @endif

    @if ($data->estado === 'abierto' && (auth()->user()->id === $data->usuario_id || auth()->user()->can('delete_expenses')))
        <a href="{{ route('turnos.cerrar.show', $data->id) }}" class="btn btn-sm btn-danger" title="Cerrar">
            Cerrar
        </a>
    @endif
</div>

