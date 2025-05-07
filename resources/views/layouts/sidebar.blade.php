<h1 class="flex align-items-center justify-content-center p-1" style="background-color: #2f89fc;">
  <span class="brand-text font-weight-light text-white">System Management</span>
</h1>
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} py-3 text-lg">
                  <i class="nav-icon fa fa-home fa-lg"></i>
                  <p class="ml-2">Dashboard</p>
              </a>
          </li>

            <!-- Clients -->
            <li class="nav-item">
                <a href="{{ route('admin.clients') }}" class="nav-link {{ request()->routeIs('admin.clients') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-user"></i>
                    <p class="ml-2">Clients</p>
                </a>
            </li>

            <!-- Incomes -->
            <li class="nav-item">
                <a href="{{ route('admin.incomes') }}" class="nav-link {{ request()->routeIs('admin.incomes') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-money"></i>
                    <p class="ml-2">Incomes</p>
                </a>
            </li>

            <!-- Outcomes -->
            <li class="nav-item">
                <a href="{{ route('admin.outcomes') }}" class="nav-link {{ request()->routeIs('admin.outcomes') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-credit-card"></i>
                    <p class="ml-2">Outcomes</p>
                </a>
            </li>

            <!-- Payments -->
            <li class="nav-item">
                <a href="{{ route('admin.payments') }}" class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }} py-3 text-lg">
                    <i class="nav-icon fa fa-calendar"></i>
                    <p class="ml-2">Payments</p>
                </a>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }} py-3 text-lg ">
                    <i class="nav-icon fa fa-file-text"></i>
                    <p class="ml-2">Reports</p>
                </a>
            </li>

            <!-- Logout -->
            <div class="mt-auto p-2">
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="nav-link btn btn-link text-left text-white px-3 py-3 w-100">
                      <i class="nav-icon fa fa-sign-out fa-lg"></i>
                      <span class="ml-3">Logout</span>
                  </button>
              </form>
          </div>
        </ul>
    </nav>
</div>