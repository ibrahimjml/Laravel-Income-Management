@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
  <h1 class="mb-4 payments">{{__('message.Payments')}}</h1>
  <div id="content" class="d-flex flex-wrap gap-3 mt-5">
      <!-- Outdated Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px;height: fit-content;">
          <div class="card-header bg-danger text-white text-center">
              <h3 class="text-white">{{__('message.Outdated Payments')}}</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($outdated_payments as $index => $out)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>{{__('message.Client Name')}}: </strong>{{$out->client->full_name}}<br>
                      <strong>{{__('message.Client Phone Number')}}: {{$out->client->client_phone}}</strong><br>
                      <strong>{{__('message.Amount')}}: {{$out->amount}}</strong> $<br>
                      <strong>Final: {{$out->final_amount}}</strong> $<br>
                      <strong>{{__('message.Paid')}}: </strong> ${{$out->total_paid}}<br>
                      <strong>{{__('message.Remaining')}}: </strong> $ {{$out->remaining}}<br>
                      <strong>{{__('message.Status')}}: </strong> <span class="badge bg-{{ 
                        $out->status == 'complete' ? 'success' : 
                        ($out->status == 'partial' ? 'warning' : 'danger') 
                    }}">
                        {{ ucfirst($out->status) }}
                    </span><br>
                      <strong>{{__('message.Due Date')}}: {{ date('M d, Y', strtotime($out->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($outdated_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">{{__('message.No outdated payments')}}.</p>
              @endforelse
          </div>
      </div>

      <!-- Today's Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px;height: fit-content;">
          <div class="card-header bg-primary text-white text-center">
              <h3 class="text-white">{{__('message.Today\'s Payments')}}</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($today_payments as $index => $today)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>{{__('message.Client Name')}}: </strong>{{$today->client->full_name}}<br>
                      <strong>{{__('message.Client Phone Number')}}: </strong> {{$today->client->client_phone}}<br>
                      <strong>{{__('message.Amount')}}: </strong> ${{$today->amount}}<br>
                      <strong>Final: </strong> ${{$today->final_amount}}<br>
                      <strong>{{__('message.Paid')}}: </strong> ${{$today->total_paid}}<br>
                      <strong>{{__('message.Remaining')}}: </strong> ${{ number_format($today->remaining) }}<br>
                      <strong>{{__('message.Status')}}: </strong> <span class="badge bg-{{ 
                        $today->status == 'complete' ? 'success' : 
                        ($today->status == 'partial' ? 'warning' : 'danger') 
                    }}">
                        {{ ucfirst($today->status) }}
                    </span><br>
                      <strong>{{__('message.Due Date')}}: {{ date('M d, Y', strtotime($today->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($today_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">{{__('message.No payments due today')}}.</p>
              @endforelse
          </div>
      </div>

      <!-- Upcoming Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px; height: fit-content;">
          <div class="card-header bg-success text-white text-center">
              <h3 class="text-white">{{__('message.Upcoming Payments')}}</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($upcoming_payments as $index => $upcoming)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>{{__('message.Client Name')}}: </strong>{{$upcoming->client->full_name}}<br>
                      <strong>{{__('message.Client Phone Number')}}: </strong> {{$upcoming->client->client_phone}}<br>
                      <strong>{{__('message.Amount')}}: </strong> ${{$upcoming->amount}}<br>
                      <strong>final: </strong> ${{$upcoming->final_amount}}<br>
                      <strong>{{__('message.Paid')}}: </strong> ${{ number_format($upcoming->total_paid) }}<br>
                      <strong>{{__('message.Remaining')}}: </strong> ${{ number_format($upcoming->remaining) }}<br>
                      <strong>{{__('message.Status')}}: </strong> <span class="badge bg-{{ 
                        $upcoming->status == 'complete' ? 'success' : 
                        ($upcoming->status == 'partial' ? 'warning' : 'danger') 
                    }}">
                        {{ ucfirst($upcoming->status) }}
                    </span><br>
                      <strong>{{__('message.Due Date')}}: {{ date('M d, Y', strtotime($upcoming->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($upcoming_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">{{__('message.No upcoming payments')}}.</p>
              @endforelse
          </div>
      </div>
  </div>
</div>
@endsection