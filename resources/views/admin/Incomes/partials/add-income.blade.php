<!-- Add Income Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.inc')}}" id="addIncomeForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addIncomeModalLabel">Add Income</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="client_id" class="form-label">Client</label>
                      <select class="form-select" id="client_id" name="client_id" required>
                          <option selected disabled value="">Select Client</option>
                          @foreach($clients as $client)
                          <option value="{{$client->client_id}}">{{$client->client_fname.' '.$client->client_lname}} - {{$client->client_phone}}</option>
                       @endforeach
                        </select>
                  </div>
                  <div class="mb-3">
                      <label for="category_id" class="form-label">Category</label>
                      <select class="form-select" id="category_id_income" name="category_id" required>
                          <option selected disabled value="">Select Category</option>
                          @foreach($categories as $cat)
                          <option value="{{$cat->category_id}}">{{$cat->category_name}}</option>
                       @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="subcategory_id" class="form-label">Subcategory</label>
                      <select class="form-select" id="subcategory_id_income" name="subcategory_id" >
                          <option selected disabled value="">Select Subcategory</option>
                          @foreach($subcategories as $sub)
                          <option value="{{$sub->subcategory_id}}">{{$sub->sub_name}}</option>
                       @endforeach
                      </select>
                  </div>
                  <div class="d-flex mb-3">
                      <div class="flex-fill me-2">
                          <label for="amount" class="form-label">Amount</label>
                          <input type="number" class="form-control border" id="amount" name="amount" required>
                      </div>
                      <div class="flex-fill me-2">
                          <label for="amount" class="form-label">Paid (optional)</label>
                          <input type="number" class="form-control border" id="paid" name="paid" max="" step="1">
                      </div>
                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control border" id="description" name="description"></textarea>
                  </div>
                  <div class="mb-3">
                      <label for="next_payment" class="form-label">Next Payment Date</label>
                      <input type="date" class="form-control border" id="next_payment" name="next_payment"></input>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add Income</button>
              </div>
          </form>
      </div>
  </div>
</div>