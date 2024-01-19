@if ($data->status == 'Pending')
    <span class="badge badge-info">
        Pendiente
    </span>
@else
    <span class="badge badge-success">
        Completado
    </span>
@endif
