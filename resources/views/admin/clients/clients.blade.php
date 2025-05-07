@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

        <h1 class="mb-4 text-center">Clients</h1>
        <div class="card">
          <div class="flex-grow-1 p-3">

            <div class="d-flex justify-content-between mb-3 ">
                <div class="flex justify-content-start gap-2">
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                      Add Client Type</button>
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#editTypeModal">
                      Edit Client Type</button>
                </div>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addClientModal">
                  Add Client</button>
            </div>
    
            <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="text" id="search-input" class="form-control border" placeholder="Search for Items...">
                </div>
            </div>
    
            <div class="table-responsive">
                <table id="sortableTable" class="table">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0, this)">Nb <span class="arrow"></span></th>
                            <th onclick="sortTable(1, this)">Name <span class="arrow"></span></th>
                            <th onclick="sortTable(2, this)">Phone <span class="arrow"></span></th>
                            <th onclick="sortTable(3, this)">Types <span class="arrow"></span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                  @foreach($clients as $index => $client)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$client->client_fname}} {{$client->client_lname}}</td>
                                <td>{{$client->client_phone}}</td>
                                <td>
                                  {{ $client->types->firstWhere('pivot.is_deleted', 0)?->type_name ?? 'No Type' }}
                              </td>
                                <td>
                                    <div class="flex gap-1">
                                      <button id="btn-edit" class='btn btn-primary' data-bs-toggle='modal'
                                      data-bs-target='#editClientModal' 
                                      data-client-id="{{ $client->client_id }}"
                                      data-client-fname="{{ $client->client_fname }}"
                                      data-client-lname="{{ $client->client_lname }}"
                                      data-client-phone="{{ $client->client_phone }}"
                                      data-client-types='@json($client->types->where("pivot.is_deleted", 0)->pluck("type_id"))'>
                                  <span class="d-sm-inline d-none">Edit</span>
                                  <span class="d-inline d-sm-none">E</span>
                              </button>

                                        <button id="delete-btn" class='btn btn-danger' data-bs-toggle='modal'
                                            data-bs-target='#deleteClientModal' data-client-id="{{$client->client_id}}"
                                            data-client-name="{{ $client->client_fname }} {{ $client->client_lname }}">
                                            <span class="d-sm-inline d-none">Delete</span>
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

    </div>
{{-- add client model --}}
@include('admin.clients.partials.add-client-model',['clienttype'=>$clienttype])
{{-- add client type model --}}
@include('admin.clients.partials.add-client-type-model')
{{-- add client type model --}}
@include('admin.clients.partials.edit-client-type-model',['clienttype'=>$clienttype])
{{-- edit client model --}}
@include('admin.clients.partials.edit-client-model',['clienttype'=>$clienttype])
{{-- delete client model --}}
@include('admin.clients.partials.delete-client-model')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit modal show
    document.getElementById('editClientModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const modal = this;
        
        // Set basic client info
        modal.querySelector('#edit_client_id').value = button.dataset.clientId;
        modal.querySelector('#edit_client_fname').value = button.dataset.clientFname;
        modal.querySelector('#edit_client_lname').value = button.dataset.clientLname;
        modal.querySelector('#edit_client_phone').value = button.dataset.clientPhone;
        
        // Parse the types JSON
        const types = JSON.parse(button.dataset.clientTypes || '[]');
        
        // Reset all checkboxes
        modal.querySelectorAll('input[name="type_id[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Check the appropriate boxes
        types.forEach(typeId => {
            const checkbox = modal.querySelector(`input[name="type_id[]"][value="${typeId}"]`);
            if (checkbox) checkbox.checked = true;
        });
    });

    // Handle edit client
    document.getElementById('editClientForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const clientId = form.querySelector('#edit_client_id').value;
        const formData = new FormData(form);
        
        try {
            const response = await fetch(`/admin/edit-client/${clientId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Update failed');
            }
            
            alert('Client updated successfully');
            location.reload();
            
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Failed to update client');
        }
    });
})

  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Handle delete modal show
    const deleteModal = document.getElementById('deleteClientModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
        
            document.getElementById('delete_client_id').value = button.dataset.clientId;
            document.getElementById('delete_client_name').textContent = button.dataset.clientName;
        });
    }

    // Handle delete form submission
    const deleteForm = document.getElementById('deleteClientForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const clientId = document.getElementById('delete_client_id').value;
            
            try {
                const response = await fetch(`/admin/delete-client/${clientId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT',
                        'Accept': 'application/json'
                    },
                    body: new FormData(this)
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Delete failed');
                }
                
                alert(data.message || 'Client deleted successfully');
                location.reload();
                
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Failed to delete client');
            }
        });
    }
});
  </script>
  @endpush
@endsection
