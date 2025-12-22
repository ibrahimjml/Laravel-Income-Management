<div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('update.inc',$income->income_id)}}" id="editIncomeForm" method="POST">
            @csrf
            @method('PUT')
              <div class="modal-header">
                  <h5 class="modal-title" id="editIncomeModalLabel">{{ __('message.Edit Income') }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="client_id" class="form-label">{{ __('message.Client') }}</label>
                      <select class="form-select" id="client_id" name="client_id" required>
                        @foreach($clients as $client)
                              <option value="{{$client->client_id}}"
                                {{$client->client_id === $income->client->client_id ? 'selected' : ''}}>
                                {{$client->full_name}}
                              </option>
                        @endforeach
                      </select>
                  </div>
                  <input type="hidden" name="income_id" value="{{$income->income_id}}">
                  <div class="mb-3">
                      <label for="category_id_income" class="form-label">{{ __('message.Category') }}</label>
                      <input type="text" class="form-control border" id="category_id_income" value="{{ $income->subcategory->category->name }}" disabled readonly>
                  </div>

                  <div class="mb-3">
                      <label for="subcategory_id" class="form-label">{{ __('message.Subcategory') }}</label>
                      <select class="form-select" id="subcategory_id_income" name="subcategory_id" required>
                        @foreach($subcategories as $sub)
                        <option value="{{$sub->subcategory_id}}"
                          {{$sub->subcategory_id === $income->subcategory->subcategory_id ? 'selected' : ''}}>
                          {{$sub->name .' - '.$sub->category->name}} 
                        </option>
                       @endforeach
                      </select>
                  </div>

                  <div class="mb-3">
                      <label for="amount" class="form-label">{{ __('message.Amount') }}</label>
                      <input type="number" class="form-control border" id="amount" name="amount"
                          value="{{$income->amount}}" required>
                  </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">{{ __('message.Description') }}</label>
                      <textarea class="form-control border" id="description" name="description"
                        >{{$income->trans_description}}</textarea>
                  </div>
                  <div class="mb-3">
                      <label for="next_payment" class="form-label">{{ __('message.Next Payment Date') }}</label>
                      <input type="date" class="form-control border" id="next_payment" name="next_payment"
                          value="{{$income->next_payment}}">
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">{{ __('message.Edit Income') }}</button>
                  <select class="form-select" name="lang" id="lang">
                  <option value="en" selected>EN</option>
                  <option value="ar">AR</option>
                  </select>
              </div>
          </form>
      </div>
  </div>
</div>