<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Vehicle;

class HomeController extends Controller
{
    public function index()
    {
        // Summary counts
        $totalQuotes    = Quote::count();
        $sentQuotes     = Quote::where('sent', true)->count();
        $totalCustomers = Customer::count();
        $totalVehicles  = Vehicle::count();
        
        // Recent records (limit to 5 each)
        $recentQuotes    = Quote::with(['customer', 'vehicle'])->orderBy('created_at', 'desc')->take(5)->get();
        $recentCustomers = Customer::orderBy('created_at', 'desc')->take(5)->get();
        $recentVehicles  = Vehicle::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('home', compact(
            'totalQuotes',
            'sentQuotes',
            'totalCustomers',
            'totalVehicles',
            'recentQuotes',
            'recentCustomers',
            'recentVehicles'
        ));
    }
}
