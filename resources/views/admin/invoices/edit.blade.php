@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Edit Invoice #{{ $invoice->invoice_id }}</h2>
    <form action="{{ route('invoices.update', ['invoice' => $invoice->invoice_id]) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Client</label>
            <input type="text" class="form-control" value="{{ $invoice->income->client->full_name ?? '-' }}" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $invoice->income->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="unpaid" @if($invoice->status == 'unpaid') selected @endif>Unpaid</option>
                <option value="partial" @if($invoice->status == 'partial') selected @endif>Partial</option>
                <option value="paid" @if($invoice->status == 'paid') selected @endif>Paid</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection