<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.payment',$income->income_id)}}" id="addPaymentForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="payment_amount" class="form-label">Payment Amount</label>
                      <input type="number" class="form-control border" id="payment_amount" name="payment_amount"
                          max="{{$income->remaining}}" required>
                  </div>
                  <div class="mb-3">
                      <label for="payment_status" class="form-label">Status:</label>
                      <select class="form-select" name="status" id="payment_status">
                        <option value="unpaid" selected>Unpaid</option>
                        <option value="paid">Paid</option>
                    </select>
                    </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Payment Description</label>
                      <input type="text" class="form-control border" id="description" name="description" required>
                  </div>
                  <div class="mb-3">
                      <label for="next_payment" class="form-label">Next Payment Date</label>
                      <input type="date" class="form-control border" id="next_payment" name="next_payment"></input>
                  </div>
                  <input type="hidden" name="income_id" value="{{$income->income_id}}">
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Add Payment</button>
              </div>
          </form>
      </div>
  </div>
</div>