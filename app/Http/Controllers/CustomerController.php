<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display the form to add a new customer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a new customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email'         => 'required|email|unique:customers,email',
            'phone'         => 'required|string'
        ]);

        $customer = Customer::create($data);

        if ($request->wantsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customer.create')->with('success', 'Customer added successfully!');
    }
    
    /**
     * List all customers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }
    
    /**
     * Show details for a specific customer.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.show', compact('customer'));
    }
    
    /**
     * Display edit form for a customer.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }
    
    /**
     * Update customer information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'email'         => 'required|email|unique:customers,email,' . $customer->id,
            'phone'         => 'required|string'
        ]);
        $customer->update($data);
        return redirect()->route('customer.show', ['id' => $customer->id])->with('success', 'Customer updated successfully!');
    }
}
