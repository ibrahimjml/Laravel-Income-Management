@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid">
        <h1 class="mb-4 text-center">Reports</h1>
        <div id="content" class="container-fluid">
          <div class="d-flex justify-content-end mb-3">
              <button id="print-report" class="btn btn-warning ms-2">Print Report</button>
          </div>
  

  
          <div class="row mb-4">
              <div class="col-md-3">
                  <div class="card text-white bg-success shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Income</h5>
                          <p class="card-text">${{$total_income}}</p>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3">
                  <div class="card text-white bg-danger shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Outcome</h5>
                          <p class="card-text">${{$total_outcome}}</p>
                      </div>
                  </div>
              </div>
  
              <div class="col-md-3">
                  <div class="card text-white bg-primary shadow">
                      <div class="card-body">
                          <h5 class="card-title">Total Profit</h5>
                          <p class="card-text">${{$total_profit}}</p>
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
                                          <td>{{$income->client->client_fname}} {{$income->client->client_lname}}</td>
                                          <td>${{$income->amount}}</td>
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
                                          <td>${{$outcome->amount}}</td>
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
                      <h3 class="text-center">Outcome by Category</h3>
                      <canvas id="outcomeCategoryChart"></canvas>
                  </div>
              </div>
  
              <div class="col-lg-3">
                  <div class="shadow p-3 mb-3">
                      <h3 class="text-center">Income by Subcategory</h3>
                      <canvas id="incomeSubcategoryChart"></canvas>
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
    </div>

@push('scripts')

@endpush    
@endsection