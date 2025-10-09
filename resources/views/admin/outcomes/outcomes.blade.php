@extends('layouts.app')

@section('title', 'Outcomes')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-center">{{__('message.Outcomes')}}</h1>
        <div class="card">
          <div class="flex-grow-1 p-3">
            <div class="d-flex justify-content-between mb-3">
                <div class="d-flex justify-content-start">
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    {{__('message.Add Category')}}
                    </button>
                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
                    {{__('message.Add Subcategory')}}
                    </button>
                </div>
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addOutcomeModal">
                  {{__('message.Add Outcome')}}
                </button>
            </div>
    
            <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="text" id="search-input" class="form-control border" placeholder="{{__('message.Search for Items')}}...">
                </div>
            </div>
    
            <!-- Outcomes Table -->
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
                                    <button  class='edit-outcome-btn btn btn-primary' 
                                             data-bs-toggle='modal'
                                             data-bs-target='#editOutcomeModal' 
                                             data-outcome='@json($outcome)'
                                      >
                                  <span class="d-sm-inline d-none">{{__('message.Edit')}}</span>
                                  <span class="d-inline d-sm-none">E</span>
                              </button>
                                    <button id="delete-btn" class='btn btn-danger' data-bs-toggle='modal'
                                        data-bs-target='#deleteOutcomeModal' data-outcome-id="{{$outcome->outcome_id}}">
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
        </div>
    </div>
{{-- add outcome model --}}
@include('admin.outcomes.partials.add-outcome',['categories'=>$categories,'subcategories'=>$subcategories])
{{-- edit outcome model --}}
@include('admin.outcomes.partials.edit-outcome',['categories'=>$categories,'subcategories'=>$subcategories])
{{-- add category  model --}}
@include('admin.outcomes.partials.add-category')
{{-- add sybcategory model --}}
@include('admin.outcomes.partials.add-subcategory',['categories'=>$categories])
{{-- delete outcome model --}}
@include('admin.outcomes.partials.delete-outcome') 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete modal show
    const deleteModal = document.getElementById('deleteOutcomeModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('delete_outcome_id').value = button.dataset.outcomeId;
        });
    }

    // Handle delete form submission
    const deleteForm = document.getElementById('deleteOutcomeForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const outcomeId = document.getElementById('delete_outcome_id').value;
            
            try {
                const response = await fetch(`/admin/delete-outcome/${outcomeId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
        
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Delete failed');
                }

                const data = await response.json();
                alert(data.message || 'Outcome deleted successfully');
                location.reload();
                
            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Failed to delete outcome');
            }
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editOutcomeModal = document.getElementById('editOutcomeModal');
    const editOutcomeForm = document.getElementById('editOutcomeForm');
    
    function populateEditModal(outcome) {

        document.getElementById('edit_outcome_id').value = outcome.outcome_id;
        document.getElementById('edit_category_id').value = outcome.subcategory.category_id;
        document.getElementById('edit_subcategory_id').value = outcome.subcategory_id;
        document.getElementById('edit_amount').value = outcome.amount;
        const lang = document.getElementById('edit_description').value = outcome.trans_description || '';
        
        editOutcomeForm.action = `/admin/edit-outcome/${outcome.outcome_id}`;
        console.log(lang);
    }
    
    document.querySelectorAll('.edit-outcome-btn').forEach(button => {
        button.addEventListener('click', function() {
            try {
                const outcomeJson = this.getAttribute('data-outcome');
                const outcome = JSON.parse(outcomeJson);
                
                populateEditModal(outcome);
            } catch (error) {
                console.error('Error parsing outcome data:', error);
                showAlert('error', 'Error loading outcome data');
            }
        });
    });
    
    // fetch 
    if (editOutcomeForm) {
        editOutcomeForm.addEventListener('submit', async function(e) {
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
                    const modal = bootstrap.Modal.getInstance(editOutcomeModal);
                    modal.hide();

                        window.location.reload();
                    
                } else {
                    alert('error', data.message);
                }
                
            } catch (error) {
                console.error('Error:', error);
                alert('error', 'An error occurred while updating the outcome.');
            } 
        });
    }
    
});
</script>
@endpush
@endsection