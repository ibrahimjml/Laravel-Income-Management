<div class="col-lg-3"><!-- Income by Category -->
  <div class="shadow p-3 mb-3">
    <h3 class="text-center">{{__('message.Income by Category')}}</h3>
    <canvas id="incomeCategoryChart"></canvas>
  </div>
</div><!-- end /Income by Category -->
<div class="col-lg-3"><!-- Income by Subcategory -->
  <div class="shadow p-3 mb-3">
    <h3 class="text-center">{{__('message.Income by Subcategory')}}</h3>
    <canvas id="incomeSubcategoryChart"></canvas>
  </div>
</div><!-- end /Income by Subcategory -->
<div class="col-lg-3"><!-- Outcome by Category -->
  <div class="shadow p-3 mb-3">
    <h3 class="text-center">{{__('message.Outcome by Category')}}</h3>
    <canvas id="outcomeCategoryChart"></canvas>
  </div>
</div><!-- end /Outcome by Category -->

<div class="col-lg-3"><!-- Outcome by Subcategory -->
  <div class="shadow p-3 mb-3">
    <h4 class="text-center">{{__('message.Outcome by Subcategory')}}</h4>
    <canvas id="outcomeSubcategoryChart"></canvas>
  </div>
</div><!-- end /Outcome by Subcategory -->
<div class="col-lg-3"><!-- Payments Stats -->
  <div class="shadow p-3 mb-3">
    <h4 class="text-center">{{__('message.Payments Stats')}}</h4>
    <canvas id="paymentsStatsChart"></canvas>
    <div class="card-footer p-0">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="#" class="nav-link">
          <b>{{ $payments_stats['paid']['count'] }}</b>  {{ \App\Enums\PaymentStatus::PAID->label() }}
            <span class="float-right text-success">
              {{ number_format($payments_stats['paid']['percentage'], 1) }}%
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
        <b>{{ $payments_stats['unpaid']['count'] }}</b>  {{ \App\Enums\PaymentStatus::UNPAID->label() }}
            <span class="float-right text-danger">
              {{ number_format($payments_stats['unpaid']['percentage'], 1) }}%
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div><!-- end /Payments Stats -->
<div class="col-lg-3"><!-- Incomes Stats -->
  <div class="shadow p-3 mb-3">
    <h4 class="text-center">{{ __('message.Incomes Stats') }}</h4>
    <canvas id="incomeStatsChart"></canvas>
        <div class="card-footer p-0">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="#" class="nav-link">
          <b>{{ $income_stats['complete']['count'] }}</b>  {{ \App\Enums\IncomeStatus::COMPLETE->label() }}
            <span class="float-right text-success">
              {{ number_format($income_stats['complete']['percentage'], 1) }}%
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
          <b>{{ $income_stats['partial']['count'] }}</b>  {{ \App\Enums\IncomeStatus::PARTIAL->label() }}
            <span class="float-right text-danger">
              {{ number_format($income_stats['partial']['percentage'], 1) }}%
            </span>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
          <b>{{ $income_stats['pending']['count'] }}</b>  {{ \App\Enums\IncomeStatus::PENDING->label() }}
            <span class="float-right text-danger">
              {{ number_format($income_stats['pending']['percentage'], 1) }}%
            </span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div><!-- end /Incomes Stats -->

<div class="col-lg-3"><!-- Clients Stats -->
  <div class="shadow p-3 mb-3">
    <h4 class="text-center">{{ __('message.Clients Stats') }}</h4>
    <canvas id="clientsStatsChart"></canvas>
        <div class="card-footer p-0">
      <ul class="nav nav-pills flex-column">
        @foreach ($clients_stats['by_type'] as $client)
        <li class="nav-item">
          <a href="#" class="nav-link">
          <b>{{ $client->total_clients }}</b>  {{ $client->type_name }} 

          <span class="float-right text-success">
              {{ number_format($client->percentage, 1) }}%
            </span>
          </a>
        </li>
          @endforeach
      </ul>
    </div>
  </div>
