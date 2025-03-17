<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KanbanController extends Controller
{
    /**
     * Display the kanban board view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all deals, grouped by status
        $dealsByStatus = [];
        
        foreach (Deal::getStatuses() as $status) {
            $dealsByStatus[$status] = Deal::with(['customer', 'quotes', 'orders'])
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        
        return view('kanban.index', compact('dealsByStatus'));
    }
    
    /**
     * Update a deal's status via AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dealId' => 'required|integer|exists:deals,id',
            'newStatus' => 'required|string|in:' . implode(',', Deal::getStatuses()),
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $deal = Deal::findOrFail($request->dealId);
        $oldStatus = $deal->status;
        $newStatus = $request->newStatus;
        
        // Check if the transition is allowed
        if (!$deal->canTransitionTo($newStatus) && $oldStatus !== $newStatus) {
            return response()->json([
                'success' => false, 
                'message' => "Cannot move deal from '{$oldStatus}' to '{$newStatus}'",
            ], 422);
        }
        
        // Update the deal status
        $deal->status = $newStatus;
        $deal->save();
        
        return response()->json([
            'success' => true,
            'message' => "Deal moved from '{$oldStatus}' to '{$newStatus}' successfully!",
            'deal' => $deal
        ]);
    }
}