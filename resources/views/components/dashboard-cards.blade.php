    <!-- Total Income Card -->
        <div class="col-md-3">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>${{ number_format($totalIncome) }}</h3>
              <p>{{ __('message.Total Income') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-money-bill-wave"></i>
            </div>
            <a href="" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Outcome Card -->
        <div class="col-md-3">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>${{ number_format($totalOutcome) }}</h3>
              <p>{{ __('message.Total Outcome') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-receipt"></i>
            </div>
            <a href="" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Profit Card -->
        <div class="col-md-3">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>${{ number_format($profit) }}</h3>
              <p>{{ __('message.Total Profit') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-chart-line"></i>
            </div>
            <a href="" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Clients Card -->
        <div class="col-md-3">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>{{ $totalStudents }}</h3>
              <p>{{ __('message.Total Clients') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>
        <!-- Total outdated payments -->
        <div class="col-md-3">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $totalOutdatedPayments }}</h3>
              <p>{{ __('message.Outdated Payments') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-money-bill-wave"></i>
            </div>
          </div>
        </div>