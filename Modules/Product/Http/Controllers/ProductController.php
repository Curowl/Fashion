<?php

namespace Modules\Product\Http\Controllers;

use Modules\Product\DataTables\ProductDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Upload\Entities\Upload;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductController extends Controller
{

    /*public function index(ProductDataTable $dataTable) {
        abort_if(Gate::denies('access_products'), 403);



        return $dataTable->render('product::products.index');
    }*/

    public function index(ProductDataTable $dataTable, Request $request) {
        abort_if(Gate::denies('access_products'), 403);

        $showTrash = $request->has('showTrash') && $request->input('showTrash') == 'true';

        return $dataTable->with(['showTrash' => $showTrash])->render('product::products.index', compact('showTrash'));
    }


     /**
     * Restaurar el producto eliminado.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        abort_if(Gate::denies('delete_products'), 403);
        // Buscar el producto eliminado suavemente con el ID proporcionado
        $product = Product::withTrashed()->find($id);

        // Verificar si se encontró el producto
        if ($product) {
            // Restaurar el producto
            $product->restore();
            toast('Producto Restaurado', 'success');
        } else {
            // Producto no encontrado
            toast('Producto no encontrado', 'error');
        }

        // Redirigir a la vista de productos
        return redirect()->route('products.index');
    }





    public function create() {
        abort_if(Gate::denies('create_products'), 403);

        return view('product::products.create');
    }


    public function store(StoreProductRequest $request) {
        $product = Product::create($request->except('document'));

        if ($request->has('document')) {
            foreach ($request->input('document', []) as $file) {
                $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
            }
        }

        toast('Se creó un producto nuevo!', 'success');

        return redirect()->route('products.index');
    }


    public function show(Product $product) {
        abort_if(Gate::denies('show_products'), 403);

        return view('product::products.show', compact('product'));
    }


    public function edit(Product $product) {
        abort_if(Gate::denies('edit_products'), 403);

        return view('product::products.edit', compact('product'));
    }


    public function update(UpdateProductRequest $request, Product $product) {
        $product->update($request->except('document'));

        if ($request->has('document')) {
            if (count($product->getMedia('images')) > 0) {
                foreach ($product->getMedia('images') as $media) {
                    if (!in_array($media->file_name, $request->input('document', []))) {
                        $media->delete();
                    }
                }
            }

            $media = $product->getMedia('images')->pluck('file_name')->toArray();

            foreach ($request->input('document', []) as $file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    $product->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
                }
            }
        }

        toast('Producto Actualizado!', 'info');

        return redirect()->route('products.index');
    }


    public function destroy(Product $product) {
        abort_if(Gate::denies('delete_products'), 403);

        // Verificar si el producto ya ha sido eliminado
        if ($product->trashed()) {
            toast('Este producto ya ha sido eliminado.', 'warning');
            return redirect()->route('products.index');
        }

        // Eliminar el producto
        $product->delete();

        toast('Producto Deshabilitado!', 'warning');

        return redirect()->route('products.index');
    }


    public function getDataTable(Request $request, ProductDataTable $dataTable)
    {
        $dataTable->setShowTrash($request->get('showTrash') == 'true');
        return $dataTable->ajax();
    }
}
