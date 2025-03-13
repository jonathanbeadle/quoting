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
        // Use the unique token in the URL instead of the quote ID
        $quoteUrl = route('quote.view', ['token' => $quote->token]);

        Mail::raw("Dear {$customer->name},\n\nPlease view your quote here: $quoteUrl\n\nThank you.", function ($message) use ($customer) {
            $message->to($customer->email)
                    ->subject('Your Vehicle Quote');
        });

        $quote->sent = true;
        $quote->save();

        return redirect()->route('quote.review', ['id' => $quote->id])->with('success', 'Quote sent successfully!');
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
     * List all quotes.
     */
    public function index()
    {
        $quotes = Quote::with(['customer', 'vehicle'])->get();
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
