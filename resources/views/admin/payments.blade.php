@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
  <h1 class="mb-4">Payments Schedule</h1>
  <div id="content" class="d-flex flex-wrap gap-3 mt-5">
      <!-- Outdated Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px;">
          <div class="card-header bg-danger text-white text-center">
              <h3 class="text-white">Outdated Payments</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($outdated_payments as $index => $out)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>Client Name:</strong>{{$out->client->client_fname}} {{$out->client->client_lname}}<br>
                      <strong>Client Phone Number: {{$out->client->client_phone}}</strong><br>
                      <strong>Amount: {{$out->amount}}</strong> $<br>
                      <strong>Paid:</strong> ${{$out->total_paid}}<br>
                      <strong>Remaining:</strong> $ {{$out->remaining}}<br>
                      <strong>Due Date: {{ date('M d, Y', strtotime($out->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($outdated_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">No outdated payments.</p>
              @endforelse
          </div>
      </div>

      <!-- Today's Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px;">
          <div class="card-header bg-primary text-white text-center">
              <h3 class="text-white">Today's Payments</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($today_payments as $index => $today)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>Client Name:</strong>{{$today->client->client_fname}} {{$today->client->client_lname}}<br>
                      <strong>Client Phone Number:</strong> {{$today->client->client_phone}}<br>
                      <strong>Amount:</strong> ${{$today->amount}}<br>
                      <strong>Paid:</strong> ${{$today->total_paid}}<br>
                      <strong>Remaining:</strong> ${{ number_format($today->remaining - $today->total_paid, 2) }}<br>
                      <strong>Due Date: {{ date('M d, Y', strtotime($today->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($outdated_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">No payments due today.</p>
              @endforelse
          </div>
      </div>

      <!-- Upcoming Payments Card -->
      <div class="card flex-grow-1" style="min-width: 320px; max-width: 420px;">
          <div class="card-header bg-success text-white text-center">
              <h3 class="text-white">Upcoming Payments</h3>
          </div>
          <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
            @forelse($upcoming_payments as $index => $upcoming)
              <div class="mb-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                  <div>
                      <strong>Client Name:</strong>{{$upcoming->client->client_fname}} {{$upcoming->client->client_lname}}<br>
                      <strong>Client Phone Number:</strong> {{$upcoming->client->client_phone}}<br>
                      <strong>Amount:</strong> ${{$upcoming->amount}}<br>
                      <strong>Paid:</strong> ${{$upcoming->total_paid}}<br>
                      <strong>Remaining:</strong> ${{ number_format($upcoming->remaining - $upcoming->total_paid, 2) }}<br>
                      <strong>Status:</strong> <span class="badge bg-{{ 
                        $upcoming->status == 'complete' ? 'success' : 
                        ($upcoming->status == 'partial' ? 'warning' : 'danger') 
                    }}">
                        {{ ucfirst($upcoming->status) }}
                    </span><br>
                      <strong>Due Date: {{ date('M d, Y', strtotime($upcoming->next_payment)) }}</strong>
                  </div>
              </div>
              @if($index < count($outdated_payments) - 1)
              <hr style="border: none; height: 1px; background-color: #000;">
              @endif
              @empty
              <p class="text-muted">No upcoming payments.</p>
              @endforelse
          </div>
      </div>
  </div>
</div>
@endsection