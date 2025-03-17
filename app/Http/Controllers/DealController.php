<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DealController extends Controller
{
    public function index(Request $request)
    {
        // Get view type from request or session, default to 'list'
        $viewType = $request->input('view', Session::get('deal_view_type', 'list'));
        
        // Save the view type preference in session
        Session::put('deal_view_type', $viewType);
        
        // For list view, we need paginated results
        $deals = Deal::with(['customer', 'quotes', 'orders'])
            ->when($request->has('search'), function($query) use ($request) {
                return $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('customer', function($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // For kanban view, we need deals grouped by status
        $dealsByStatus = [];
        
        if ($viewType === 'kanban') {
            foreach (Deal::getStatuses() as $status) {
                $dealsByStatus[$status] = Deal::with(['customer', 'quotes', 'orders'])
                    ->when($request->has('search'), function($query) use ($request) {
                        return $query->where('title', 'like', '%' . $request->search . '%')
                            ->orWhereHas('customer', function($query) use ($request) {
                                $query->where('name', 'like', '%' . $request->search . '%');
                            });
                    })
                    ->where('status', $status)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }
        
        return view('deal.index', compact('deals', 'dealsByStatus', 'viewType'));
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

        // Status transition check is now always allowed for manual updates
        // The canTransitionTo method in the Deal model now allows all valid statuses
        // for manual transitions

        $deal->update($data);

        return redirect()->route('deal.show', $deal->id)
            ->with('success', 'Deal updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $deal = Deal::findOrFail($id);
        $newStatus = $request->input('status');
        
        // Handle AJAX requests for kanban view
        if ($request->ajax()) {
            // Validate that the new status is valid
            if (!in_array($newStatus, Deal::getStatuses())) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid status value.'
                ], 422);
            }
            
            // The canTransitionTo method in the Deal model has been updated
            // to allow any valid status transition via AJAX, so no additional check is needed here
            
            $oldStatus = $deal->status;
            $deal->status = $newStatus;
            $deal->save();
            
            return response()->json([
                'success' => true,
                'message' => "Deal moved from '{$oldStatus}' to '{$newStatus}' successfully!",
                'deal' => $deal
            ]);
        }
        
        // Handle form submissions (non-AJAX)
        // Validate that the new status is valid
        if (!in_array($newStatus, Deal::getStatuses())) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        // Manual transitions are now allowed to any valid status
        // The canTransitionTo method in the Deal model handles this
        
        $oldStatus = $deal->status;
        $deal->status = $newStatus;
        $deal->save();

        return redirect()->back()->with('success', "Deal status updated from '{$oldStatus}' to '{$newStatus}' successfully!");
    }
}