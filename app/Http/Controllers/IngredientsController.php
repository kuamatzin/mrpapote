<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredientsController extends Controller
{
    public function index()
    {
        return Auth::user()->ingredients;
    }

    public function update(Ingredient $ingredient, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        $ingredient->name = $request->name;
        $ingredient->save();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        return Ingredient::create(['name' => $request->name, 'user_id' => Auth::id()]);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
    }
}
