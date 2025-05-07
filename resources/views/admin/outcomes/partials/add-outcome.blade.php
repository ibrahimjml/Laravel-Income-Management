<!-- Add Outcome Modal -->
<div class="modal fade" id="addOutcomeModal" tabindex="-1" aria-labelledby="addOutcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.out')}}" id="addOutcomeForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addOutcomeModalLabel">Add Outcome</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="category_id" class="form-label">Category</label>
                      <select class="form-select" id="category_id_outcome" name="category_id" required>
                          <option selected disabled value="">Select Category</option>
                          @foreach($categories as $cat)
                          <option value="{{$cat->category_id}}">{{$cat->category_name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="subcategory_id" class="form-label">Subcategory</label>
                      <select class="form-select" id="subcategory_id_outcome" name="subcategory_id" required>
                          <option selected disabled value="">Select Subcategory</option>
                          @foreach($subcategories as $sub)
                          <option value="{{$sub->subcategory_id}}">{{$sub->sub_name}}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="amount" class="form-label">Amount</label>
                      <input type="number" class="form-control border" id="amount" name="amount" required>

                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control border" id="description" name="description"></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add outcome</button>
              </div>
          </form>
      </div>
  </div>
</div>