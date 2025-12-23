@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid">
        <h1 class="mb-4 text-center">{{__('message.Reports')}}</h1>
        <div id="content" class="container-fluid">
          <div class="d-flex justify-content-end gap-3 mb-3">
            {{-- clear filters --}}
            @if($date_range)
              <button class="btn btn-danger mr-3" onclick="window.location.href='/admin/reports'">
                <i class="fa fa-close mr-2"></i>
                {{__('message.Clear')}}
              </button>
              @endif
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                  {{__('message.Filter by Date')}}
              </button>
              <button onclick="window.print()" class="btn btn-warning ms-2">{{__('message.Print Report')}}</button>
          </div>

        <div class="row mb-4"><!-- dashboard cards -->
        <x-reports-cards :totalIncome="$total_income" :totalOutcome="$total_outcome" :totalProfit="$total_profit" :totalClients="$total_students" :totalInvoices="$total_invoices" :totalIncomeRemaining="$total_income_remaining" :totalRecurring="$total_recurring_payments" :totalOnetime="$total_onetime_payments"/>
        </div><!-- end dashboard cards -->
  
          <div class="row">
              <div class="col-lg-6">
                  <div class="shadow p-3 mb-3">
                      <h4 class="text-center">{{__('message.Incomes')}}</h4>
                      <div class="row mb-3">
                          <div class="col-sm-12">
                              <input type="text" id="search-input" class="form-control border"
                                  placeholder="{{__('message.Search for Items')}}...">
                          </div>
                      </div>
  
                      <div class="table-responsive">
                          <table id="sortableTable" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th onclick="sortTable(0, this)">{{__('message.Client')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable(1, this)">{{__('message.Status')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable(2, this)">{{__('message.Client Type')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable(3, this)">{{__('message.Amount')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable(4, this)">Final <span class="arrow"></span></th>
                                      <th onclick="sortTable(5, this)">{{__('message.Total Paid')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable(6, this)">{{__('message.Date')}} <span class="arrow"></span></th>
                                  </tr>
                              </thead>
                              <tbody>
                          @foreach($incomes as $income)
                                      <tr>
                                          <td>{{$income->client->full_name}}</td>
                                          <td>  <span class="badge bg-{{ 
                                                  $income->status == \App\Enums\IncomeStatus::COMPLETE ? 'success' : 
                                                  ($income->status == \App\Enums\IncomeStatus::PARTIAL ? 'warning' : 'danger') 
                                                   }}">
                                                  <small>{{ $income->status->label() }}</small>
                                                 </span>
                                          </span></td>
                                          <td>{{ $income->client->types->first()?->type_name}}</td>
                                          <td>${{number_format($income->amount)}}</td>
                                          <td>${{number_format($income->final_amount)}}</td>
                                          <td>${{$income->total_paid}}</td>
                                          <td>{{ date('M d, Y', strtotime($income->created_at)) }}</td>
                                      </tr>
                      @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
  
              <div class="col-lg-6">
  
                  <div class="shadow p-3 mb-3">
                      <h4 class="text-center">{{__('message.Outcomes')}}</h4>
                      <div class="row mb-3">
                          <div class="col-sm-12">
                              <input type="text" id="search-input1" class="form-control border"
                                  placeholder="{{__('message.Search for outcomes')}}...">
                          </div>
                      </div>
  
                      <div class="table-responsive">
                          <table id="sortableTable1" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th onclick="sortTable1(0, this)">{{__('message.Category')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable1(1, this)">{{__('message.Subcategory')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable1(2, this)">{{__('message.Amount')}} <span class="arrow"></span></th>
                                      <th onclick="sortTable1(3, this)">{{__('message.Date')}} <span class="arrow"></span></th>
                                  </tr>
                              </thead>
                              <tbody>
                          @foreach($outcomes as $outcome)
                                      <tr>
                                          <td>{{$outcome->subcategory->category->category_name}}</td>
                                          <td>{{$outcome->subcategory->sub_name}}</td>
                                          <td>${{number_format($outcome->amount,2)}}</td>
                                          <td>{{ date('M d, Y', strtotime($outcome->created_at)) }}</td>
                                      </tr>
                          @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="row"><!-- reports stats -->
           @include('admin.reports.partials.reports-stats')
          </div>
      </div>
{{-- filter by date model --}}
@include('admin.reports.partials.filter-date-model')
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- filter date model script -->
<script src="{{asset('js/filter.js')}}"></script>
@endpush   