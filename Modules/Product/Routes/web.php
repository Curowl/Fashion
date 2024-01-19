<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {
    //Print Barcode
    Route::get('/products/print-barcode', 'BarcodeController@printBarcode')->name('barcode.print');

    //Product
    //Route::resource('products', 'ProductController');
    Route::resource('products', 'ProductController');

    //Product Category
    Route::resource('product-categories', 'CategoriesController')->except('create', 'show');
    
    // Ruta adicional para restaurar un producto eliminado
    Route::put('products/{product}/restore', 'ProductController@restore')->name('products.restore');

   
});

