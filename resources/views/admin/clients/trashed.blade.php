@extends('layouts.app')

@section('title', 'Trashed Clients')

@section('content')
  <h1 class="mb-4 text-center">Trashed clients</h1>
<div class="card">
  <div class="table-responsive">
    <table id="sortableTable" class="table">
      <thead>
        <tr>
          <th onclick="sortTable(0, this)">{{__('message.Nb')}} <span class="arrow"></span></th>
          <th onclick="sortTable(1, this)">{{__('message.Name')}} <span class="arrow"></span></th>
          <th onclick="sortTable(1, this)">{{__('message.Email')}} <span class="arrow"></span></th>
          <th onclick="sortTable(2, this)">{{__('message.Phone')}} <span class="arrow"></span></th>
          <th onclick="sortTable(3, this)">{{__('message.Types')}} <span class="arrow"></span></th>
          <th>{{__('message.Actions')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($clients as $index => $client)
          <tr>
            <td>{{$index + 1}}</td>
            <td>{{$client->full_name}}</td>
            <td>{{$client->email}}</td>
            <td>{{$client->client_phone}}</td>
            <td>
              {{ $client->trashedTypes->first()?->type_name ?? 'No Type' }}
            </td>
            <td>
              <div class="flex gap-1">
                <button class='btn btn-primary recovery-btn' 
                        data-client-id="{{ $client->client_id }}" 
                        data-client-name="{{ $client->full_name }}">
                  <span class="d-sm-inline d-none">{{__('message.Recover')}}</span>
                  <span class="d-inline d-sm-none">R</span>
              </button>

                <button class='btn btn-danger force-delete-btn' 
                        data-client-id="{{ $client->client_id }}" 
                        data-client-name="{{ $client->full_name }}">
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

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle force delete button clicks
    const forceDeleteButtons = document.querySelectorAll('.force-delete-btn');
    
    forceDeleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const clientId = this.dataset.clientId;
            const clientName = this.dataset.clientName;
            
            // SweetAlert2 confirmation
            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to permanently delete")}} ${clientName}. {{__("message.This action cannot be undone")}}!`,
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
                    await forceDeleteClient(clientId, clientName);
                }
            });
        });
    });
    
    // Force delete function
    async function forceDeleteClient(clientId, clientName) {
        try {
            // Show loading indicator
            Swal.fire({
                title: '{{__("message.Deleting")}}...',
                text: '{{__("message.Please wait")}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch(`/admin/clients/delete/${clientId}`, {
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
            
            // Success notification
            Swal.fire({
                title: '{{__("message.Deleted")}}!',
                text: `{{__("message.Client has been permanently deleted")}}`,
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
                text: error.message || '{{__("message.Failed to delete client")}}',
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
    // Handle recover button
    const recoveryButtons = document.querySelectorAll('.recovery-btn');
    
    recoveryButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const clientId = this.dataset.clientId;
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
                    await recoverClient(clientId, clientName);
                }
            });
        });
    });
    
    async function recoverClient(clientId, clientName) {
        try {
    
            Swal.fire({
                text: '{{__("message.Please wait")}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch(`/admin/clients/recover/${clientId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'recover error');
            }
            
            // Success notification
            Swal.fire({
                title: '{{__("message.Recovered")}}!',
                text: `{{__("message.Client has been succeesful recovered")}}`,
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
                text: error.message || '{{__("message.Failed to recover client")}}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: '{{__("message.OK")}}'
            });
        }
    }
});
  </script>
@endpush
@endsection