@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
  <div class="container-fluid">
    <h1 class="mb-4 text-center">{{__('message.Activity Logs')}}</h1>
    @if($logs->count() > 0)
      <div class="d-flex justify-content-end m-4">
       <button id="deleteAll-btn" class='btn btn-danger'>
               <span class="d-sm-inline d-none">Delete All</span>
               <span class="d-inline d-sm-none">D</span>
        </button>
      </div>
    @endif
    <div class="card">
      <div class="row mb-3 p-2">
        <div class="col-sm-12">
          <input type="text" id="search-input" class="form-control border"
            placeholder="{{__('message.Search for Items')}}...">
        </div>
      </div>
      <!-- table logs-->
      <div class="table-responsive">
        <table id="sortableTable" class="table ">
          <thead>
            <tr>
              <th onclick="sortTable(0, this)">Time<span class="arrow"></span></th>
              <th onclick="sortTable(1, this)">Event<span class="arrow"></span></th>
              <th onclick="sortTable(2, this)">Subject<span class="arrow"></span></th>
              <th onclick="sortTable(3, this)">Causer<span class="arrow"></span></th>
              <th onclick="sortTable(4, this)">Description<span class="arrow"></span></th>
              <th>{{__('message.Actions')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $log)
              <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>
                  <span @class([
                    'badge bg-success' => $log->event == 'created',
                    'badge bg-warning' => $log->event == 'updated',
                    'badge bg-danger' => $log->event == 'deleted',
                  ])>
                    {{ $log->event }}
                  </span>
                </td>
                <td>
                  @php
                    $modelName = class_basename($log->subject_type);
                    $subjectName = optional($log->subject)->activity_subject_name ?? 'N/A';
                  @endphp
                  <strong>{{ $modelName }}:</strong> {{ $subjectName }}
                </td>
                <td>{{ $log->causer['name'] }}</td>
                <td>{{ $log->description }}</td>
                <td>
                  <button class="btn btn-primary me-2" data-bs-toggle="modal"
                    data-bs-target="#showPropertiesModal{{ $log->id }}">
                    <small>view properties</small>
                  </button>
                </td>
              </tr>
              <!-- Modal log -->
             @include('admin.logs.partials.show-properties')
             <!-- end Modal log -->
            @endforeach
          </tbody>
        </table>
        {{ $logs->links() }}
      </div>
    </div>
  </div>

@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {

    const DeleteAll = document.getElementById('deleteAll-btn');
        DeleteAll.addEventListener('click', function(e) {
        
            // SweetAlert2 confirmation
            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to permanently delete all logs")}}`,
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
                    await forceDeleteAll();
                }
            });
        });

    
    // Force delete function
    async function forceDeleteAll() {
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
            
            const response = await fetch(`/admin/logs/delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error('{{__("message.Delete failed")}}');
            }
            
            // Success notification
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
            
        }
    }
});
  </script>
@endpush