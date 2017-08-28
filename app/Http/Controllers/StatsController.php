<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Carbon\Carbon;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class StatsController extends Controller
{
    public function orders()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        $values = [];
        $months = [];

        for ($start_date; $start_date <= $end_date; $start_date ++){
            $months[] = Carbon::createFromFormat('!m', $start_date)->format('F');
            array_push($values, Auth::user()->orders()->whereMonth('created_at', '=', $start_date)->count());
        }


        $chart = Charts::create('bar', 'highcharts')
            ->title("Estadísticas")
            ->dimensions(0, 400)
            ->template("material")
            ->values($values)
            ->elementLabel("Ventas")
            ->labels($months);

        return view('stats.index', compact('chart'));
    }
}
