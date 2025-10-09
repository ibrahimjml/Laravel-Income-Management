<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('add.cat.sub')}}" id="addSubcategoryForm" method="POST">
              @csrf
              @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCategoryModalLabel">{{__('message.Add Subcategory')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">{{__('message.Category')}}</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">{{__('message.Select Category')}}</option>
                            @foreach($categories as $cat)
                            <option value="{{$cat->category_id}}">{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sub_name_en" class="form-label">{{__('message.Subcategory name_en')}}</label>
                        <input type="text" class="form-control border" id="sub_name_en" name="name_en" required>
                    </div>
                    <div class="mb-3">
                        <label for="sub_name_ar" class="form-label">{{__('message.Subcategory name_ar')}}</label>
                        <input type="text" class="form-control border" id="sub_name_ar" name="name_ar" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('message.Add Subcategory')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>