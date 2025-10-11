@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoice #{{ $invoice->id }}</h2>
    <p><strong>Client:</strong> {{ $invoice->client->name ?? '-' }}</p>
    <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
    <p><strong>Date:</strong> {{ $invoice->created_at->format('Y-m-d') }}</p>
    <hr>
    <h4>Payments</h4>
   @if($invoice->payment)
        <ul>
            <li>{{ $invoice->payment->description }} - {{ number_format($invoice->payment->payment_amount, 2) }}</li>
        </ul>
    @else
        <p>No payment found.</p>
    @endif
    <a href="{{ route('invoices.pdf', ['invoice' => $invoice->invoice_id]) }}" class="btn btn-secondary">Download PDF</a>
    <a href="{{ route('invoices.index') }}" class="btn btn-light">Back</a>
</div>
@endsection