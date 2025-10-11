@extends('layouts.app')

@section('title', 'upcoming payments')

@section('content')

  <div class="card-header text-center mb-3">
    <h3 class="fs-1 fw-bold text-info">{{__('message.Upcoming Payments')}}</h3>
  </div>
  <div class="row mb-3">
    <div class="col-sm-12">
      <input type="text" id="search-input" class="form-control border"
        placeholder="{{__('message.Search for payments')}}...">
    </div>
  </div>

    <div class="d-flex flex-wrap gap-3 justify-content-start">
      @forelse($upcoming_payments as $index => $upcoming)
        <div class="card shadow-sm border-0 flex-grow-1" style="min-width: 320px; max-width: 420px;">
          <div class="card-body d-flex gap-4">
            <div>
              <p class="fs-1 fw-bold text-info">{{$loop->index + 1}}</p>
            </div>
            <div>
              <h5 class=" mb-2 text-primary border-primary border-bottom pb-2">{{$upcoming->client->full_name}}</h5>
              <p class="mb-1"><strong>{{__('message.Client Phone Number')}}: </strong> {{$upcoming->client->client_phone}}  </p>
              <p class="mb-1"><strong>{{__('message.Amount')}}: </strong> ${{number_format($upcoming->amount)}}</p>
              <p class="mb-1"><strong>Final: </strong> ${{number_format($upcoming->final_amount)}}</p>
              <p class="mb-1"><strong>{{__('message.Paid')}}: </strong> ${{$upcoming->total_paid}}</p>
              <p class="mb-1">
                <strong>{{ __('message.Remaining') }}:</strong>${{ number_format($upcoming->remaining) }}
              </p>
              <p class="mb-1">
                <strong>{{ __('message.Status') }}:</strong>
                <span class="badge bg-{{ 
                  $upcoming->status == 'complete' ? 'success' :
                   ($upcoming->status == 'partial' ? 'warning' : 'danger') 
                   }}">
                  {{ ucfirst($upcoming->status) }}
                </span>
              </p>
              <strong>{{__('message.Due Date')}}: {{ date('M d, Y', strtotime($upcoming->next_payment)) }}</strong>
            </div>
          </div>
          
         @empty
          <p class="text-muted">{{__('message.No upcoming payments')}}.</p>
        @endforelse
      </div>
    </div>

@endsection