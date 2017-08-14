<?php

namespace App\Http\Controllers;

use App\Product;
use App\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Get products by subcategory
     * @param  Subcategory $subcategory 
     * @return Product
     */
    public function getBySubcategory(Subcategory $subcategory)
    {
        return Product::where('subcategory_id', $subcategory->id)->with('subcategory.category')->with('ingredients')->get();
    }

    /**
     * Store a product
     * @param  Request $request 
     * @return Product           
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'price' => 'required|numeric'
        ]);

        $request['active'] = true;
        $request['personalizable'] = false;

        return Product::create($request->all());
    }

    /**
     * Update a product
     * @param  Product $product 
     * @param  Request $request 
     * @return Product
     */
    public function update(Product $product, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'price' => 'required|numeric'
        ]);
        
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        return $product;
    }

    /**
     * Delete a product
     * @param  Product $product 
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }

    /**
     * Get product ingredients
     * @param  Product $product 
     * @return array           
     */
    public function getProductIngredients(Product $product)
    {
        return $product->ingredients;
    }

    /**
     * Update product ingredients
     * @param  Product $product 
     * @param  Request $request 
     */
    public function updateIngredients(Product $product, Request $request)
    {
        $product->syncIngredients($request->ingredients);
    }
}
