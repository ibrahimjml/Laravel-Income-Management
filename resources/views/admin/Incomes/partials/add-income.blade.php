<!-- Add Income Modal -->
<div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="{{route('add.inc')}}" id="addIncomeForm" method="POST">
            @csrf
            @method('POST')
              <div class="modal-header">
                  <h5 class="modal-title" id="addIncomeModalLabel">{{__('message.Add Income')}}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="client_id" class="form-label">{{__('message.Client')}}</label>
                      <select class="form-select" id="client_id" name="client_id" required>
                          <option selected disabled value="">{{__('message.Select Client')}}</option>
                          @foreach($clients as $client)
                          <option value="{{$client->client_id}}">{{$client->full_name}} - {{$client->client_phone}}</option>
                       @endforeach
                        </select>
                  </div>
                  <div class="mb-3">
                      <label for="category_id" class="form-label">{{__('message.Category')}}</label>
                      <select class="form-select" id="category_id_income" name="category_id" required>
                          <option selected disabled value="">{{__('message.Select Category')}}</option>
                          @foreach($categories as $cat)
                          <option value="{{$cat->category_id}}">{{$cat->name}}</option>
                       @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                      <label for="subcategory_id" class="form-label">{{__('message.Subcategory')}}</label>
                      <select class="form-select" id="subcategory_id_income" name="subcategory_id" >
                          <option selected disabled value="">{{__('message.Select Subcategory')}}</option>
                          @foreach($subcategories as $sub)
                          <option value="{{$sub->subcategory_id}}">{{$sub->name}}</option>
                       @endforeach
                      </select>
                  </div>
                  <div class="mb-3">
                  <label class="form-label">{{ __('income.Payment Type') }}</label>
              
                  @foreach (\App\Enums\PaymentType::cases() as $type)
                      <div class="form-check">
                          <input class="form-check-input" type="radio" name="payment_type" id="payment_type_{{ $type->value }}" value="{{ $type->value }}"
                              {{ old('payment_type', $income->payment_type ?? 'onetime') === $type->value ? 'checked' : '' }}>
              
                          <label class="form-check-label" for="payment_type_{{ $type->value }}">
                              {{ __('income.payment_type.' . $type->value) }}
                          </label>
                      </div>
                  @endforeach
              </div>

                  <div class="d-flex mb-3">
                      <div class="flex-fill me-2">
                          <label for="amount" class="form-label">{{__('message.Amount')}}</label>
                          <input type="number" class="form-control border" id="amount" name="amount" required>
                      </div>
                      <div class="flex-fill me-2">
                          <label for="paid" class="form-label">{{__('message.Paid Amount')}}</label>
                          <input type="number" class="form-control border" id="paid" name="paid" max="" step="1">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="payment_status" class="form-label">{{ __('income.Payment Status') }}</label>
                      <select class="form-select payment-status" name="payment_status" id="payment_status">
                        @foreach (\App\Enums\PaymentStatus::cases() as $case)
                        <option value="{{$case->value}}">{{$case->label()}}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="me-2">
                      <label for="discount_id" class="form-label">{{ __('income.Discount') }} {{ __('message.Optional') }}</label>
                      <select class="form-select" name="discount_id" id="discount_id">
                        <option value="">--{{ __('income.Select Discount Rate') }}--</option>
                        @foreach($discounts as $id => $rate)
                            <option value="{{ $id }}">{{ $rate }}%</option>
                        @endforeach
                    </select>
                    </div>
                  <div class="mb-3">
                      <label for="description" class="form-label">{{__('message.Description')}}</label>
                      <textarea class="form-control border" id="description" name="description"></textarea>
                  </div>
                  <div class="mb-3" id="paid-wrapper">
                      <label for="next_payment" class="form-label">{{__('message.Next Payment Date')}} </label>
                      <input type="date" class="form-control border" id="next_payment" name="next_payment"></input>
                  </div>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="submit" class="btn btn-primary">{{__('message.Add Income')}}</button>
                  <select class="form-select" name="lang" id="lang">
                  <option value="en" selected>EN</option>
                  <option value="ar">AR</option>
                  </select>
              </div>
          </form>
      </div>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentStatus = document.querySelectorAll('.payment-status');
    const paidWrapper   = document.getElementById('paid-wrapper');

    function togglePaidField() {
        const selected = paymentStatus[0].value;

        if (selected === 'unpaid') {
            paidWrapper.style.display = 'block';
        } else {
            paidWrapper.style.display = 'none';
        }
    }

    paymentStatus.forEach(radio => {
        radio.addEventListener('change', togglePaidField);
    });

    togglePaidField();
});
</script>

@endpush