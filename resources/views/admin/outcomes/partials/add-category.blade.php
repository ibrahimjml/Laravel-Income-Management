<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.out.cat')}}" id="addCategoryForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="category_name" class="form-label">Category Name</label>
                      <input type="text" class="form-control border" id="category_name" name="category_name" required>
                  </div>
                  <input value="Outcome" type="hidden" id="category_type" name="category_type">
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add Category</button>
              </div>
          </form>
      </div>
  </div>
</div>