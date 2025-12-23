    <!-- Total Income Card -->
    <div class="col-md-3">
        <div class="small-box bg-success bg-gradient">
            <div class="inner">
                <h3>${{ number_format($totalIncome) }}</h3>
                <p>{{ __('message.Total Income') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-money-bill-wave"></i>
            </div>
            
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
                <i class="fa fa-receipt"></i>
            </div>
          
        </div>
    </div>

    <!-- Total Profit Card -->
    <div class="col-md-3">
        <div class="small-box bg-info bg-gradient">
            <div class="inner">
                <h3>${{ number_format($totalProfit ) }}</h3>
                <p>{{ __('message.Total Profit') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-chart-line"></i>
            </div>
            
        </div>
    </div>

    <!-- Total Clients Card -->
    <div class="col-md-3">
        <div class="small-box bg-warning bg-gradient">
            <div class="inner">
                <h3>{{ $totalClients }}</h3>
                <p>{{ __('message.Total Clients') }}</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
          
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
      </div>
    </div>
  
    <!-- Total Recurring payments  -->
      <div class="col-md-3">
        <div class="small-box bg-secondary bg-gradient">
          <div class="inner">
            <h3>{{ $totalRecurring }}</h3>
            <p>{{ __('income.Recurring Payments') }}</p>
          </div>
          <div class="icon">
            <i class="fas fa-sync-alt"></i>
          </div>
        </div>
      </div>
    <!-- Total Onetime Payments  -->
      <div class="col-md-3">
        <div class="small-box bg-success bg-gradient">
          <div class="inner">
            <h3>{{ $totalOnetime }}</h3>
            <p>{{ __('income.Onetime Payments') }}</p>
          </div>
          <div class="icon">
            <i class="fas fa-hand-holding-usd"></i>
          </div>
        </div>
      </div>
        <!-- Total paid invoices  -->
      <div class="col-md-3">
        <div class="small-box bg-red bg-gradient">
          <div class="inner">
            <h3>{{ $totalInvoices }}</h3>
            <p>{{ __('message.Total Invoices') }}</p>
          </div>
          <div class="icon">
            <i class="fas fa-file-invoice-dollar"></i>
          </div>
        </div>
      </div>