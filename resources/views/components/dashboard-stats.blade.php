<!-- Row 1: Line Chart -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title fw-bold">{{__('message.Income, Outcome, and Profit for')}} {{$currentMonth}}</h3>
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
</div>

<!-- Row 2: Bar Chart & Payments -->
<div class="row mb-4">
  <div class="col-lg-7 d-flex flex-column">
    <!-- BAR CHART -->
    <div class="card flex-grow-1">
      <div class="card-header">
        <h3 class="card-title fw-bold">Icomes Comparision {{ now()->year . ' & ' . now()->year - 1 }}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body d-flex flex-column">
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
        <div class="chart flex-grow-1">
          <canvas id="barChart" style="min-height: 250px; height: 100%; max-height: 100%; max-width: 100%;"></canvas>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <div class="col-lg-5 d-flex flex-column">
    <!-- Upcoming Payments -->
    <div class="card card-success mb-4">
      <div class="card-header">
        <h3 class="card-title fw-bold">{{ __('message.Upcoming Payments') }}</h3>
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
          @forelse ($upcomingPayments->take(2) as $upcoming)
          @foreach ($upcoming->unpaidPayments as $payment)
          <li class="item">
            <div class="product-img">
              <i class="fas fa-user img-size-100"></i>
            </div>
            <div class="product-info">
              <a href="{{ route('details', ['income' => $upcoming->income_id]) }}"
                class="nav-link product-title">{{ $upcoming->client->full_name }}
                &dash; {{ $payment->trans_description }}
                <span class="badge badge-warning float-right">${{ number_format($payment->payment_amount) }}</span></a>
              <span class="product-description">
                <small class="badge badge-success"><i class="far fa-clock"></i>
                  @if(now()->startOfDay()->diffInDays($payment->next_payment, false) > 0)
                  {{ trans_choice('message.day', now()->startOfDay()->diffInDays($payment->next_payment, false), ['count' => now()->startOfDay()->diffInDays($payment->next_payment, false)])}}
                  @elseif(now()->startOfDay()->diffInDays($payment->next_payment, false) === 0)
                  {{ trans('message.Today') }}
                  @endif
                </small>
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
        <a href="{{ route('admin.upcoming') }}"
          class="nav-link uppercase">{{__('message.View All Upcoming Payments')}}</a>
      </div> <!-- /.card-footer -->
    </div>
    <!-- Outdated Payments-->
    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title fw-bold">{{__('message.Outdated Payments')}}</h3>
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
          @forelse ($outdatedPayments->take(3) as $out)
          @foreach ($out->unpaidPayments as $payment)
          <li class="nav-item item">
            <div class="product-img">
              <i class="fas fa-user img-size-100"></i>
            </div>
            <div class="product-info">
              <a href="{{ route('details', ['income' => $out->income_id]) }}"
                class="nav-link product-title">{{ $out->client->full_name }}
                &dash; {{ $payment->trans_description }}

                <span class="badge badge-danger float-right">${{ number_format($payment->payment_amount) }}</span></a>
              <span class="product-description">
                <small class="badge badge-danger"><i class="far fa-clock"></i>
                  {{ trans_choice('message.day', $out->next_payment->diffInDays(now()->startOfDay()), ['count' => $out->next_payment->diffInDays(now()->startOfDay())]) }}
                  {{ trans('message.overdue') }}
                </small>
              </span>
            </div>
          </li>
          @endforeach
          @empty
          <p class="p-3">{{__('message.No outdated payments')}}</p>
          @endforelse
        </ul><!-- /.item -->
      </div><!-- /.card-body -->
      <div class="card-footer text-center">
        <a href="{{ route('admin.outdated') }}"
          class="nav-link uppercase">{{__('message.View All Outdated Payments')}}</a>
      </div> <!-- /.card-footer -->
    </div>
  </div>
</div>

<!-- Row 3: Pie Charts & Logs -->
<div class="row">
  <div class="col-lg-4 d-flex flex-column">
    <!-- pie chart payments 1 -->
    <div class="card mb-4">
      <div class="card-header">
        <h3 class="card-title fw-bold">{{ __('message.Payments Stats') }}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <div class="chart-responsive">
              <canvas id="pieChart" height="150"></canvas>
            </div><!-- ./chart-responsive -->
          </div> <!-- /.col -->
          <div class="col-md-4 d-flex align-items-center">
            <ul class="chart-legend clearfix">
              @foreach (\App\Enums\PaymentStatus::cases() as $case)
              <li>
                <i
                  class="far fa-circle text-{{ $case === \App\Enums\PaymentStatus::UNPAID ? 'danger' : 'success' }}"></i>
                {{ $case->label() }}
              </li>
              @endforeach
            </ul>
          </div> <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.card-body -->
      <div class="card-footer p-0">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a href="#" class="nav-link">
              {{ \App\Enums\PaymentStatus::PAID->label() }}
              <span class="float-right text-success">
                {{ number_format($percentageSumPaid, 1) }}%
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              {{ \App\Enums\PaymentStatus::UNPAID->label() }}
              <span class="float-right text-danger">
                {{ number_format($percentageSumUnpaid, 1) }}%
              </span>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.footer -->
    </div>

    <!-- pie chart payments 2 -->
    
  </div>

  <div class="col-lg-8 d-flex flex-column">
    <!-- Activity Logs -->
    <div class="card flex-grow-1">
      <div class="card-header">
        <h3 class="card-title fw-bold">{{ __('message.Recent Activity Logs') }}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
          <thead>
            <tr>
              <th>Time</th>
              <th>Event</th>
              <th>Causer</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($logs as $log)
            <tr>
              <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
              <td>
                <span @class([ 'badge bg-success'=> $log->event == 'created',
                  'badge bg-warning' => $log->event == 'updated',
                  'badge bg-danger' => $log->event == 'deleted',
                  ])>
                  {{ $log->event }}
                </span>
              </td>
              <td>{{ $log->causer['name'] }}</td>
              <td>{{ $log->description }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center p-3">No recent activity.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="card-footer text-center">
        <a href="{{ route('activity.logs') }}" class="nav-link uppercase">{{__('message.View All Logs')}}</a>
      </div> <!-- /.card-footer -->
    </div><!-- /.card -->
  </div>
</div>
