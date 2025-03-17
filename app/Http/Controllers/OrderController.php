<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Deal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Create an order based on a quote.
     *
     * This method copies details from the quote into a new order record.
     *
     * @param int $quoteId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFromQuote($quoteId)
    {
        $quote = Quote::findOrFail($quoteId);

        // Create a new order by copying data from the quote, including deal_id
        $order = Order::create([
            'quote_id'        => $quote->id,
            'customer_id'     => $quote->customer_id,
            'deal_id'        => $quote->deal_id,
            'vehicle_id'      => $quote->vehicle_id,
            'finance_type'    => $quote->finance_type,
            'contract_length' => $quote->contract_length,
            'annual_mileage'  => $quote->annual_mileage,
            'payment_profile' => $quote->payment_profile,
            'deposit'         => $quote->deposit,
            'monthly_payment' => $quote->monthly_payment,
            'maintenance'     => $quote->maintenance,
            'document_fee'    => $quote->document_fee,
            'status'          => 'pending',
            'token'           => Str::random(20),
            'expires_at'      => now()->addDays(28)
        ]);

        // Create a tracking record for the initial creation
        $order->tracking()->create([
            'event_type' => 'created',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);

        // Update deal status if this order is part of a deal
        if ($order->deal_id) {
            $deal = $order->deal;
            if ($deal->status === Deal::STATUS_QUOTE_ACCEPTED) {
                $deal->update(['status' => Deal::STATUS_FINANCE_PROCESS]);
            }
        }

        return redirect()->route('order.review', ['id' => $order->id])
                         ->with('success', 'Order created from quote successfully!');
    }

    /**
     * Display the form for editing order details.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function edit($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Prevent editing orders that have been sent
        if ($order->sent) {
            return redirect()->route('order.review', ['id' => $orderId])
                           ->with('error', 'This order has already been sent and cannot be edited. Please create a duplicate order to make changes.');
        }
        
        return view('order.edit', compact('order'));
    }

    /**
     * Update the order details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Prevent updating orders that have been sent
        if ($order->sent) {
            return redirect()->route('order.review', ['id' => $orderId])
                           ->with('error', 'This order has already been sent and cannot be edited. Please create a duplicate order to make changes.');
        }

        $data = $request->validate([
            'finance_type'    => 'required|string',
            'contract_length' => 'required|integer',
            'annual_mileage'  => 'required|integer',
            'payment_profile' => 'required|string',
            'deposit'         => 'required|numeric',
            'monthly_payment' => 'required|numeric',
            'maintenance'     => 'boolean',
            'document_fee'    => 'required|numeric'
        ]);

        // Set maintenance to false if not provided in the request
        if (!isset($data['maintenance'])) {
            $data['maintenance'] = false;
        }

        $order->update($data);

        // Track the update
        $order->tracking()->create([
            'event_type' => 'updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);

        return redirect()->route('order.review', ['id' => $orderId])
                         ->with('success', 'Order updated successfully.');
    }

    /**
     * Display the order details by numeric ID (staff view).
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function view($orderId)
    {
        $order = Order::with(['customer', 'vehicle', 'quote'])->findOrFail($orderId);
        
        // Create a tracking record
        $order->tracking()->create([
            'event_type' => 'view',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);
        
        return view('order.view', compact('order'));
    }

    /**
     * Display the order details using a token (public view).
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function viewByToken($token)
    {
        $order = Order::where('token', $token)
                ->with(['customer', 'vehicle'])
                ->firstOrFail();

        // Get the latest tracking record for this order
        $latestTracking = $order->tracking()
            ->whereNull('user_id') // Only consider customer interactions
            ->where('event_type', 'view')
            ->latest()
            ->first();

        // Determine if we should create a new tracking record
        $shouldTrack = true;
        
        if ($latestTracking) {
            // If the last interaction was less than 60 minutes ago
            if ($latestTracking->created_at->diffInMinutes(now()) < 60) {
                $shouldTrack = false;
            }
        }

        // Only create tracking record if conditions are met
        if ($shouldTrack) {
            $order->tracking()->create([
                'event_type' => 'view',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
                // No user_id because this is a public view
            ]);
        }

        // Check if the order has expired
        if (now()->greaterThan($order->expires_at)) {
            if ($order->status !== 'expired') {
                $order->update(['status' => 'expired']);
            }
            return view('order.expired', compact('order'));
        }

        return view('order.view', compact('order'));
    }

    /**
     * Confirm the order.
     */
    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        
        // Store the token before updating status (in case we need to redirect back)
        $token = $order->token;
        
        // Track the confirmation
        $order->tracking()->create([
            'event_type' => 'confirm',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);
        
        // Update the order status to 'confirmed'
        $order->status = 'confirmed';
        $order->save();

        // Update deal status if this order is part of a deal
        if ($order->deal_id) {
            $deal = $order->deal;
            if ($deal->status === Deal::STATUS_ORDER_SENT) {
                $deal->update(['status' => Deal::STATUS_ORDER_ACCEPTED]);
            }
        }

        // Check if the user is logged in
        if (Auth::check()) {
            // If logged in, redirect to order index page
            return redirect()->route('order.index')->with('success', 'Order confirmed successfully!');
        } else {
            // If not logged in, redirect back to the order view page
            return redirect()->route('order.viewByToken', ['token' => $token]);
        }
    }

    /**
     * List all orders with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'vehicle', 'quote'])->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('business_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhereHas('vehicle', function($q) use ($search) {
                    $q->where('make', 'like', '%' . $search . '%')
                      ->orWhere('model', 'like', '%' . $search . '%');
                })
                ->orWhere('finance_type', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('order.index', compact('orders'));
    }

    /**
     * Display the order for staff review.
     */
    public function review($id)
    {
        $order = Order::with(['customer', 'vehicle', 'quote', 'tracking'])->findOrFail($id);
        return view('order.review', compact('order'));
    }

    /**
     * Update the status of an order.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        // Validate that the new status is a valid status value
        if (!in_array($newStatus, Order::getStatuses())) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        // Check if the status transition is allowed
        if (!$order->canTransitionTo($newStatus)) {
            return redirect()->back()->with('error', "Cannot change order from '{$oldStatus}' to '{$newStatus}'.");
        }

        // Update the status and save
        $order->status = $newStatus;
        $order->save();

        // Track the status change
        $order->tracking()->create([
            'event_type' => 'status_change',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'metadata' => json_encode(['old_status' => $oldStatus, 'new_status' => $newStatus])
        ]);

        return redirect()->back()->with('success', "Order status updated from '{$oldStatus}' to '{$newStatus}' successfully!");
    }

    /**
     * Send the order via email.
     */
    public function send($id)
    {
        $order = Order::findOrFail($id);
        $customer = $order->customer;

        // Check if the customer has a valid email address
        if (empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer does not have a valid email address.');
        }
        
        // Generate the order URL with the unique token
        $orderUrl = route('order.viewByToken', ['token' => $order->token]);

        try {
            // Send HTML email with the blade template
            Mail::send('emails.order', [
                'order' => $order, 
                'customer' => $customer, 
                'orderUrl' => $orderUrl
            ], function ($message) use ($customer, $order) {
                $message->to($customer->email)
                        ->subject('Your Vehicle Order: ' . $order->vehicle->make . ' ' . $order->vehicle->model);
            });
            
            // Track the email send
            $order->tracking()->create([
                'event_type' => $order->sent ? 'resent' : 'sent',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id()
            ]);
            
            // Update the order sent status
            $order->sent = true;
            $order->save();

            // Update deal status if this order is part of a deal
            if ($order->deal_id) {
                $deal = $order->deal;
                if ($deal->status === Deal::STATUS_FINANCE_PROCESS) {
                    $deal->update(['status' => Deal::STATUS_ORDER_SENT]);
                }
            }

            return redirect()->back()
                           ->with('success', 'Order successfully emailed to ' . $customer->email);
                
        } catch (\Exception $e) {
            \Log::error('Failed to send order email: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate an existing order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($id)
    {
        $originalOrder = Order::findOrFail($id);
        
        // Create a new order by copying data from the original order
        $newOrder = Order::create([
            'quote_id'        => $originalOrder->quote_id,
            'customer_id'     => $originalOrder->customer_id,
            'vehicle_id'      => $originalOrder->vehicle_id,
            'finance_type'    => $originalOrder->finance_type,
            'contract_length' => $originalOrder->contract_length,
            'annual_mileage'  => $originalOrder->annual_mileage,
            'payment_profile' => $originalOrder->payment_profile,
            'deposit'         => $originalOrder->deposit,
            'monthly_payment' => $originalOrder->monthly_payment,
            'maintenance'     => $originalOrder->maintenance,
            'document_fee'    => $originalOrder->document_fee,
            'status'          => 'pending', // Always start as pending
            'token'           => Str::random(20), // Generate a new unique token
            'expires_at'      => now()->addDays(28), // Reset expiry period
            'sent'            => false // Mark as not sent
        ]);

        // Create a tracking record for the duplication
        $newOrder->tracking()->create([
            'event_type' => 'duplicated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'metadata' => json_encode(['duplicated_from' => $originalOrder->id])
        ]);

        return redirect()->route('order.review', ['id' => $newOrder->id])
                         ->with('success', 'Order has been duplicated successfully. You can now edit this copy.');
    }
}
