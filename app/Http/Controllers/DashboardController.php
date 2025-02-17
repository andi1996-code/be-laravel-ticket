<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');
        $todayRevenue = Order::whereDate('transaction_time', Carbon::today())->sum('total_price');
        $totalProducts = Product::count();

        // Sales data for the week
        $weekSalesData = $this->getSalesData(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());

        // Sales data for the month
        $monthSalesData = $this->getSalesData(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());

        // Sales data for the chart
        $salesData = $this->getSalesData(null, null);

        // Best-selling products data
        $bestSellingProductsData = Product::selectRaw('products.name, SUM(order_items.quantity) as total')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'total' => $product->total
                ];
            });

        return view('pages.dashboard', [
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'totalProducts' => $totalProducts,
            'weekSalesData' => $weekSalesData,
            'monthSalesData' => $monthSalesData,
            'salesData' => $salesData,
            'bestSellingProductsData' => $bestSellingProductsData
        ]);
    }

    private function getSalesData($startDate, $endDate)
    {
        $query = Order::selectRaw('DATE(transaction_time) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc');

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_time', [$startDate, $endDate]);
        }

        return $query->get()->map(function ($order) {
            return [
                'date' => $order->date,
                'total' => $order->total,
            ];
        });
    }
}