</div><!-- end /Clients Stats -->
@push('scripts')
<!-- doughnut carts -->
@php
    $clientLabels = $clients_stats['by_type']->pluck('type_name');
    $clientPayments = $clients_stats['by_type']->pluck('total_payment_amount');
@endphp
<script>
  // income Category Chart
  var incomeCategoryCtx = document.getElementById('incomeCategoryChart').getContext('2d');
        var incomeCategoryChart = new Chart(incomeCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($incomeCategoryData->toArray(), 'category')),
                datasets: [{
                    label: 'Income by Category',
                    data: @json(array_column($incomeCategoryData->toArray(), 'total_amount')),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
          });
// income Subcategory Chart
          var incomeSubcategoryCtx = document.getElementById('incomeSubcategoryChart').getContext('2d');
        var incomeSubcategoryChart = new Chart(incomeSubcategoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($incomeSubcategoryData->toArray(), 'subcategory')),
                datasets: [{
                    label: 'Income by Subcategory',
                    data: @json(array_column($incomeSubcategoryData->toArray(), 'total_amount')),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
        });   
  // outcome Category Chart       
        var outcomeCategoryCtx = document.getElementById('outcomeCategoryChart').getContext('2d');
        var outcomeCategoryChart = new Chart(outcomeCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($outcomeCategoryData->toArray(), 'category')),
                datasets: [{
                    label: 'Outcome by Category',
                    data: @json(array_column($outcomeCategoryData->toArray(), 'total_amount')),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
        });
// outcome Subcategory Chart
        var outcomeSubcategoryCtx = document.getElementById('outcomeSubcategoryChart').getContext('2d');
        var outcomeSubcategoryChart = new Chart(outcomeSubcategoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($outcomeSubcategoryData->toArray(), 'subcategory')),
                datasets: [{
                    label: 'Outcome by Subcategory',
                    data: @json(array_column($outcomeSubcategoryData->toArray(), 'total_amount')),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                }]
            }
        });
// Payments Stats Chart
        var outcomeSubcategoryCtx = document.getElementById('paymentsStatsChart').getContext('2d');
        var outcomeSubcategoryChart = new Chart(outcomeSubcategoryCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ \App\Enums\PaymentStatus::PAID->label() }}',
                    '{{ \App\Enums\PaymentStatus::UNPAID->label() }}',
                    ],
                datasets: [{
                    label: '{{__('message.Payments Stats')}}',
                    data: [@json($payments_stats['paid']['total']), @json($payments_stats['unpaid']['total'])],
                    backgroundColor: ['#00a65a', '#FF0000']
                }]
            }
        });
// Incomes Stats Chart
        var outcomeSubcategoryCtx = document.getElementById('incomeStatsChart').getContext('2d');
        var outcomeSubcategoryChart = new Chart(outcomeSubcategoryCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ \App\Enums\IncomeStatus::COMPLETE->label() }}',
                    '{{ \App\Enums\IncomeStatus::PARTIAL->label() }}',
                    '{{ \App\Enums\IncomeStatus::PENDING->label() }}',
                    ],               
                 datasets: [{
                    label: '{{ __('message.Incomes Stats') }}',
                    data: [@json($income_stats['complete']['total']), @json($income_stats['partial']['total']), @json($income_stats['pending']['total'])],
                    backgroundColor: ['#00a65a', '#FFD41D', '#FF0000']
                }]
            }
        });
// Clients Stats Chart
        var outcomeSubcategoryCtx = document.getElementById('clientsStatsChart').getContext('2d');
        var outcomeSubcategoryChart = new Chart(outcomeSubcategoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($clientLabels),       
                 datasets: [{
                    label: '{{ __('message.Total Paid') }}',
                    data: @json($clientPayments),
                    backgroundColor: ['#00a65a','#FFD41D','#FF0000','#3c8dbc','#605ca8']
                }]
            }
        });

</script>
@endpush