@extends('layouts.app')

@section('title', 'Recover Outcomes')

@section('content')
  <h1 class="mb-4 text-center">{{ __('message.Recover Outcomes') }}</h1>
<div class="card">
  <div class="table-responsive">
    <table id="sortableTable" class="table">
      <thead>
       <tr>
           <th onclick="sortTable(0, this)">{{__('message.Nb')}} <span class="arrow"></span></th>
           <th onclick="sortTable(1, this)">{{__('message.Category')}} <span class="arrow"></span></th>
           <th onclick="sortTable(2, this)">{{__('message.Subcategory')}} <span class="arrow"></span></th>
           <th onclick="sortTable(3, this)">{{__('message.Amount')}} <span class="arrow"></span></th>
           <th onclick="sortTable(4, this)">{{__('message.Description')}} <span class="arrow"></span></th>
           <th onclick="sortTable(5, this)">{{__('message.Date')}}  <span class="arrow"></span></th>
           <th>{{__('message.Actions')}}</th>
       </tr>
   </thead>
    <tbody>
       @foreach($outcomes as $index => $outcome)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$outcome->subcategory->category->name}}</td>
                <td>{{$outcome->subcategory->name}}</td>
                <td> ${{$outcome->amount}}</td>
                <td>{{$outcome->trans_description}}</td>
                <td>{{ date('M d, Y', strtotime($outcome->created_at)) }}</td>
                <td>
                  <div class="flex gap-1">
                <button class='btn btn-primary recovery-btn' 
                        data-outcome-id="{{ $outcome->outcome_id }}" >
                  <span class="d-sm-inline d-none">{{__('message.Recover')}}</span>
                  <span class="d-inline d-sm-none">R</span>
              </button>

                <button class='btn btn-danger force-delete-btn' 
                        data-outcome-id="{{ $outcome->outcome_id }}" >
                  <span class="d-sm-inline d-none">{{__('message.Delete')}}</span>
                  <span class="d-inline d-sm-none">D</span>
              </button>
              </div>
                </td>
            </tr>
    @endforeach
    </tbody>
    </table>
    {{$outcomes->links()}}
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
            
            const outcomeId = this.dataset.outcomeId;

            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to recover")}} ${outcomeId} !`,
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
                    await recoverOutcome(outcomeId);
                }
            });
        });
    });
    
    async function recoverOutcome(outcomeId) {
        try {
    
            Swal.fire({
                text: '{{__("message.Please wait")}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const response = await fetch("{{ route('outcome.recover', ':id') }}".replace(':id', outcomeId), {
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
                text: error.message || '{{__("message.Failed to recover Outcome")}}',
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
            
            const outcomeId = this.dataset.outcomeId;
            
            // confirmation
            Swal.fire({
                title: '{{__("message.Are you sure")}}?',
                text: `{{__("message.You are about to permanently delete outcome ")}}. {{__("message.This action cannot be undone")}}!`,
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
                    await forceDeleteOutcome(outcomeId);
                }
            });
        });
    });
    

    async function forceDeleteOutcome(outcomeId) {
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
            
            const response = await fetch("{{ route('outcome.force.delete', ':id') }}".replace(':id', outcomeId), {
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
                text: error.message || '{{__("message.Failed to delete outcome")}}',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: '{{__("message.OK")}}'
            });
        }
    }
});
  </script>
@endpush