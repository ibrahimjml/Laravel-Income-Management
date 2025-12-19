<div class="row">
  <!-- line chart total profit, income, outcome-->
  <div class="col-lg-7">
    <div class="card card-primary h-100">
      <div class="card-header">
        <h3 class="card-title">{{__('message.Income, Outcome, and Profit for')}} {{$currentMonth}}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="lineChart" style="min-height: 300px; height: 500px; max-height: 500px; max-width: 100%;"></canvas>
      </div><!-- /.card-body -->
    </div>
  </div>
  
  <div class="col-lg-5 mt-3 mt-lg-0">
    <div class="row">
      <div class="col-12">
        <!-- BAR CHART -->
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Icomes Comparision {{ now()->year .' & '. now()->year -1 }}</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex">
              <p class="d-flex flex-column">
                <span class="text-bold text-lg">${{ number_format($totalYearlyIncome, 2) }}</span>
                <span>{{ __('message.Total Income') }}</span>
              </p>
              <p class="ml-auto d-flex flex-column text-right">
                <span class="{{ $incomePercentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                  @if ($incomePercentageChange >= 0)
                  <i class="fas fa-arrow-up"></i>
                  @else
                  <i class="fas fa-arrow-down"></i>
                  @endif
                  {{ number_format(abs($incomePercentageChange), 1) }}%
                </span>
                <span class="text-muted">{{ __('message.Since last year') }}</span>
              </p>
            </div>
            <div class="chart">
              <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- Upcoming Payments -->
      <div class="col-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Upcoming Payments</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <ul class="products-list product-list-in-card pl-2 pr-2">
              @forelse ($upcomingPayments->take(2) as $income)
              @foreach ($income->payments as $payment)
              <li class="item">
                <div class="product-img">
                  <i class="fas fa-user img-size-100"></i>
                </div>
                <div class="product-info">
                  <a href="{{ route('admin.upcoming') }}" class="product-title">{{ $income->client->full_name }}
                    <span class="badge badge-warning float-right">${{ number_format($payment->payment_amount) }}</span></a>
                  <span class="product-description">
                    {{ $payment->description }}
                  </span>
                </div>
              </li>
              @endforeach
              @empty
              <p class="p-3">{{__('message.No upcoming payments')}}</p>
              @endforelse
            </ul><!-- /.item -->
          </div><!-- /.card-body -->
          <div class="card-footer text-center">
            <a href="{{ route('admin.upcoming') }}" class="uppercase">{{__('message.View All Upcoming Payments')}}</a>
          </div> <!-- /.card-footer -->
        </div>
      </div>
      <div class="col-12">
        <!-- Outdated Payments-->
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">{{__('message.Outdated Payments')}}</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <ul class="products-list product-list-in-card pl-2 pr-2">
              @forelse ($outdatedPayments->take(2) as $out)
              <li class="item">
                <div class="product-img">
                  <i class="fas fa-user img-size-100"></i>
                </div>
                <div class="product-info">
                  <a href="{{ route('details',['income'=>$out->income_id]) }}" class="product-title">{{ $out->client->full_name }}
                    <span class="badge badge-danger float-right">${{ number_format($out->payments->first()->payment_amount) }}</span></a>
                  <span class="product-description">
                    {{ \Carbon\Carbon::parse($out->next_payment)->diffForHumans() }}
                  </span>
                </div>
              </li>
              @empty
              <p class="p-3">{{__('message.No outdated payments')}}</p>
              @endforelse
            </ul><!-- /.item -->
          </div><!-- /.card-body -->
          <div class="card-footer text-center">
            <a href="{{ route('admin.outdated') }}" class="uppercase">{{__('message.View All Outdated Payments')}}</a>
          </div> <!-- /.card-footer -->
        </div>
      </div>
    </div>
  </div>
</div>