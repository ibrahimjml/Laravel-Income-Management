<!-- Add Client Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.client.type')}}" id="addTypeForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addTypeModalLabel">{{__('message.Add Client Type')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="type_name" class="form-label">{{__('message.Client Type')}}</label>
                      <input type="text" class="form-control border" id="type_name" name="type_name" required>
                  </div>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">{{__('message.Add Client Type')}}</button>
                  <select  name="lang" id="lang">
                 <option value="en" selected>EN</option>
                 <option value="ar">AR</option>
                 </select>
              </div>
          </form>
      </div>
  </div>
</div>