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
            <li class="nav-item has-treeview {{ request()->routeIs('admin.clients*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link  py-3 text-lg">
                    <i class="nav-icon fas fa-users"></i>
                    <p class="ml-2">
                        {{__('message.Clients')}}
                        <i class="right fas fa-angle-left mt-2"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('admin.clients')}}" 
                           class="nav-link {{ request()->routeIs('admin.clients') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{__('message.All Clients')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('trashed.clients')}}" 
                           class="nav-link {{ request()->routeIs('trashed.clients') ? 'active' : '' }}">
                            <i class="fas fa-trash nav-icon"></i>
                            <p>{{__('message.Trashed Client')}}</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Incomes -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.incomes') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link py-3 text-lg">
                    <i class="nav-icon fa fa-money-bill-wave"></i>
                    <p class="ml-2">
                      {{__('message.Incomes')}}
                    <i class="right fas fa-angle-left mt-2"></i>
                    </p>
                </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('admin.incomes')}}" 
                           class="nav-link {{ request()->routeIs('admin.incomes') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-money-bill-wave"></i>
                            <p>{{__('message.All Incomes')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" 
                           class="nav-link {{ request()->routeIs('admin.clients.create') ? 'active' : '' }}">
                            <i class="fas fa-trash nav-icon"></i>
                            <p>{{__('message.Trashed Incomes')}}</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Outcomes -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.outcomes') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link  py-3 text-lg">
                    <i class="nav-icon fa fa-credit-card"></i>
                    <p class="ml-2">
                      {{__('message.Outcomes')}}
                     <i class="right fas fa-angle-left mt-2"></i>
                    </p>
                </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('admin.outcomes')}}" 
                           class="nav-link {{ request()->routeIs('admin.outcomes') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-credit-card"></i>
                            <p>{{__('message.All Outcomes')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" 
                           class="nav-link {{ request()->routeIs('admin.outcomes.trashed') ? 'active' : '' }}">
                            <i class="fas fa-trash nav-icon"></i>
                            <p>{{__('message.Trashed Outcomes')}}</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Payments -->
            <li class="nav-item has-treeview {{ request()->routeIs('admin.payments') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link py-3 text-lg">
                    <i class="nav-icon fa fa-calendar"></i>
                    <p class="ml-2">
                      {{__('message.Payments')}}
                      <i class="right fas fa-angle-left mt-2"></i>
                    </p>
                </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('admin.payments')}}" 
                           class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-credit-card"></i>
                            <p>{{__('message.All Payments')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.upcoming')}}" 
                           class="nav-link ">
                              <i class="nav-icon fa fa-calendar"></i>
                            <p>{{__('message.Upcoming Payments')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.today')}}" 
                           class="nav-link ">
                              <i class="nav-icon fa fa-calendar"></i>
                            <p>{{__('message.Today\'s Payments')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.outdated')}}" 
                           class="nav-link ">
                              <i class="nav-icon fa fa-calendar"></i>
                            <p>{{__('message.Outdated Payments')}}</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Discounts -->
            <li class="nav-item">
                <a href="{{ route('discounts.index') }}" class="nav-link {{ request()->routeIs('discounts.index') ? 'active' : '' }} py-3 text-lg ">
                    <i class="nav-icon fas fa-percentage"></i>
                    <p class="ml-2">{{__('message.Discounts')}}</p>
                </a>
            </li>
            <!-- Invoices -->
            <li class="nav-item">
                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('discounts.index') ? 'active' : '' }} py-3 text-lg ">
                    <i class="nav-icon fas fa-file"></i>
                    <p class="ml-2">{{__('message.Invoices')}}</p>
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