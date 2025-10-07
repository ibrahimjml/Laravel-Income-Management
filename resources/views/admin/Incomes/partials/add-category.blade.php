<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.cat')}}" id="addCategoryForm" method="POST">
            @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="addCategoryModalLabel">{{__('message.Add Category')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="category_name_en" class="form-label">{{__('message.Category name_en')}}</label>
                      <input type="text" class="form-control border" id="category_name_ar" name="name_en" required>
                  </div>
                  <div class="mb-3">
                      <label for="category_name_ar" class="form-label">{{__('message.Category name_ar')}}</label>
                      <input type="text" class="form-control border" id="category_name_ar" name="name_ar" required>
                  </div>
                  <input value="Income" type="hidden" id="category_type" name="category_type">
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">{{__('message.Add Category')}}</button>
                </div>
          </form>
      </div>
  </div>
</div>