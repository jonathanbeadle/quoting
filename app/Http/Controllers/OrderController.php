<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Create an order based on a confirmed quote.
     *
     * This method copies details from the quote into a new order record,
     * setting its initial status to 'pending' so that the details can be edited.
     *
     * @param int $quoteId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFromQuote($quoteId)
    {
        $quote = Quote::findOrFail($quoteId);

        // Create a new order by copying data from the quote.
        $order = Order::create([
            'quote_id'        => $quote->id,
            'customer_id'     => $quote->customer_id,
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
        ]);

        // Redirect to the order edit form to allow updating details.
        return redirect()->route('order.edit', $order->id)
                         ->with('success', 'Order created from quote. Please review and update details.');
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

        $data = $request->validate([
            'finance_type'    => 'required|string',
            'contract_length' => 'required|integer',
            'annual_mileage'  => 'required|integer',
            'payment_profile' => 'required|string',
            'deposit'         => 'required|numeric',
            'monthly_payment' => 'required|numeric',
            'maintenance'     => 'required|boolean',
            'document_fee'    => 'required|numeric'
        ]);

        $order->update($data);

        return redirect()->route('order.view', $order->id)
                         ->with('success', 'Order updated successfully.');
    }

    /**
     * Display the finalized order details by numeric ID.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function view($orderId)
    {
        $order = Order::with(['customer', 'vehicle', 'quote'])->findOrFail($orderId);
        return view('order.view', compact('order'));
    }

    /**
     * Display the finalized order details using a token (read-only view).
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function viewByToken($token)
    {
        $order = Order::with(['customer', 'vehicle', 'quote'])
                      ->where('token', $token)
                      ->firstOrFail();
        return view('order.view', compact('order'));
    }

    /**
     * Confirm the order (final customer acceptance).
     *
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($orderId)
    {
        $order = Order::findOrFail($orderId);
        // Additional validation can be added here if needed.
        $order->status = 'confirmed';
        $order->save();

        return redirect()->route('order.view', $order->id)
                         ->with('success', 'Order confirmed successfully.');
    }

    public function index()
{
    $orders = Order::with(['customer', 'vehicle', 'quote'])->get();
    return view('order.index', compact('orders'));
}

}
