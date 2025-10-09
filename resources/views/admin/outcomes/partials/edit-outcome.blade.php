<!-- Edit Outcome Modal -->
<div class="modal fade" id="editOutcomeModal" tabindex="-1" aria-labelledby="editOutcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="editOutcomeForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_outcome_id" name="outcome_id">
              <div class="modal-header">
                  <h5 class="modal-title" id="editOutcomeModalLabel">{{__('message.Edit Outcome')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="edit_category_id" class="form-label">{{__('message.Category')}}</label>
                      <select class="form-select" id="edit_category_id" name="category_id" required>
                          <option selected disabled value="">{{__('message.Select Category')}}</option>
                          @foreach($categories as $cat)
                          <option value="{{$cat->category_id}}">{{$cat->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="edit_subcategory_id" class="form-label">{{__('message.Subcategory')}}</label>
                      <select class="form-select" id="edit_subcategory_id" name="subcategory_id" required>
                          <option selected disabled value="">{{__('message.Select Subcategory')}}</option>
                          @foreach($subcategories as $sub)
                          <option value="{{$sub->subcategory_id}}">{{$sub->name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="edit_amount" class="form-label">{{__('message.Amount')}}</label>
                      <input type="number" class="form-control border" id="edit_amount" name="amount" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_description" class="form-label">{{__('message.Description')}}</label>
                      <textarea class="form-control border" id="edit_description" name="description"></textarea>
                  </div>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">{{__('message.Update Outcome')}}</button>
                  <select class="form-select" name="lang" id="edit_lang">
                      <option value="en">EN</option>
                      <option value="ar">AR</option>
                  </select>
              </div>
          </form>
      </div>
  </div>
</div>