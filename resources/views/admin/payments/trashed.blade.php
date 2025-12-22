@extends('layouts.app')

@section('title', 'Recover Payments')

@section('content')
  <h1 class="mb-4 text-center">{{ __('message.Recover Payments') }}</h1>
<div class="card">
  <div class="table-responsive">
    <table id="sortableTable" class="table">
      <thead>
        <tr>
          <th onclick="sortTable(0, this)"># <span class="arrow"></span></th>
          <th onclick="sortTable(1, this)">{{__('message.Client')}} <span class="arrow"></span></th>
          <th onclick="sortTable(1, this)">{{__('message.Category')}} <span class="arrow"></span></th>
          <th onclick="sortTable(2, this)">{{__('message.Amount')}} <span class="arrow"></span></th>
          <th onclick="sortTable(3, this)">{{__('message.Description')}} <span class="arrow"></span></th>
          <th onclick="sortTable(4, this)">{{__('message.Status')}} <span class="arrow"></span></th>
          <th>{{__('message.Actions')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $index => $payment)
          <tr>
            <td>{{$index + 1}}</td>
            <td>{{$payment->income->client->full_name}}</td>
            <td>{{$payment->income->subcategory->name.' | '.$payment->income->subcategory->category->name}}</td>
            <td>${{$payment->payment_amount}}</td>
            <td>{{$payment->trans_description ?? 'N/A'}}</td>
            <td>
              <span class="badge bg-{{ 
                $payment->status == \App\Enums\PaymentStatus::PAID ? 'success' :
                 ($payment->status == \App\Enums\PaymentStatus::UNPAID ? 'danger' : '')
                }}">
               {{ $payment->status->label() }}
              </span>
            </td>
            <td>
              <div class="flex gap-1">
                <button class='btn btn-primary recovery-btn' 
                        data-payment-id="{{ $payment->payment_id }}" 
                        data-client-name="{{ $payment->income->client->full_name }}">
                  <span class="d-sm-inline d-none">{{__('message.Recover')}}</span>
                  <span class="d-inline d-sm-none">R</span>
              </button>

                <button class='btn btn-danger force-delete-btn' 
                        data-payment-id="{{ $payment->payment_id }}" 
                        data-client-name="{{ $payment->income->client->full_name }}">
                  <span class="d-sm-inline d-none">{{__('message.Delete')}}</span>
                  <span class="d-inline d-sm-none">D</span>
              </button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>  

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle recover button
    const recoveryButtons = document.querySelectorAll('.recovery-btn');
    
    recoveryButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const paymentId = this.dataset.paymentId;
            const clientName = this.dataset.clientName;

            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to recover")}} ${clientName} !`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{__("message.Yes, recover")}}',
                cancelButtonText: '{{__("message.Cancel")}}',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await recoverClient(paymentId, clientName);
                }
            });
        });
    });
    
    async function recoverClient(paymentId, clientName) {
        try {
    
            Swal.fire({
                text: '{{__("message.Please wait")}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch("{{ route('payment.recover', ':id') }}".replace(':id', paymentId), {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error('recover error');
            }
            
            // Success notification
            Swal.fire({
                title: '{{__("message.Recovered")}}!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: '{{__("message.OK")}}'
            }).then((result) => {

                 location.reload();
            });
            
        } catch (error) {
            console.error('Error:', error);
            
            Swal.fire({
                title: '{{__("message.Error")}}!',
                text: error.message || '{{__("message.Failed to recover Payment")}}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: '{{__("message.OK")}}'
            });
        }
    }
});
  </script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
    // Handle force delete button clicks
    const forceDeleteButtons = document.querySelectorAll('.force-delete-btn');
    
    forceDeleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const paymentId = this.dataset.paymentId;
            const clientName = this.dataset.clientName;
            
            // confirmation
            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to permanently delete payment for")}} ${clientName}. {{__("message.This action cannot be undone")}}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{__("message.Yes, delete permanently")}}',
                cancelButtonText: '{{__("message.Cancel")}}',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await forceDeleteClient(paymentId, clientName);
                }
            });
        });
    });
    

    async function forceDeleteClient(paymentId, clientName) {
        try {
            // loader
            Swal.fire({
                title: '{{__("message.Deleting")}}...',
                text: '{{__("message.Please wait")}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch("{{ route('payment.force.delete', ':id') }}".replace(':id', paymentId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || '{{__("message.Delete failed")}}');
            }
            
            Swal.fire({
                title: '{{__("message.Deleted")}}!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: '{{__("message.OK")}}'
            }).then((result) => {

                 location.reload();
            });
            
        } catch (error) {
            console.error('Error:', error);
            
            Swal.fire({
                title: '{{__("message.Error")}}!',
                text: error.message || '{{__("message.Failed to delete payment")}}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: '{{__("message.OK")}}'
            });
        }
    }
});
  </script>
@endpush