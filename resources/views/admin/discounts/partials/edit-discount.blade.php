<!-- Add Category Modal -->
<div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editDiscountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="editDiscountForm"  method="POST">
            @csrf
            @method('PUT')
              <div class="modal-header">
                  <h5 class="modal-title" id="editDiscountModalLabel">edit Discount</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="edit_discount_name" class="form-label">name:</label>
                      <input type="text" class="form-control border" id="edit_discount_name" name="name" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_discount_rate" class="form-label">rate:</label>
                      <input type="text" class="form-control border" id="edit_discount_rate" name="rate" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_discount_type" class="form-label">type:</label>
                        <select class="form-select" id="edit_discount_type" name="type" required>
                        <option value="manual">manual</option>
                        <option value="loyalty">loyalty</option>
                        <option value="early">early</option>
                        </select>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">edit Discount</button>
                </div>
          </form>
      </div>
  </div>
</div>