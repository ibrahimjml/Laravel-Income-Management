<h1 class="d-flex align-items-center justify-content-center p-1 text-white"
  style="background-color: #2f89fc; font-size: 30px; white-space: nowrap; height: 70px;"  >
  {{ __('message.Income System') }}
</h1>
  <!-- Sidebar -->
  <div class="sidebar d-flex justify-content-end">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} py-3 text-lg">
                  <i class="nav-icon fa fa-home fa-lg"></i>
                  <p class="ml-2">{{__('message.Dashboard')}}</p>
              </a>
          </li>

            <!-- Clients -->
            <li class="nav-item">
                <a href="{{ route('admin.clients') }}" class="nav-link {{ request()->routeIs('admin.clients') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-user"></i>
                    <p class="ml-2">{{__('message.Clients')}}</p>
                </a>
            </li>

            <!-- Incomes -->
            <li class="nav-item">
                <a href="{{ route('admin.incomes') }}" class="nav-link {{ request()->routeIs('admin.incomes') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-money-bill-wave"></i>
                    <p class="ml-2">{{__('message.Incomes')}}</p>
                </a>
            </li>

            <!-- Outcomes -->
            <li class="nav-item">
                <a href="{{ route('admin.outcomes') }}" class="nav-link {{ request()->routeIs('admin.outcomes') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-credit-card"></i>
                    <p class="ml-2">{{__('message.Outcomes')}}</p>
                </a>
            </li>

            <!-- Payments -->
            <li class="nav-item">
                <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-calendar"></i>
                    <p class="ml-2">{{__('message.Payments')}}</p>
                </a>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }} py-3 text-lg ">
                    <i class="nav-icon fa fa-file"></i>
                    <p class="ml-2">{{__('message.Reports')}}</p>
                </a>
            </li>

            <!-- Logout -->
          <div class="mt-auto p-2 text-end ">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn  text-end text-white d-flex align-items-center gap-3 justify-content-start">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <span >{{ __('message.Logout') }}</span>
            </button>
          </form>
        </div>
        </ul>
    </nav>
</div>