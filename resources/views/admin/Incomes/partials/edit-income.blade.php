<div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('update.inc',$income->income_id)}}" id="editIncomeForm" method="POST">
            @csrf
            @method('PUT')
              <div class="modal-header">
                  <h5 class="modal-title" id="editIncomeModalLabel">Edit Income</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="client_id" class="form-label">Client</label>
                      <select class="form-select" id="client_id" name="client_id" required>
                        @foreach($clients as $client)
                              <option value="{{$client->client_id}}"
                                {{$client->client_id === $income->client->client_id ? 'selected' : ''}}>
                                {{$client->client_fname}} {{$client->client_lname}}
                              </option>
                        @endforeach
                      </select>
                  </div>
                  <input type="hidden" name="income_id" value="{{$income->income_id}}">
                  <div class="mb-3">
                      <label for="category_id_income" class="form-label">Category</label>
                      <select class="form-select" id="category_id_income" name="category_id" required>
                        @foreach($categories as $cat)
                        <option value="{{$cat->category_id}}"
                          {{$cat->category_id === $income->subcategory->category->category_id ? 'selected' : ''}}>
                          {{$cat->category_name}} 
                        </option>
                  @endforeach
        
                      </select>
                  </div>

                  <div class="mb-3">
                      <label for="subcategory_id" class="form-label">Subcategory</label>
                      <select class="form-select" id="subcategory_id_income" name="subcategory_id" required>
                        @foreach($subcategories as $sub)
                        <option value="{{$sub->subcategory_id}}"
                          {{$sub->subcategory_id === $income->subcategory->subcategory_id ? 'selected' : ''}}>
                          {{$sub->sub_name}} 
                        </option>
                       @endforeach
                      </select>
                  </div>

                  <div class="mb-3">
                      <label for="amount" class="form-label">Amount</label>
                      <input type="number" class="form-control border" id="amount" name="amount"
                          value="{{$income->amount}}" required>
                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">Description</label>
                      <textarea class="form-control border" id="description" name="description"
                          required>{{$income->description}}</textarea>
                  </div>
                  <div class="mb-3">
                      <label for="next_payment" class="form-label">Next Payment Date</label>
                      <input type="date" class="form-control border" id="next_payment" name="next_payment"
                          value="{{$income->next_payment}}">
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Update Income</button>
              </div>
          </form>
      </div>
  </div>
</div>