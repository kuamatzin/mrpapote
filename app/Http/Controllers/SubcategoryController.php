<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function getByCategory(Category $category)
    {
        return Subcategory::where('category_id', $category->id)->get();
    }

    public function store(Request $request)
    {
        Subcategory::create($request->all());
    }

    public function update(Subcategory $subcategory, Request $request)
    {
        $subcategory->name = $request->name;
        $subcategory->save();
    }
}
