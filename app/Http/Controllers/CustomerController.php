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

        // Always return JSON if it's an AJAX request
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($customer);
        }

        return redirect()->route('customer.create')->with('success', 'Customer added successfully!');
    }

    /**
     * List all customers with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('business_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->paginate(10)->withQueryString();
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

    /**
     * Delete a customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customerName = $customer->name;
        
        // Check if customer has associated quotes
        if ($customer->quotes()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete customer with associated quotes.');
        }
        
        $customer->delete();
        return redirect()->route('customer.index')->with('success', "Customer '{$customerName}' deleted successfully!");
    }
}
