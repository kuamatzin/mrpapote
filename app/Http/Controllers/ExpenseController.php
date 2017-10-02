<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('expenses.index');
    }

    public function get()
    {
        $month = Input::get('month');
        return Auth::user()->expenses()->whereMonth('buy_date', $month)->orderBy('buy_date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:50',
            'total' => 'required|numeric',
            'date' => 'required|date'
        ]);

        Auth::user()->expenses()->create([
            'description' => $request->description,
            'total' => $request->total,
            'buy_date' => $request->date
        ]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
    }

    public function update(Expense $expense, Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:50',
            'total' => 'required|numeric',
            'buy_date' => 'required|date'
        ]);

        $expense->file = $this->storeOrDelete($expense, $request);
        $expense->description = $request->description;
        $expense->total = $request->total;
        $expense->buy_date = $request->buy_date;
        $expense->save();
        
        return $expense;

    }

    private function storeOrDelete($expense, $request)
    {
        return $expense->file ? $this->deleteImage($expense, $request) : $this->storeImage($request);
    }

    private function storeImage($request)
    {
        return $request->hasFile('file') ? $request->file('file')->store('expenses', 'public') : null;
    }

    private function deleteImage($expense, $request)
    {
        unlink(storage_path() . '/app/public/' .$expense->file);
        return $this->storeImage($request);
    }


}
