<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Order;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        // Summary counts
        $totalQuotes    = Quote::count();
        $sentQuotes     = Quote::where('sent', true)->count();
        $totalCustomers = Customer::count();
        $totalVehicles  = Vehicle::count();
        $totalOrders    = Order::count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        
        // Recent records (limit to 5 each)
        $recentQuotes    = Quote::with(['customer', 'vehicle'])->orderBy('created_at', 'desc')->take(5)->get();
        $recentCustomers = Customer::orderBy('created_at', 'desc')->take(5)->get();
        $recentVehicles  = Vehicle::orderBy('created_at', 'desc')->take(5)->get();
        $recentOrders    = Order::with(['customer', 'vehicle', 'quote'])->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('home', compact(
            'totalQuotes',
            'sentQuotes',
            'totalCustomers',
            'totalVehicles',
            'totalOrders',
            'confirmedOrders',
            'recentQuotes',
            'recentCustomers',
            'recentVehicles',
            'recentOrders'
        ));
    }
}
