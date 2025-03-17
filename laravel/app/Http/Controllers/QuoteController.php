<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Deal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    /**
     * Display the form to create a new quote.
     */
    public function create(Request $request)
    {
        $customers = Customer::all();
        $vehicles  = Vehicle::all();
        $selectedCustomer = $request->query('customer_id', null);
        $selectedVehicle  = $request->query('vehicle_id', null);
        $selectedDeal = $request->query('deal_id', null);
        
        // Get open deals for the selected customer, or all open deals if no customer selected
        $deals = $selectedCustomer 
            ? Deal::where('customer_id', $selectedCustomer)->where('status', 'open')->get()
            : Deal::where('status', 'open')->get();

        return view('quote.create', compact('customers', 'vehicles', 'selectedCustomer', 'selectedVehicle', 'selectedDeal', 'deals'));
    }

    /**
     * Store the quote and redirect to the review page.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'vehicle_id'      => 'required|exists:vehicles,id',
            'deal_id'        => 'nullable|exists:deals,id',
            'finance_type'    => 'required|in:Hire Purchase,Finance Lease,Operating Lease,Business Contract Hire',
            'contract_length' => 'required|integer',
            'annual_mileage'  => 'required|integer',
            'payment_profile' => 'required|string',
            'deposit'         => 'required|numeric',
            'monthly_payment' => 'required|numeric',
            'maintenance'     => 'nullable|boolean',
            'document_fee'    => 'required|numeric',
        ]);

        // Set maintenance to false if not provided
        $data['maintenance'] = isset($data['maintenance']) ? true : false;

        $quote = Quote::create($data);

        return redirect()->route('quote.review', ['id' => $quote->id])
            ->with('success', 'Quote created successfully!');
    }

    /**
     * Display the review page.
     */
    public function review($id)
    {
        $quote = Quote::findOrFail($id);
        return view('quote.review', compact('quote'));
    }

    /**
     * Send the quote via email.
     */
    public function send($id)
    {
        $quote = Quote::findOrFail($id);
        $customer = $quote->customer;

        // Check if the customer has a valid email address
        if (empty($customer->email)) {
            return redirect()->back()->with('error', 'Customer does not have a valid email address.');
        }
        
        // Generate the quote URL with the unique token
        $quoteUrl = route('quote.view', ['token' => $quote->token]);

        try {
            // Send HTML email with the blade template
            Mail::send('emails.quote', [
                'quote' => $quote, 
                'customer' => $customer, 
                'quoteUrl' => $quoteUrl
            ], function ($message) use ($customer, $quote) {
                $message->to($customer->email)
                        ->subject('Your Vehicle Quote: ' . $quote->vehicle->make . ' ' . $quote->vehicle->model);
            });
            
            // Track the email send
            $quote->tracking()->create([
                'event_type' => $quote->sent ? 'resent' : 'sent',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id()
            ]);
            
            // Update the quote status to indicate it was sent
            $quote->sent = true;
            $quote->save();

            // Update deal status if this quote is part of a deal
            if ($quote->deal_id) {
                $deal = $quote->deal;
                if ($deal->status === Deal::STATUS_INITIAL) {
                    $deal->update(['status' => Deal::STATUS_QUOTE_SENT]);
                }
            }

            return redirect()->back()->with('success', 'Quote successfully emailed to ' . $customer->email);
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Allow the customer to view the quote.
     */
    public function view($token)
    {
        $quote = Quote::where('token', $token)->firstOrFail();

        // Get the latest tracking record for this quote
        $latestTracking = $quote->tracking()
            ->whereNull('user_id') // Only consider customer interactions
            ->latest()
            ->first();

        // Determine if we should create a new tracking record
        $shouldTrack = true;
        
        if ($latestTracking) {
            // If the last interaction was less than 60 minutes ago and of the same type
            if ($latestTracking->event_type === 'view' && 
                $latestTracking->created_at->diffInMinutes(now()) < 60) {
                $shouldTrack = false;
            }
        }

        // Only create tracking record if conditions are met
        if ($shouldTrack) {
            $quote->tracking()->create([
                'event_type' => 'view',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id()
            ]);
        }

        // Check if the quote has expired
        if ($quote->status === 'expired' || now()->greaterThan($quote->expires_at)) {
            if ($quote->status !== 'expired') {
                $quote->update(['status' => 'expired']);
            }
            return view('quote.expired', compact('quote'));
        }

        return view('quote.view', compact('quote'));
    }

    /**
     * List all quotes with pagination.
     */
    public function index(Request $request)
    {
        $query = Quote::with(['customer', 'vehicle'])->orderBy('id', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
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

        $quotes = $query->paginate(10)->withQueryString();
        return view('quote.index', compact('quotes'));
    }

    /**
     * Confirm the order.
     */
    public function confirm($id)
    {
        $quote = Quote::findOrFail($id);
        
        // Store the token before updating status (in case we need to redirect back)
        $token = $quote->token;
        
        // Track the confirmation
        $quote->tracking()->create([
            'event_type' => 'confirm',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);
        
        // Update the quote status to 'confirmed'
        $quote->status = 'confirmed';
        $quote->save();

        // Update deal status if this quote is part of a deal
        if ($quote->deal_id) {
            $deal = $quote->deal;
            if ($deal->status === Deal::STATUS_QUOTE_SENT) {
                $deal->update(['status' => Deal::STATUS_QUOTE_ACCEPTED]);
            }
        }

        // Check if the user is logged in
        if (Auth::check()) {
            // If logged in, redirect to quote index page
            return redirect()->route('quote.index')->with('success', 'Quote confirmed and order processed!');
        } else {
            // If not logged in, redirect back to the quote view page
            return redirect()->route('quote.view', ['token' => $token]);
        }
    }

    /**
     * Update the status of a quote.
     *
     * Note: Users are allowed to change status to 'active' or 'expired' only.
     */
    public function updateStatus(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        $newStatus = $request->input('status');

        // Prevent manual setting to 'confirmed'
        if ($newStatus === 'confirmed') {
            return redirect()->back()->with('error', 'You cannot manually set the status to confirmed.');
        }

        if (!in_array($newStatus, ['active', 'expired'])) {
            return redirect()->back()->with('error', 'Invalid status value.');
        }

        $quote->status = $newStatus;
        $quote->save();

        return redirect()->back()->with('success', 'Quote status updated successfully!');
    }

    /**
     * Show the edit form for a quote.
     */
    public function edit($id)
    {
        $quote = Quote::with(['customer', 'vehicle'])->findOrFail($id);
        
        // Prevent editing sent quotes
        if ($quote->sent) {
            return redirect()->route('quote.review', ['id' => $id])
                           ->with('error', 'This quote has already been sent and cannot be edited. Please create a duplicate quote to make changes.');
        }

        return view('quote.edit', compact('quote'));
    }

    /**
     * Update the quote details.
     */
    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        
        // Prevent updating quotes that have been sent
        if ($quote->sent) {
            return redirect()->route('quote.review', ['id' => $id])
                           ->with('error', 'This quote has already been sent and cannot be edited. Please create a duplicate quote to make changes.');
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

        $quote->update($data);

        // Track the update
        $quote->tracking()->create([
            'event_type' => 'updated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id()
        ]);

        return redirect()->route('quote.review', ['id' => $id])
                       ->with('success', 'Quote updated successfully.');
    }

    /**
     * Duplicate a quote.
     */
    public function duplicate($id)
    {
        $originalQuote = Quote::findOrFail($id);
        
        // Create a new quote with the same details
        $newQuote = $originalQuote->replicate();
        $newQuote->sent = false;
        $newQuote->status = 'active';
        $newQuote->token = Str::uuid();
        $newQuote->expires_at = now()->addDays(28);
        $newQuote->save();

        // Track the duplication
        $newQuote->tracking()->create([
            'event_type' => 'duplicated',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'metadata' => json_encode(['duplicated_from' => $originalQuote->id])
        ]);

        return redirect()->route('quote.review', ['id' => $newQuote->id])
                       ->with('success', 'Quote duplicated successfully. You can now make changes to this new quote.');
    }
}
