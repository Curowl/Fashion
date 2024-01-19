{{-- actions_trashed.blade.php --}}
@can('delete_products')
<button id="restore" class="btn btn-success btn-sm" onclick="
    event.preventDefault();
    if (confirm('¿Está seguro que quiere restaurar este producto?')) {
        document.getElementById('restore{{ $data->id }}').submit()
    }
    ">
    <i class="bi bi-arrow-counterclockwise"></i> Restaurar
    <form id="restore{{ $data->id }}" class="d-none" action="{{ route('products.restore', $data->id) }}" method="POST">
        @csrf
        @method('PUT')        
    </form>
</button>
@endcan
