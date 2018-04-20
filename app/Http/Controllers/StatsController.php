<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Services\ExcelGenerator;
use App\Subcategory;
use Carbon\Carbon;
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

    public function index()
    {
        return view('stats.index');
    }

    public function generalStats()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $export_excel = Input::get('export_excel');

        $data = $this->buildArrayWithGeneralStats($start_date, $end_date);
        
        return $export_excel ? ExcelGenerator::create($data) : $data;
    }

    private function buildArrayWithGeneralStats($start_month, $end_month)
    {
        for ($start_month; $start_month <= $end_month; $start_month ++) {
            $months[] = $this->months[$start_month - 1];
            $revenue[] = (int)Auth::user()->orders()->getByMonth($start_month)->sum('total');
            $expenses[] = Auth::user()->expenses()->getByMonth($start_month)->sum('total');
            $utilities[] = $revenue[count($revenue) - 1] - $expenses[count($expenses) - 1];
        }

        return ['months' => $months, 'expenses' => $expenses, 'revenue' => $revenue, 'utilities' => $utilities];
    }

    public function getByProduct(Product $product, $start_month = null, $end_month = null)
    {
        $start_date = $start_month ? $start_month : Input::get('start_date');
        $end_date = $end_month ? $end_month : Input::get('end_date');

        return $this->buildArrayWithProductStats($product, $start_date, $end_date);
    }

    private function buildArrayWithProductStats($product, $start_month, $end_month)
    {
        for ($start_month; $start_month <= $end_month; $start_month ++) {
            $months[] = $this->months[$start_month - 1];
            $orders[] = $num_orders = (int)$product->stats($start_month)->count();
            $revenue[] = $product->price * $num_orders;
        }

        return ['months' => $months, 'orders' => $orders, 'revenue' => $revenue];
    }

    public function getBySubcategory(Subcategory $subcategory)
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        return $this->buildArrayWithSubcategoryStats($subcategory, $start_date, $end_date);
    }

    private function buildArrayWithSubcategoryStats(Subcategory $subcategory, $start_month, $end_month)
    {
        for ($start_month; $start_month <= $end_month; $start_month ++) {
            $months[] = $this->months[$start_month - 1];
            $subcategory_stats = $subcategory->stats($start_month);
            $orders[] = $subcategory_stats['orders'];
            $revenue[] = $subcategory_stats['revenue'];
        }

        return ['months' => $months, 'orders' => $orders, 'revenue' => $revenue];
    }

    public function getBySubcategoryProducts(Subcategory $subcategory)
    {
        $month_date = Input::get('month');

        foreach ($subcategory->products as $product) {
            $products[] = $product->name;
            $product_stats = $this->getByProduct($product, $month_date, $month_date);
            $orders[] = $product_stats['orders'][0];
            $revenue[] = $product_stats['revenue'][0];
        }

        return ['products' => $products, 'orders' => $orders, 'revenue' => $revenue];
    }
}
