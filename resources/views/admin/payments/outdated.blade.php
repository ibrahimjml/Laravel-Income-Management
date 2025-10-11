@extends('layouts.app')

@section('title', 'outdated payments')

@section('content')

<div class="card-header text-center mb-3">
  <h3 class="fs-1 fw-bold text-info">{{__('message.Outdated Payments')}}</h3>
</div>
  <div class="row mb-3">
  <div class="col-sm-12">
      <input type="text" id="search-input" class="form-control border" placeholder="{{__('message.Search for payments')}}...">
  </div>
  </div>
<div class="d-flex flex-wrap gap-3 justify-content-start">
  @forelse($outdated_payments as $out)
    <div class="card shadow-sm border-0 flex-grow-1" style="min-width: 320px; max-width: 420px;">
      <div class="card-body d-flex gap-4">
        <div>
          <p class="fs-1 fw-bold text-info">{{$loop->index + 1}}</p>
        </div>
        <div>
        <h5 class=" mb-2 text-primary border-primary border-bottom pb-2">{{ $out->client->full_name }}</h5>
        <p class="mb-1"><strong>{{ __('message.Client Phone Number') }}:</strong> {{ $out->client->client_phone }}</p>
        <p class="mb-1"><strong>{{ __('message.Amount') }}:</strong> ${{ number_format($out->amount) }}</p>
        <p class="mb-1"><strong>Final:</strong> ${{ number_format($out->amount) }}</p>
        <p class="mb-1"><strong>{{ __('message.Paid') }}:</strong> ${{ $out->total_paid }}</p>
        <p class="mb-1"><strong>{{ __('message.Remaining') }}:</strong> ${{ $out->remaining}}</p>
        <p class="mb-1">
          <strong>{{ __('message.Status') }}:</strong>
          <span class="badge bg-{{ 
              $out->status == 'complete' ? 'success' : 
              ($out->status == 'partial' ? 'warning' : 'danger') 
          }}">
            {{ ucfirst($out->status) }}
          </span>
        </p>
        <p class="mb-0">
          <strong>{{ __('message.Due Date') }}:</strong> {{ date('M d, Y', strtotime($out->next_payment)) }}
        </p>
        </div>
      
      </div>
    </div>
  @empty
    <p class="text-muted">{{ __('message.No outdated payments') }}.</p>
  @endforelse
</div>


@endsection