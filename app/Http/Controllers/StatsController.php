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
            // Setup the chart settings
            ->title("Estadísticas")
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 400) // Width x Height
            // This defines a preset of colors already done:)
            ->template("material")
            ->values($values)
            ->elementLabel("Ventas")
            // Setup what the values mean
            ->labels($months);

        return view('stats.index', compact('chart'));
    }
}
