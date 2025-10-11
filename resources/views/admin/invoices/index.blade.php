@extends('layouts.app')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4 text-center">{{__('message.Invoices')}}</h1>
    <div class="card">
      <div class="flex-grow-1 p-3">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">{{ __('message.Add Invoice') }}</a>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('message.Client') }}</th>
                <th>{{ __('message.Amount') }}</th>
                <th>{{ __('message.Paid') }}</th>
                <th>{{ __('message.Income Status') }}</th>
                <th>{{ __('message.Status') }}</th>
                <th>{{ __('message.Date') }}</th>
                <th>{{ __('message.Actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $invoice)
                <tr>
                  <td>{{ $invoice->invoice_id }}</td>
                  <td>{{ $invoice->income->client->full_name ?? '-' }}</td>
                  <td>${{ number_format($invoice->income->amount) }}</td>
                  <td>${{ number_format($invoice->payment_amount) }}</td>
                  <td>{{ $invoice->income->status }}</td>
                  <td>{{ ucfirst($invoice->status) }}</td>
                  <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('invoices.show', ['invoice' => $invoice->invoice_id]) }}"
                      class="btn btn-info btn-sm">{{ __('message.Details') }}</a>
                    <a href="{{ route('invoices.edit', ['invoice' => $invoice->invoice_id]) }}"
                      class="btn btn-warning btn-sm">{{ __('message.Edit') }}</a>
                    <a href="{{ route('invoices.pdf', ['invoice' => $invoice->invoice_id]) }}"
                      class="btn btn-secondary btn-sm">{{ __('message.Export as PDF') }}</a>
                    <form action="{{ route('invoices.destroy', ['invoice' => $invoice->invoice_id]) }}" method="POST"
                      style="display:inline;">
                      @csrf @method('DELETE')
                      <button class="btn btn-danger btn-sm"
                        onclick="return confirm('{{ __('message.Are you sure') }}')">{{ __('message.Delete') }}</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{ $invoices->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection