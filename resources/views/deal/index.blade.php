@extends('layouts.app')

@section('title', 'All Deals')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Deals ({{ $deals->total() }})</h1>
        <div class="d-flex align-items-center">
            {{ $deals->links() }}
            <form action="{{ route('deal.index') }}" method="GET" class="d-flex ms-3">
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
                    <th style="width: 20%">Customer</th>
                    <th style="width: 20%">Title</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Quotes</th>
                    <th style="width: 10%">Orders</th>
                    <th style="width: 10%">Created</th>
                    <th style="width: 15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deals as $deal)
                <tr>
                    <td>{{ $deal->id }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('customer.show', ['id' => $deal->customer->id]) }}">
                            {{ $deal->customer->name }}
                        </a>
                    </td>
                    <td>{{ $deal->title }}</td>
                    <td>
                        <span class="badge bg-{{ $deal->status === Deal::STATUS_CLOSED ? 'secondary' : 'primary' }}">
                            {{ $deal->status }}
                        </span>
                    </td>
                    <td>{{ $deal->quotes->count() }}</td>
                    <td>{{ $deal->orders->count() }}</td>
                    <td>{{ $deal->created_at->format('d/m/Y') }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('deal.show', ['id' => $deal->id]) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('deal.edit', ['id' => $deal->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="{{ route('deal.create') }}" class="btn btn-primary">Create New Deal</a>
    </div>
</div>
@endsection