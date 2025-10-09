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
          <strong><p>Client: {{$income->client->client_fname}} {{$income->client->client_lname}}</p> </strong>
          <strong><p>Client Phone: {{$income->client->client_phone}}</p></strong>
          <strong><p>Category: {{$income->subcategory->category->category_name}}</p></strong>
          <strong><p>Subcategory: {{$income->subcategory->sub_name}}</p></strong>
          <strong><p>Amount: {{$income->amount}}</p></strong>
          <strong><p>Total Paid: {{$income->paid}}</p></strong>
          <strong><p>Remaining: {{$income->remaining}}</p></strong>

      </div>
      <div class="p-3 border flex-grow-1">
          <p><strong>Status: <span class="badge bg-{{ 
            $income->status == 'complete' ? 'success' : 
            ($income->status == 'partial' ? 'warning' : 'danger') 
        }}">
            {{ ucfirst($income->status) }}
        </span></strong>
            
          </p>
          <p><strong>Description:</strong></p>
          <p> {{$income->description}}</p>
          <p><strong>Next Payment Date: </strong></p>
          <p>{{$income->next_payment}}</p>
          <p><strong>Date: </strong></p>
          <p>{{ date('M d, Y', strtotime($income->created_at)) }}</p>
      </div>
  </div>

  <h4 class="mt-4">Payments</h4>
  <div class="table-responsive">

      <table id="sortableTable" class="table mt-3">
          <thead>
              <tr>
                  <th onclick="sortTable(0, this)">Payment Amount<span class="arrow"></span></th>
                  <th onclick="sortTable(1, this)">Description <span class="arrow"></span></th>
                  <th onclick="sortTable(2, this)">Payment Date <span class="arrow"></span></th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              @foreach($payments as $payment)
                  <tr>
                      <td> ${{$payment->payment_amount}}</td>
                      <td>{{$payment->trans_description}}</td>
                      <td>{{ date('M d, Y', strtotime($payment->created_at)) }}</td>
                      <td>
                      <button class='edit-payment-btn btn btn-primary'
                              data-bs-toggle='modal'
                              data-bs-target='#editPaymentModal'
                              data-payment='@json($payment)'
                              data-income='@json([
                              'income_id' => $income->income_id,
                              'next_payment' =>$income->next_payment
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
@include('admin.incomes.partials.edit-income',['income'=>$income,'clients'=>$clients,'subcategories'=>$subcategories,'categories'=>$categories])
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
         document.getElementById('edit_next_payment').value = income.next_payment;

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