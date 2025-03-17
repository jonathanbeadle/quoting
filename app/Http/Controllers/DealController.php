<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Customer;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['customer', 'quotes', 'orders'])->orderBy('created_at', 'desc')->paginate(10);
        return view('deal.index', compact('deals'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('deal.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $deal = Deal::create($data);

        return redirect()->route('deal.show', $deal->id)
            ->with('success', 'Deal created successfully.');
    }

    public function show($id)
    {
        $deal = Deal::with(['customer', 'quotes.vehicle', 'orders.vehicle'])->findOrFail($id);
        return view('deal.show', compact('deal'));
    }

    public function edit($id)
    {
        $deal = Deal::findOrFail($id);
        $customers = Customer::all();
        return view('deal.edit', compact('deal', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);
        
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:' . implode(',', Deal::getStatuses())
        ]);

        $oldStatus = $deal->status;
        
        // Check if status transition is allowed
        if ($oldStatus !== $data['status'] && !$deal->canTransitionTo($data['status'])) {
            return redirect()->back()
                ->with('error', "Cannot change deal status from '{$oldStatus}' to '{$data['status']}'.");
        }

        $deal->update($data);

        return redirect()->route('deal.show', $deal->id)
            ->with('success', 'Deal updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);
        $newStatus = $request->input('status');
        
        // Validate that the new status is valid
        if (!in_array($newStatus, Deal::getStatuses())) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        // Check if the transition is allowed
        if (!$deal->canTransitionTo($newStatus)) {
            return redirect()->back()->with('error', "Cannot change deal from '{$deal->status}' to '{$newStatus}'.");
        }

        $oldStatus = $deal->status;
        $deal->status = $newStatus;
        $deal->save();

        return redirect()->back()->with('success', "Deal status updated from '{$oldStatus}' to '{$newStatus}' successfully!");
    }
}