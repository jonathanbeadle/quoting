<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        return view('quote.create', compact('customers', 'vehicles', 'selectedCustomer', 'selectedVehicle'));
    }

    /**
     * Store the quote and redirect to the review page.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'vehicle_id'      => 'required|exists:vehicles,id',
            'finance_type'    => 'required|in:Hire Purchase,Finance Lease,Operating Lease,Business Contract Hire',
            'contract_length' => 'required|integer',
            'annual_mileage'  => 'required|integer',
            'payment_profile' => 'required|string',
            'deposit'         => 'required|numeric',
            'monthly_payment' => 'required|numeric',
            'maintenance'     => 'required|boolean',
            'document_fee'    => 'required|numeric',
        ]);

        $quote = Quote::create($data);

        return redirect()->route('quote.review', ['id' => $quote->id]);
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
            return redirect()->route('quote.review', ['id' => $quote->id])
                ->with('error', 'Customer does not have a valid email address.');
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
            
            // Update the quote status to indicate it was sent
            $quote->sent = true;
            $quote->save();

            return redirect()->route('quote.review', ['id' => $quote->id])
                ->with('success', 'Quote successfully emailed to ' . $customer->email);
                
        } catch (\Exception $e) {
            return redirect()->route('quote.review', ['id' => $quote->id])
                ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Allow the customer to view the quote.
     */
    public function view($token)
    {
        $quote = Quote::where('token', $token)->firstOrFail();

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
        $query = Quote::with(['customer', 'vehicle']);

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
        // Update the quote status to 'confirmed'
        $quote->status = 'confirmed';
        $quote->save();

        return redirect()->route('quote.index')->with('success', 'Quote confirmed and order processed!');
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
}
