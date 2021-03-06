<?php

namespace App\Http\Controllers;

use App\Category;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return all categorues
     * @return Illuminate\Database\Eloquent\Collection;
     */
    public function index()
    {
        return Auth::user()->categories;
    }

    /**
     * Store a new Category
     * @param  Request $request
     * @return
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
        ]);

        Category::create(['name' => $request->name, 'user_id' => Auth::id()]);

        return response()->json([
            'message' => 'Created',
        ], 201);
    }
}
