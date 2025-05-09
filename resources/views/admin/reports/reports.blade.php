@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid">
        <h1 class="mb-4 text-center">Reports</h1>
        <div id="content" class="container-fluid">
          <div class="d-flex justify-content-end mb-3">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                  Filter by Date
              </button>
              <button onclick="window.print()" class="btn btn-warning ms-2">Print Report</button>
          </div>

          <div class="row mb-4">
              <div class="col-md-3">
                  <div class="card text-white bg-success shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Income</h5>
                          <p class="card-text">${{number_format($total_income)}}</p>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3">
                  <div class="card text-white bg-danger shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Outcome</h5>
                          <p class="card-text">${{number_format($total_outcome)}}</p>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3">
                  <div class="card text-white bg-primary shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Profit</h5>
                          <p class="card-text">${{number_format($total_profit, 2)}}</p>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3">
                  <div class="card text-white bg-info shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Students</h5>
                          <p class="card-text">{{$total_students}}</p>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="row">
              <div class="col-lg-6">
                  <div class="shadow p-3 mb-3">
                      <h4 class="text-center">Income</h4>
                      <div class="row mb-3">
                          <div class="col-sm-12">
                              <input type="text" id="search-input" class="form-control border"
                                  placeholder="Search for Items...">
                          </div>
                      </div>
  
                      <div class="table-responsive">
                          <table id="sortableTable" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th onclick="sortTable(0, this)">Client <span class="arrow"></span></th>
                                      <th onclick="sortTable(1, this)">Amount <span class="arrow"></span></th>
                                      <th onclick="sortTable(2, this)">Paid <span class="arrow"></span></th>
                                      <th onclick="sortTable(3, this)">Date <span class="arrow"></span></th>
                                  </tr>
                              </thead>
                              <tbody>
                          @foreach($incomes as $income)
                                      <tr>
                                          <td>{{$income->client->client_fname}} {{$income->client->client_lname}}
                                            <span class="badge bg-{{ 
                                              $income->status == 'complete' ? 'success' : 
                                              ($income->status == 'partial' ? 'warning' : 'danger') 
                                          }}">
                                            <small>{{ ucfirst($income->status) }}</small>  
                                          </span>
                                          </td>
                                          <td>${{number_format($income->amount, 2)}}</td>
                                          <td>${{$income->paid}}</td>
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
                      <h4 class="text-center">Outcome</h4>
                      <div class="row mb-3">
                          <div class="col-sm-12">
                              <input type="text" id="search-input1" class="form-control border"
                                  placeholder="Search for Items...">
                          </div>
                      </div>
  
                      <div class="table-responsive">
                          <table id="sortableTable1" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th onclick="sortTable1(0, this)">Category <span class="arrow"></span></th>
                                      <th onclick="sortTable1(0, this)">Subcategory <span class="arrow"></span></th>
                                      <th onclick="sortTable1(0, this)">Amount <span class="arrow"></span></th>
                                      <th onclick="sortTable1(0, this)">Date <span class="arrow"></span></th>
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
  
          <div class="row">
              <div class="col-lg-3">
                  <div class="shadow p-3 mb-3">
                      <h3 class="text-center">Income by Category</h3>
                      <canvas id="incomeCategoryChart"></canvas>
                  </div>
              </div>
              <div class="col-lg-3">
                  <div class="shadow p-3 mb-3">
                      <h3 class="text-center">Income by Subcategory</h3>
                      <canvas id="incomeSubcategoryChart"></canvas>
                  </div>
              </div>
              <div class="col-lg-3">
                  <div class="shadow p-3 mb-3">
                      <h3 class="text-center">Outcome by Category</h3>
                      <canvas id="outcomeCategoryChart"></canvas>
                  </div>
              </div>
  
              <div class="col-lg-3">
                  <div class="shadow p-3 mb-3" >
                      <h4 class="text-center">Outcome by Subcategory</h4>
                      <canvas id="outcomeSubcategoryChart"></canvas>
                  </div>
              </div>
          </div>
      </div>
{{-- filter by date model --}}
@include('admin.reports.partials.filter-date-model')
</div>
@push('scripts')
{{-- filter date model script --}}
<script src="{{asset('js/filter.js')}}"></script>
<script>
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

</script>
@endpush   
@endsection