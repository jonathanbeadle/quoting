@extends('layouts.app')

@section('title', 'All Orders')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Orders ({{ $orders->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $orders->links() }}
            <form action="{{ route('order.index') }}" method="GET" class="d-flex ms-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th style="width: 5%">ID</th>
                    <th style="width: 12%">Customer</th>
                    <th style="width: 15%">Vehicle</th>
                    <th style="width: 8%">Contract</th>
                    <th style="width: 8%">Monthly</th>
                    <th style="width: 8%">Created</th>
                    <th style="width: 15%">Actions</th>
                    <th style="width: 12%">Status Update</th>
                    <th style="width: 7%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td class="text-nowrap">{{ $order->customer->name ?? 'N/A' }}</td>
                    <td class="text-nowrap">{{ $order->vehicle->make ?? '' }} {{ $order->vehicle->model ?? '' }}</td>
                    <td class="text-nowrap">{{ $order->contract_length }}m</td>
                    <td class="text-nowrap">£{{ number_format($order->monthly_payment, 2) }}</td>
                    <td class="text-nowrap">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('order.viewByToken', ['token' => $order->token]) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                        <a href="{{ route('order.review', ['id' => $order->id]) }}" class="btn btn-sm btn-warning">Review</a>
                        <form action="{{ route('order.send', ['id' => $order->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" title="Send order to customer's email" {{ empty($order->customer->email) ? 'disabled' : '' }}>
                                <i class="fas fa-envelope"></i> Send
                            </button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('order.updateStatus', $order->id) }}" class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                <option value="{{ $order->status }}" selected>{{ ucfirst($order->status) }}</option>
                                
                                @if($order->status === 'pending')
                                    <option value="active">Activate</option>
                                    <option value="expired">Expire</option>
                                @elseif($order->status === 'active')
                                    <option value="pending">Set Pending</option>
                                    <option value="expired">Expire</option>
                                @elseif($order->status === 'expired')
                                    <option value="pending">Set Pending</option>
                                    <option value="active">Activate</option>
                                @elseif($order->status === 'confirmed')
                                    <option value="pending">Unconfirm</option>
                                    <option value="expired">Expire</option>
                                @endif
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                    </td>
                    <td class="text-nowrap">
                        {{ ucfirst($order->status) }}
                        @if($order->sent)
                            <span class="badge bg-success ms-1" title="Email sent to customer">Sent</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
