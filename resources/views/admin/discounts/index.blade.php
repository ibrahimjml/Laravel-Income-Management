@extends('layouts.app')

@section('title', 'discounts')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 text-center">Discounts</h1>
        <div class="card">
          <div class="flex-grow-1 p-3">
            <div class=" mb-3">
                    <button class="btn btn-success me-2 mb-4" data-bs-toggle="modal" data-bs-target="#addDiscountModal">
                    Add Discount
                    </button>

            <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="text" id="search-input" class="form-control border" placeholder="{{__('message.Search for Items')}}...">
                </div>
            </div>
    
            <!-- discounts Table -->
            <div class="table-responsive">
                <table id="sortableTable" class="table">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0, this)">{{__('message.Nb')}} <span class="arrow"></span></th>
                            <th onclick="sortTable(1, this)">Name <span class="arrow"></span></th>
                            <th onclick="sortTable(2, this)">Rate <span class="arrow"></span></th>
                            <th onclick="sortTable(3, this)">Type <span class="arrow"></span></th>
                            <th onclick="sortTable(4, this)">CreatedAt <span class="arrow"></span></th>
                            <th>{{__('message.Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                  @foreach($discounts as $index => $discount)
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$discount->name}}</td>
                                <td> % {{$discount->rate}}</td>
                                <td>{{$discount->type}}</td>
                                <td>{{ date('M d, Y', strtotime($discount->created_at)) }}</td>
                                <td>
                                  <div class="flex gap-1">
                                    <button  class='edit-discount-btn btn btn-primary' 
                                             data-bs-toggle='modal'
                                             data-bs-target='#editDiscountModal' 
                                             data-discount='@json($discount)'
                                      >
                                  <span class="d-sm-inline d-none">{{__('message.Edit')}}</span>
                                  <span class="d-inline d-sm-none">E</span>
                              </button>
                                    <button id="delete-btn" class='btn btn-danger'
                                             data-bs-toggle='modal'
                                             data-bs-target='#deleteDiscountModal' 
                                             data-discount-id="{{$discount->discount_id}}">
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
    </div>
{{-- add discount model  --}}
@include('admin.discounts.partials.add-discount')
{{-- edit discount model  --}}
@include('admin.discounts.partials.edit-discount')
@endsection    

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editDiscountModal = document.getElementById('editDiscountModal');
    const editDiscountForm = document.getElementById('editDiscountForm');
    
    function populateEditModal(discount) {

        document.getElementById('edit_discount_name').value = discount.name;
        document.getElementById('edit_discount_rate').value = discount.rate;
        document.getElementById('edit_discount_type').value = discount.type;

        editDiscountForm.action = `/admin/discounts/${discount.discount_id}`;

    }
    
    document.querySelectorAll('[data-bs-target="#editDiscountModal"]').forEach(button => {
        button.addEventListener('click', function() {
            try {
                const discountJson = this.getAttribute('data-discount');
                const discount =  JSON.parse(this.dataset.discount);
        
console.log(discount);
                populateEditModal(discount);
            } catch (error) {
                console.error('Error parsing discount data:', error);
                alert('error', 'Error loading discount data');
            }
        });
    });
    
    // fetch 
    if (editDiscountForm) {
        editDiscountForm.addEventListener('submit', async function(e) {
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
                    const modal = bootstrap.Modal.getInstance(editDiscountModal);
                    modal.hide();

                        window.location.reload();
                    
                } else {
                    alert('error', data.message);
                }
                
            } catch (error) {
                console.error('Error:', error);
                alert('error', 'An error occurred while updating discount.');
            } 
        });
    }
    
});
</script>
@endpush