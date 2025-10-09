<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form id="editPaymentForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_payment_id" name="payment_id">
              <div class="modal-header">
                  <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="edit_amount" class="form-label">Payment Amount</label>
                      <input type="number" class="form-control border" id="edit_amount" name="payment_amount"
                          max="{{$income->remaining}}" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_description" class="form-label">Payment Description</label>
                      <input type="text" class="form-control border" id="edit_description" name="description" required>
                  </div>
                  <div class="mb-3">
                      <label for="edit_next_payment" class="form-label">Next Payment Date</label>
                      <input type="date" class="form-control border" id="edit_next_payment" name="next_payment">
                  </div>
                  <input type="hidden" name="income_id" value="{{$income->income_id}}">
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="submit" class="btn btn-success">Edit Payment</button>
                  <select class="form-select" name="lang" id="lang">
                  <option value="en" selected>EN</option>
                  <option value="ar">AR</option>
                  </select>
              </div>
          </form>
      </div>
  </div>
</div>