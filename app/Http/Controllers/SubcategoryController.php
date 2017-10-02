<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcategoryController extends Controller
{
    /**
     * Create a new SubcategoryController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getByCategory(Category $category)
    {
        $this->authorize('view', $category);

        return Subcategory::where('category_id', $category->id)->get();
    }

    public function store(Request $request)
    {
        $this->authorize('updateSubcategory', Category::findOrFail($request->category_id));
        
        Subcategory::create($request->all());

        return response()->json([
            'message' => 'Created',
        ], 201);
    }

    public function update(Subcategory $subcategory, Request $request)
    {
        $this->authorize('update', $subcategory);
        
        $subcategory->name = $request->name;
        $subcategory->save();

        return response()->json([
            'message' => 'OK',
        ], 200);
    }
}
