@extends('layouts.app')

@section('title', 'Upcoming Payments')

@section('content')

<div class="card-header text-center mb-3">
  <h3 class="fs-1 fw-bold text-info">{{__('message.Upcoming Payments')}}</h3>
</div>
  <div class="row mb-3">
  <div class="col-sm-12"><!-- start filters -->
      <input type="text" id="search-input" class="form-control border" placeholder="{{__('message.Search for payments')}}...">
  </div><!-- start filters -->
  </div>
<div class="d-flex flex-wrap gap-3 justify-content-start">
  @forelse($upcoming_payments as $upcoming)
    <div class="card shadow-sm border-0 flex-grow-1" style="min-width: 320px; max-width: 420px;">
      <div class="card-body d-flex gap-4"><!-- start card body -->
        <div>
          <p class="fs-1 fw-bold text-info">{{$loop->index + 1}}</p>
        </div>
        <div class="mb-3 w-100"><!-- start payment card -->
         <!-- client full name -->
          <a href="{{ route('details',['income'=>$upcoming->income_id]) }}" style="text-decoration: none;">
          <h5 class=" mb-2 text-primary border-primary border-bottom pb-2">{{ $upcoming->client->full_name }}</h5>
          </a>
                <!-- client phone -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-phone me-1"></i> {{ __('message.Client Phone Number') }}</div>
                    <div>{{ $upcoming->client->client_phone }}</div>
                </div>
                 <!-- client income amount -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-dollar-sign me-1"></i> {{ __('message.Amount') }}</div>
                    <div>${{ number_format($upcoming->amount) }}</div>
                </div>
                <!-- client final amount after discount -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-percent me-1"></i> {{ __('message.Final') }}</div>
                    <div>{{ $upcoming->final_amount >0 ? '$ '.$upcoming->final_amount : 'N/A'}}</div>
                </div>
                <!-- client total paid -->
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-1"></i> {{ __('message.Total Paid') }}</div>
                    <div>${{ number_format($upcoming->total_paid) }}</div>
                </div>
                <!-- clinet willing to pay $ -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-1"></i> {{ __('message.Will Pay') }}</div>
                    <div>${{ number_format($upcoming->next_payment_amount) }}</div>
                </div>
                <!-- client income remaining -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-wallet me-1"></i> {{ __('message.Remaining') }}</div>
                    <div>${{ number_format($upcoming->remaining) }}</div>
                </div>
                <!-- client status income -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-check-circle me-1"></i> {{ __('message.Status') }}</div>
                    <div>
                        <span class="badge bg-{{ 
                            $upcoming->status == \App\Enums\IncomeStatus::COMPLETE ? 'success' : 
                            ($upcoming->status == \App\Enums\IncomeStatus::PARTIAL ? 'warning' : 'danger') 
                        }}">
                            {{ $upcoming->status->label() }}
                        </span>
                    </div>
                </div>
                <!-- client next payment date diffindays -->
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-clock me-1"></i> {{ __('message.Due Date') }}</div>
                    <div>{{ date('M d, Y', strtotime($upcoming->next_payment)) }}
                        @if($upcoming->next_payment)
                       <span class="border-start border-dark border-2 ps-1">
                           @php
                               $paymentDate = $upcoming->next_payment->startOfDay();
                               $today = now()->startOfDay();
                           @endphp
                           
                           @if($paymentDate->lt($today))
                               <span class="text-danger">
                                   {{ $paymentDate->diffInDays($today) }} days overdue
                               </span>
                           @elseif($paymentDate->gt($today))
                               <span class="text-success">
                                    in {{ $today->diffInDays($paymentDate) }} days
                               </span>
                           @else
                               <span class="text-warning fw-bold">
                                   Due today
                               </span>
                           @endif
                       </span>
                       @endif
                    </div>
                </div>
        </div><!-- end payment card -->
      
      </div><!-- end card body -->
    </div>
  @empty
    <p class="text-muted">{{ __('message.No upcoming payments') }}.</p>
  @endforelse
</div>
{{ $upcoming_payments->links() }}


@endsection