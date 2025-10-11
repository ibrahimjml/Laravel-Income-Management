@extends('layouts.app')

@section('title', 'today payments')

@section('content')

<div class="card-header text-center mb-3">
  <h3 class="fs-1 fw-bold text-info">{{__('message.Today\'s Payments')}}</h3>
</div>
  <div class="row mb-3">
  <div class="col-sm-12">
      <input type="text" id="search-input" class="form-control border" placeholder="{{__('message.Search for payments')}}...">
  </div>
  </div>

     <div class="d-flex flex-wrap gap-3 justify-content-start">
      @forelse($today_payments as $index => $today)
        <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
        <div class="card-body d-flex gap-4">
        <div>
          <p class="fs-1 fw-bold text-info">{{$loop->index + 1}}</p>
        </div>
        <div>
            <h5 class=" mb-2 text-primary border-primary border-bottom pb-2">{{$today->client->full_name}}</h5>
            <p class="mb-1"><strong>{{__('message.Client Phone Number')}}: </strong> {{$today->client->client_phone}}</p>
            <p class="mb-1"><strong>{{__('message.Amount')}}: </strong> ${{number_format($today->amount)}}</p>
            <p class="mb-1"><strong>Final: </strong> ${{number_format($today->final_amount)}}</p>
            <p class="mb-1"><strong>{{__('message.Paid')}}: </strong> ${{$today->total_paid}}</p>
            <p class="mb-1"><strong>{{ __('message.Remaining') }}:</strong>${{ number_format($today->remaining) }}</p>
            <p class="mb-1">
            <strong>{{ __('message.Status') }}:</strong>
            <span class="badge bg-{{ 
              $today->status == 'complete' ? 'success' : 
              ($today->status == 'partial' ? 'warning' : 'danger') 
               }}">
            {{ ucfirst($today->status) }}
            </span>
             </p>
            <strong>{{__('message.Due Date')}}: {{ date('M d, Y', strtotime($today->next_payment)) }}</strong>
          </div>
          @empty
            <p class="text-muted">{{__('message.No payments due today')}}.</p>
        </div>
      @endforelse
    </div>
    </div>

@endsection