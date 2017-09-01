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

    private $months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orders()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        $orders = [];
        $revenue = [];
        $months = [];

        for ($start_date; $start_date <= $end_date; $start_date ++){
            //$months[] = Carbon::createFromFormat('!m', $start_date)->format('F');
            array_push($months, $this->months[$start_date - 1]);
            $revenue[] = Auth::user()->orders()->totalRevenueByMonth($start_date);
            $orders[] = Auth::user()->expenses()->totalExpensesByMonth($start_date);
         }

        $chart = Charts::multi('bar', 'highcharts')
            ->title("Estadísticas")
            ->dimensions(0, 400)
            ->template("material")
            ->dataset('Ganancias', $revenue)
            ->dataset('Gastos', $orders)
            ->labels($months);

        return view('stats.index', compact('chart'));
    }
}
