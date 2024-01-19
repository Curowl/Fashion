@can('edit_suppliers')
    <a href="{{ route('suppliers.edit', $data->id) }}" class="btn btn-info btn-sm">
        <i class="bi bi-pencil"></i>
    </a>
@endcan
@can('show_suppliers')
    <a href="{{ route('suppliers.show', $data->id) }}" class="btn btn-primary btn-sm">
        <i class="bi bi-eye"></i>
    </a>
@endcan

@can('delete_suppliers')
    <button id="delete" class="btn btn-danger btn-sm" onclick="
        event.preventDefault();
        if (confirm('¿Está seguro que quiere eliminar este dato?')) {
        document.getElementById('destroy{{ $data->id }}').submit()
        }
        ">
        <i class="bi  bi-slash-circle"></i>
        <form id="destroy{{ $data->id }}" class="d-none" action="{{ route('suppliers.destroy', $data->id) }}" method="POST">
            @csrf
            @method('delete')
        </form>
    </button>
@endcan

