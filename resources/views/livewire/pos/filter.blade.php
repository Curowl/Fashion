<div>
    <div class="form-row">
        <div class="col-md-7">
            <div class="form-group">
                <label>Categoría del Producto</label>
                <select wire:model.live="category" class="form-control">
                    <option value="">Todos los Productos</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>Número de productos</label>
                <select wire:model.live="showCount" class="form-control">
                    <option value="9">9 Productos</option>
                    <option value="15">15 Productos</option>
                    <option value="21">21 Productos</option>
                    <option value="30">30 Productos</option>
                    <option value="">Todos los Productos</option>
                </select>
            </div>
        </div>
    </div>
</div>
