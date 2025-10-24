@extends('layouts.app')

@section('title', 'Details-'.$income->client->client_fname)

@section('content')
<div id="content" class="d-flex flex-column">
<div class="flex-grow-1 p-3">
  <h3 class="text-center mb-4">Income Details</h3>
  <div class="d-flex justify-content-between mb-3">
      <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editIncomeModal">Edit
          Income</button>
      <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add
          Payment</button>
  </div>

  <div class="d-flex flex-wrap">
      <div class="p-3 border flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-1">
                <div><i class="fa fa-user me-2 text-secondary"></i> <b>{{ __('message.Client Name') }}</b></div>
                <b>{{ $income->client->full_name }}</b>
          </div>
        <div class="d-flex justify-content-between align-items-center mb-1">
                <div><i class="fa fa-phone me-2 text-secondary"></i><b>{{ __('message.Client Phone Number') }}</b> </div>
                <b>{{$income->client->client_phone}}</b>
          </div>
        <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-envelope me-2 text-secondary"></i><b>{{ __('message.Client Email') }}</b> </div>
                    <b>{{ $income->client->email }}</b>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-layer-group me-2 text-secondary"></i> <b>{{ __('message.Category') }}</b></div>
                    <b>{{$income->subcategory->category->category_name}}</b>
                </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-tags me-2 text-secondary"></i><b>{{ __('message.Subcategory') }}</b> </div>
                    <b>{{$income->subcategory->sub_name}}</b>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-dollar-sign me-3 text-secondary"></i><b> {{ __('message.Amount') }}</b></div>
                    <b>${{number_format($income->amount)}}</b>
          </div>
          @if(isset($income->discount->rate))
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-percent me-2 text-secondary"></i><b>{{ __('message.Discounts') }}</b> </div>
                    <b>%{{number_format($income->discount->rate)}} ~ ${{$income->final_amount}}</b>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-2 text-secondary"></i><b>{{ __('message.Total Paid') }}</b> </div>
                    <b>${{number_format($income->total_paid)}}</b>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-money-bill-wave me-2 text-secondary"></i><b>{{ __('message.Remaining') }}</b> </div>
                    <b>${{number_format($income->remaining)}}</b>
        </div>

      </div>
      <div class="p-3 border flex-grow-1">
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-check-circle me-2 text-secondary"></i><b>{{ __('message.Status') }}</b> </div>
                    <b class="badge bg-{{ 
            $income->status == 'complete' ? 'success' : 
            ($income->status == 'partial' ? 'warning' : 'danger') 
        }}">{{ ucfirst($income->status) }}</b>
        </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-info-circle me-2 text-secondary"></i><b>{{ __('message.Description') }}</b> </div>
                    <b>{{$income->description ?? 'N/A'}}</b>
        </div>
          <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-calendar-alt me-2 text-secondary"></i><b>{{ __('message.Next Payment Date') }}</b> </div>
                    <b>{{$income->next_payment?->format('M d, Y') ?? 'N/A'}}</b>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-1">
                    <div><i class="fa fa-calendar me-2 text-secondary"></i><b>{{ __('message.CreatedAt') }}</b> </div>
                    <b>{{ date('M d, Y', strtotime($income->created_at)) }}</b>
        </div>

      </div>
  </div>

  <h4 class="mt-4">Payments</h4>
  <div class="table-responsive">

      <table id="sortableTable" class="table mt-3">
          <thead>
              <tr>
                  <th onclick="sortTable(0, this)">Payment Amount<span class="arrow"></span></th>
                  <th onclick="sortTable(1, this)">Status<span class="arrow"></span></th>
                  <th onclick="sortTable(2, this)">Description <span class="arrow"></span></th>
                  <th onclick="sortTable(3, this)">CreatedAt <span class="arrow"></span></th>
                  <th onclick="sortTable(4, this)">Payment Due <span class="arrow"></span></th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              @foreach($payments as $payment)
                  <tr>
                      <td> ${{$payment->payment_amount}}</td>
                      <td> <span class="badge bg-{{ 
                            $payment->status == 'paid' ? 'success' : 
                            ($payment->status == 'unpaid' ? 'danger' : '') 
                              }}">
                      {{ ucfirst($payment->status) }}
                      </span>
                      </td>
                      <td>{{$payment->trans_description}}</td>
                      <td>{{ date('M d, Y', strtotime($payment->created_at)) }}</td>
                      <td>{{ $payment->next_payment?->format('M d, Y') ?? 'N/A' }}
                        @if($payment->next_payment && $payment->status == 'unpaid')
                       <span>
                           <i class="fas fa-clock text-warning"></i>
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
                      </td>
                      <td>
                      <button class='edit-payment-btn btn btn-primary'
                              data-bs-toggle='modal'
                              data-bs-target='#editPaymentModal'
                              data-payment='@json($payment)'
                              data-income='@json([
                              'income_id' => $income->income_id
                              ])'>
                        <span class="d-sm-inline d-none">{{__('message.Edit')}}</span>
                        <span class="d-inline d-sm-none">E</span>
                        </button>
                      </td>
                  </tr>
        @endforeach
          </tbody>
      </table>
  </div>
</div>
</div>
{{-- edit income model --}}
@include('admin.incomes.partials.edit-income',['income'=>$income,'clients'=>$clients,'subcategories'=>$subcategories])
{{-- add payment model --}}
@include('admin.payments.partials.add-payment',['income'=>$income])
{{-- edit payment model --}}
@include('admin.payments.partials.edit-payment',['income'=>$income])
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editPaymentModal = document.getElementById('editPaymentModal');
    const editPaymentForm = document.getElementById('editPaymentForm');
    
    function populateEditModal(payment,income) {

        document.getElementById('edit_payment_id').value = payment.payment_id;
        document.getElementById('edit_amount').value = payment.payment_amount;
        document.getElementById('edit_description').value = payment.trans_description;
        if (payment.next_payment) {
        const date = new Date(payment.next_payment);
        const formattedDate = date.toISOString().split('T')[0];
        document.getElementById('edit_next_payment').value = formattedDate ?? null;
          } 
        document.getElementById('edit_status').value = payment.status;


        editPaymentForm.action = `/admin/edit-payment/${payment.payment_id}/${income.income_id}`; 

    }
    
    document.querySelectorAll('[data-bs-target="#editPaymentModal"]').forEach(button => {
        button.addEventListener('click', function() {
            try {
                const paymentJson = this.getAttribute('data-payment');
                const incomeJson = this.getAttribute('data-income');
                const payment =  JSON.parse(this.dataset.payment);
                const income  = JSON.parse(this.dataset.income);

                populateEditModal(payment,income);
            } catch (error) {
                console.error('Error parsing payment data:', error);
                alert('error', 'Error loading payment data');
            }
        });
    });
    
    // fetch 
    if (editPaymentForm) {
        editPaymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST', 
                    headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                      'X-HTTP-Method-Override': 'PUT',
                      'Accept': 'application/json'
                    },
                    body: formData,
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('success', data.message);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(editPaymentModal);
                    modal.hide();

                        window.location.reload();
                    
                } else {
                    alert('error', data.message);
                }
                
            } catch (error) {
                console.error('Error:', error);
                alert('error', 'An error occurred while updating payment.');
            } 
        });
    }
    
});
</script>
@endpush