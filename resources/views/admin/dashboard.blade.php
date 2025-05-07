@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
  @media print {
      .no-print {
          display: none;
      }

      .card {
          margin-bottom: 20px;
      }

      #content {
          padding: 0;
      }

      canvas {
          max-width: 100%;
          height: auto !important;
      }
  }
</style>
<div id="content" class="d-flex flex-column">
        <h1 class="mb-4 text-center">Dashboard</h1>
        <div class="flex-grow-1 p-3">
              <div class="d-flex justify-content-end mb-3">
                  <button class="no-print btn btn-primary mb-3 me-2" onclick="window.print()">Print Dashboard</button>
                  <button class="no-print btn btn-secondary mb-3" id="exportPdfBtn">Export as PDF</button>
              </div>
  
              <div class="d-flex flex-wrap justify-content-between mb-4">
                  <div class="card flex-fill mx-2 mb-3">
                      <div class="card-body text-center bg-success text-white">
                          <h5 class="card-title">Total Income (Month)</h5>
                          <h2 class="card-text text-white">{{$totalIncome}} USD</h2>
                          <small></small>
                      </div>
                  </div>
                  <div class="card flex-fill mx-2 mb-3">
                      <div class="card-body text-center text-white bg-danger">
                          <h5 class="card-title">Total Outcome (Month)</h5>
                          <h2 class="card-text text-white">{{$totalOutcome}} USD</h2>
                          <small></small>
                      </div>
                  </div>
                  <div class="card flex-fill mx-2 mb-3">
                      <div class="card-body text-center text-white bg-primary">
                          <h5 class="card-title">Profit (Month)</h5>
                          <h2 class="card-text text-white">{{$profit}} USD</h2>
                          <small></small>
                      </div>
                  </div>
                  <div class="card flex-fill mx-2 mb-3">
                      <div class="card-body text-center text-white bg-info">
                          <h5 class="card-title">New Students</h5>
                          <h2 class="card-text text-white">{{$totalStudents}}</h2>
                          <small></small>
                      </div>
                  </div>
              </div>
  
              <div class="flex-grow-1">
                  <h3 class="text-center mb-4">Income, Outcome, and Profit for {{$currentMonth}}</h3>
                  <canvas id="lineChart" class="w-100"></canvas>
              </div>
      </div>
    </div>
@push('scripts')
<script>
  document.getElementById('exportPdfBtn').addEventListener('click', function () {
  html2canvas(document.querySelector('#content')).then(canvas => {
      const { jsPDF } = window.jspdf;
      const imgData = canvas.toDataURL('image/jpeg', 1.0);
      const imgWidth = 295;
      const pageHeight = 200;
      const imgHeight = canvas.height * imgWidth / canvas.width;
      const pdf = new jsPDF('l', 'mm', [imgWidth, imgHeight]);
      pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight);
      pdf.save('dashboard.pdf');
  });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Chart Data
      var ctx = document.getElementById('lineChart').getContext('2d');
      var lineChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: @json($labels),
              datasets: [
                  {
                      label: 'Income',
                      data: @json($incomeData),
                      borderColor: '#28a745',
                      backgroundColor: 'rgba(40, 167, 69, 0.1)',
                      borderWidth: 2,
                      fill: true
                  },
                  {
                      label: 'Outcome',
                      data: @json($outcomeData),
                      borderColor: '#dc3545',
                      backgroundColor: 'rgba(220, 53, 69, 0.1)',
                      borderWidth: 2,
                      fill: true
                  },
                  {
                      label: 'Profit',
                      data: @json($profitData),
                      borderColor: '#17a2b8',
                      backgroundColor: 'rgba(23, 162, 184, 0.1)',
                      borderWidth: 2,
                      fill: true
                  }
              ]
          },
          options: {
              responsive: true,
              plugins: {
                  title: {
                      display: true,
                      text: 'Daily Income, Outcome, and Profit ({{ $currentMonth }})'
                  },
                  tooltip: {
                      mode: 'index',
                      intersect: false
                  }
              },
              scales: {
                  x: {
                      display: true,
                      title: {
                          display: true,
                          text: 'Day of Month'
                      }
                  },
                  y: {
                      display: true,
                      title: {
                          display: true,
                          text: 'Amount (USD)'
                      },
                      beginAtZero: true
                  }
              },
              interaction: {
                  intersect: false,
                  mode: 'nearest'
              }
          }
      });
  });
  </script>
@endpush
@endsection
