
<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="editClientForm">
             @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="editClientModalLabel">{{__('message.Edit Client')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="edit_client_id" name="client_id">

                  <div class="mb-3">
                      <label for="edit_client_fname" class="form-label">{{__('message.First Name')}}</label>
                      <input type="text" class="form-control border" id="edit_client_fname" name="client_fname"
                   required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_client_lname" class="form-label">{{__('message.Last Name')}}</label>
                      <input type="text" class="form-control border" id="edit_client_lname" name="client_lname"
                   required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_client_email" class="form-label">{{__('message.Email')}}</label>
                      <input type="text" class="form-control border" id="edit_client_email" name="email">
                  </div>
                  <div class="mb-3">
                      <label for="edit_client_phone" class="form-label">{{__('message.Phone Number')}}</label>
                      <input type="tel" class="form-control border" id="edit_client_phone" name="client_phone"
                       required>
                  </div>
                  <div class="mb-3">
                    <label for="edit_type_id" class="form-label">{{__('message.Edit Client')}}</label>
                    <div class="">
                      @foreach($clienttype->where('is_deleted', 0) as $type)
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" 
                                 name="type_id[]" 
                                 value="{{ $type->type_id }}" 
                                 id="type_{{ $type->type_id }}">
                          <label class="form-check-label" for="type_{{ $type->type_id }}">
                              {{ $type->types_name }}
                          </label>
                      </div>
                  @endforeach
                  
                    </div>
                </div>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">{{__('message.Edit Client')}}</button>
                   <select class="form-select" name="lang" id="lang">
                 <option value="en">EN</option>
                 <option value="ar">AR</option>
                 </select>
              </div>
          </form>
      </div>
  </div>
  </div>
