<?php

namespace App\Http\Controllers;

use App\Product;
use App\Subcategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Create a new ProductController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all Products
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Auth::user()->products()->get();
    }

    /**
     * Get products by subcategory
     * @param  Subcategory $subcategory 
     * @return Product
     */
    public function getBySubcategory(Subcategory $subcategory)
    {
        $this->authorize('view', $subcategory);

        return Product::getBySubcategory($subcategory)->get();
    }

    /**
     * Store a product
     * @param  Request $request 
     * @return Product           
     */
    public function store(Request $request)
    {
        $this->authorize('view', Subcategory::findOrFail($request->subcategory_id));

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
        $this->authorize($product);

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
        $this->authorize($product);

        $product->delete();

        return response()->json([
            'message' => 'Deleted'
        ], 200);
    }

    /**
     * Get product ingredients
     * @param  Product $product 
     * @return array           
     */
    public function getProductIngredients(Product $product)
    {
        $this->authorize('view', $product);

        return $product->ingredients;
    }

    /**
     * Update product ingredients
     * @param  Product $product 
     * @param  Request $request 
     */
    public function updateIngredients(Product $product, Request $request)
    {
        $this->authorize('view', $product);

        $product->syncIngredients($request->ingredients);
    }
}
