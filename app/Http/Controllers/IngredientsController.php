<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngredientsController extends Controller
{

    /**
     * Create a new IngredientsController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get ingredientes
     * @return 
     */
    public function index()
    {
        return Auth::user()->ingredients;
    }

    /**
     * Update Ingredient
     * @param  Ingredient $ingredient 
     * @param  Request    $request    
     * @return                  
     */
    public function update(Ingredient $ingredient, Request $request)
    {
        $this->authorize('update', $ingredient);

        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        $ingredient->name = $request->name;
        $ingredient->save();

        return response()->json([
            'message' => 'OK',
        ], 200);
    }

    /**
     * Store ingredient
     * @param  Request $request 
     * @return            
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        Ingredient::create(['name' => $request->name, 'user_id' => Auth::id()]);

        return response()->json([
            'message' => 'Created',
        ], 201);
    }

    /**
     * Destroy ingredient
     * @param  Ingredient $ingredient 
     * @return                  
     */
    public function destroy(Ingredient $ingredient)
    {
        $this->authorize('update', $ingredient);

        $ingredient->delete();

        return response()->json([
            'message' => 'Deleted',
        ], 200);
    }
}
