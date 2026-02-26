<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Entities\Product;


Route::get('products', 'ProductController@index')->name('products.index');

Route::get('products/{slug}', 'ProductController@show')->name('products.show');

Route::post('products/{id}/price', 'ProductPriceController@show')->name('products.price.show');

Route::get('suggestions', 'SuggestionController@index')->name('suggestions.index');

Route::get('api/products-by-category/{slug}', function ($slug) {

    return Product::whereHas('categories', function ($query) use ($slug) {
        $query->where('slug', $slug);
    })
    ->get()
    ->map(function ($product) {

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price->amount(),

           
            'image' => optional($product->base_image)['path'],
        ];
    });

});
