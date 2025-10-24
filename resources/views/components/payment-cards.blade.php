@props([
    'payments' => [],
    'title' => 'Payments',
    'count' => [],
    'headerColor' => 'success',
    'emptyMessage' => 'No payments found.'
])

<div class="card flex-grow-1" style="min-width: 320px; max-width: 420px; height: fit-content;">
    <div class="card-header bg-{{ $headerColor }} text-white text-center">
        <h3 class="text-whoute">{{ __($title) }} @if($count > 0)( {{ $count}} ) @endif</h3>
    </div>
    <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
        @forelse($payments as $index => $payment)
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-user me-1"></i> {{ __('message.Client Name') }}</div>
                    <div>{{ $payment->client->full_name }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-phone me-1"></i> {{ __('message.Client Phone Number') }}</div>
                    <div>{{ $payment->client->client_phone }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-dollar-sign me-1"></i> {{ __('message.Amount') }}</div>
                    <div>${{ $payment->amount }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-percent me-1"></i> {{ __('message.Final') }}</div>
                    <div>${{ $payment->final_amount }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-1"></i> {{ __('message.Total Paid') }}</div>
                    <div>${{ number_format($payment->total_paid) }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-1"></i> {{ __('message.Will Pay') }}</div>
                    <div>${{ number_format($payment->next_payment_amount) }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-wallet me-1"></i> {{ __('message.Remaining') }}</div>
                    <div>${{ number_format($payment->remaining) }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-check-circle me-1"></i> {{ __('message.Status') }}</div>
                    <div>
                        <span class="badge bg-{{ 
                            $payment->status == 'complete' ? 'success' : 
                            ($payment->status == 'partial' ? 'warning' : 'danger') 
                        }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-clock me-1"></i> {{ __('message.Due Date') }}</div>
                    <div>{{ date('M d, Y', strtotime($payment->next_payment)) }}
                        @if($payment->next_payment)
                       <span class="border-start border-dark border-2 ps-1">
                           @php
                               $paymentDate = $payment->next_payment->startOfDay();
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
            </div>

            @if($index < count($payments) - 1)
                <hr style="border: none; height: 1px; background-color: #000;">
            @endif
        @empty
            <p class="text-muted">{{ __($emptyMessage) }}</p>
        @endforelse
    </div>
</div>