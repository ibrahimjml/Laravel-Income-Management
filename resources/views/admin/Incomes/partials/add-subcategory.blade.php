<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('add.sub')}}" id="addSubcategoryForm" method="POST">
              @csrf
              @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCategoryModalLabel">Add Subcategory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                      @foreach($categories as $category)
                      <option value="{{$category->category_id}}">{{$category->category_name}}</option>
                      @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sub_name" class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control border" id="sub_name" name="sub_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>