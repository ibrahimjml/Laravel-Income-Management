    <!-- Total Income Card -->
        <div class="col-md-3">
          <div class="small-box bg-success bg-gradient">
            <div class="inner">
              <h3>${{ number_format($totalIncome) }}</h3>
              <p>{{ __('message.Total Income') }}</p>
            </div>
            <div class="icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="javasript:void(0);" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Outcome Card -->
        <div class="col-md-3">
          <div class="small-box bg-danger bg-gradient">
            <div class="inner">
              <h3>${{ number_format($totalOutcome) }}</h3>
              <p>{{ __('message.Total Outcome') }}</p>
            </div>
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <a href="javasript:void(0);" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Profit Card -->
        <div class="col-md-3">
          <div class="small-box bg-primary bg-gradient">
            <div class="inner">
              <h3>${{ number_format($profit) }}</h3>
              <p>{{ __('message.Total Profit') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-chart-line"></i>
            </div>
            <a href="javasript:void(0);" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>

        <!-- Total Clients Card -->
        <div class="col-md-3">
          <div class="small-box bg-info bg-gradient">
            <div class="inner">
              <h3>{{ $totalStudents }}</h3>
              <p>{{ __('message.Total Clients') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="javasript:void(0);" class="small-box-footer">
              {{ $currentMonth }} <i class="fa fa-info"></i>
            </a>
          </div>
        </div>
        <!-- Total outdated payments -->
        <div class="col-md-3">
          <div class="small-box bg-warning bg-gradient">
            <div class="inner">
              <h3>{{ $outdatedPayments->count() }}</h3>
              <p>{{ __('message.Outdated Payments') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-money-bill-wave"></i>
            </div>
              <a href="{{ route('admin.outdated') }}" class="small-box-footer">
               <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <!-- Total upcoming payments -->
        <div class="col-md-3">
          <div class="small-box bg-primary bg-gradient">
            <div class="inner">
              <h3>{{ $upcomingPayments->count() }}</h3>
              <p>{{ __('message.Upcoming Payments') }}</p>
            </div>
            <div class="icon">
              <i class="fa fa-credit-card"></i>
            </div>
              <a href="{{ route('admin.upcoming') }}" class="small-box-footer">
                <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <!-- Total paid invoices  -->
        <div class="col-md-3">
          <div class="small-box bg-info bg-gradient">
            <div class="inner">
              <h3>{{ $totalPaidInvoices }}</h3>
              <p>{{ __('message.Total Paid Invoices') }}</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
              <a href="{{ route('invoices.index')  }}" class="small-box-footer">
               <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <!-- Total unpaid invoices  -->
        <div class="col-md-3">
          <div class="small-box bg-red bg-gradient">
            <div class="inner">
              <h3>{{ $totalUnpaidInvoices }}</h3>
              <p>{{ __('message.Total Unpaid Invoices') }}</p>
            </div>
            <div class="icon">
              <i class="fas fa-file-invoice-dollar"></i>
            </div>
              <a href="{{ route('invoices.index')  }}" class="small-box-footer">
               <i class="fa fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <!-- Total Income Remaining  -->
        <div class="col-md-3">
          <div class="small-box bg-black bg-gradient">
            <div class="inner">
              <h3>${{ number_format($totalIncomeRemaining)}}</h3>
              <p>{{ __('income.Total Income Remaining') }}</p>
            </div>
            <div class="icon bf-light">
              <i class="fas fa-wallet text-light"></i>
            </div>
              <a href="javasript:void(0);" class="small-box-footer">
              {{ $currentMonth }} <i class="fas fa-info"></i>
            </a>
          </div>
        </div>