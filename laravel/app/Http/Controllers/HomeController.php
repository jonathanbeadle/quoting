<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\QuoteTracking;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index(Request $request)
    {
        // Search functionality
        $searchResults = null;
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $searchResults = Customer::where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('business_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('phone', 'like', '%' . $searchTerm . '%')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
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
        
        // Get latest customer interactions (where user_id is null)
        $customerInteractions = QuoteTracking::whereNull('user_id')
            ->with(['quote.customer', 'quote.vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
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
            'recentOrders',
            'customerInteractions',
            'searchResults'
        ));
    }
}
