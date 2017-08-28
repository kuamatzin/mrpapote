<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Carbon\Carbon;
use ConsoleTVs\Charts\Facades\Charts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class StatsController extends Controller
{
    public function orders()
    {
        $inicio = Input::get('inicio');
        $final = Input::get('final');

        $chart = Charts::create('bar', 'highcharts')
            // Setup the chart settings
            ->title("Estadísticas")
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 400) // Width x Height
            // This defines a preset of colors already done:)
            ->template("material");

        $values = [];
        $months = [];
        for ($inicio; $inicio <= $final; $inicio ++){
            $months[] = Carbon::createFromFormat('!m', $inicio)->format('F');
            array_push($values, Order::whereMonth('created_at', '=', $inicio)->count());
        }


        $chart
            ->values($values)
            ->elementLabel("Ventas")
            // Setup what the values mean
            ->labels($months);

        return view('stats.index', compact('chart'));
    }
}
