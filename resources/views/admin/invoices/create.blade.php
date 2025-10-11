@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ __('message.Add Income') }}</h2>
    <form method="GET" action="{{ route('invoices.create') }}" class="mb-4">
        <div class="row">
            <div class="col">
                <label for="client_id" class="form-label">{{ __('message.Client') }}</label>
                <select name="client_id" id="client_id" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('message.Select Client') }}</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->client_id }}" @if(isset($selectedClientId) && $selectedClientId == $client->client_id) selected @endif>
                            {{ $client->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="income_id" class="form-label">{{ __('message.Incomes') }}</label>
                <select name="income_id" id="income_id" class="form-control" onchange="this.form.submit()">
                    <option value="">{{ __('message.Select Income') }}</option>
                    @foreach($incomes as $income)
                        <option value="{{ $income->income_id }}" @if(isset($selectedIncomeId) && $selectedIncomeId == $income->income_id) selected @endif>
                            {{ $income->description }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        <input type="hidden" name="client_id" value="{{ $selectedClientId }}">
        <input type="hidden" name="income_id" value="{{ $selectedIncomeId }}">
        <div class="mb-3">
            <label for="payment_id" class="form-label">{{ __('message.Payments') }}</label>
            <select name="payment_id" id="payment_id" class="form-control" required>
                <option value="">{{ __('message.Select Payment') }}</option>
                @foreach($payments as $payment)
                    <option value="{{ $payment->payment_id }}">{{ $payment->description }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success" @if(!$selectedClientId || !$selectedIncomeId) disabled @endif>{{ __('message.Add Income') }}</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-light">{{ __('message.Cancel') }}</a>
    </form>
</div>
@endsection