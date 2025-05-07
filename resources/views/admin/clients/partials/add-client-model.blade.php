<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.client')}}" id="addClientForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="client_fname" class="form-label">First Name</label>
                      <input type="text" class="form-control border" id="client_fname" name="client_fname" required>
                  </div>
                  <div class="mb-3">
                      <label for="client_lname" class="form-label">Last Name</label>
                      <input type="text" class="form-control border" id="client_lname" name="client_lname" required>
                  </div>
                  <div class="mb-3">
                      <label for="client_phone" class="form-label">Phone Number</label>
                      <input type="tel" class="form-control border" id="client_phone" name="client_phone" required>
                  </div>
                  <div class="mb-3">
                      <label for="type_id" class="form-label">Client Type</label>
                      <div>
                  @foreach($clienttype as $type)
                              <div class="form-check">
                                  <input class="form-check-input" type="checkbox" name="type_id[]"
                                      value="{{$type->type_id}}" id="clientType">
                                  <label class="form-check-label" for="clientType">
                                    {{$type->type_name}}
                                  </label>
                              </div>
              @endforeach
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add Client</button>
              </div>
          </form>
      </div>
  </div>
</div>