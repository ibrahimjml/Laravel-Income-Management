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
    <h1 class="mb-4 text-center">{{__('message.Dashboard')}}</h1>
    <div class="flex-grow-1 p-3 ">
      <div class="d-flex justify-content-end gap-3 mb-3">
        <button class="no-print btn btn-primary mb-3 me-2"
          onclick="window.print()">{{__('message.Print Dashboard')}}</button>
        <button class="no-print btn btn-secondary mb-3" id="exportPdfBtn">{{__('message.Export as PDF')}}</button>
      </div>

      <div class="row mb-4"><!-- dashboard cards -->
      <x-dashboard-cards :totalIncome="$totalIncome" :currentMonth="$currentMonth" :totalOutcome="$totalOutcome" :profit="$profit" :totalIncomeRemaining="$totalIncomeRemaining" :totalStudents="$totalStudents" :outdatedPayments="$outdatedPayments" :upcomingPayments="$upcomingPayments" :totalPaidInvoices="$totalPaidInvoices"  :totalUnpaidInvoices="$totalUnpaidInvoices"/>
      </div><!-- end dashboard cards -->

      <!-- dashboard stats -->
      <x-dashboard-stats :currentMonth="$currentMonth" :upcomingPayments="$upcomingPayments" :outdatedPayments="$outdatedPayments" :totalYearlyIncome="$totalYearlyIncome" :incomePercentageChange="$incomePercentageChange" :labels="$labels" :percentageSumPaid="$percentageSumPaid" :percentageSumUnpaid="$percentageSumUnpaid" :logs="$logs"/>

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
      document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('lineChart').getContext('2d');

        const lineChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: @json($labels),
            datasets: [
              {
                label: '{{__("message.Total Income")}}',
                data: @json($incomeData),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#28a745',
                borderWidth: 1,
                tension: 0.4,
                fill: true
              },
              {
                label: '{{__("message.Total Outcome")}}',
                data: @json($outcomeData),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.5)',
                pointBackgroundColor: '#dc3545',
                pointBorderColor: '#dc3545',
                borderWidth: 1,
                tension: 0.4,
                fill: true
              },
              {
                label: '{{__("message.Total Profit")}}',
                data: @json($profitData),
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23, 162, 184, 0.4)',
                pointBackgroundColor: '#17a2b8',
                pointBorderColor: '#17a2b8',
                borderWidth: 1,
                tension: 0.4,
                fill: true
              }
            ]
          },
          options: {
            layout: {
              padding: 1,
            },
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              title: {
                display: false,
                text: '{{__("message.Daily Income, Outcome, and Profit")}} ({{ $currentMonth }})',
                font: {
                  size: 18,
                  weight: 'bold'
                },
                padding: {
                  top: 10,
                  bottom: 30
                }
              },
              tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: '#f8f9fa',
                titleColor: '#000',
                bodyColor: '#000',
                borderColor: '#ccc',
                borderWidth: 1
              },
              legend: {
                labels: {
                  font: {
                    size: 14
                  }
                }
              }
            },
            interaction: {
              mode: 'nearest',
              axis: 'x',
              intersect: false
            },
            scales: {
              x: {
                title: {
                  display: true,
                  text: '{{$currentMonth}}',
                  font: {
                    size: 14
                  }
                },
                ticks: {
                  maxRotation: 0
                },
                grid: {
                  display: false
                },
                barThickness: 50,           // Width of each individual bar (increase this)
                maxBarThickness: 50,        // Optional: limits the max width
                categoryPercentage: 1.0,    // Use full category width
                barPercentage: 1.0,
              },
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Amount (USD)',
                  font: {
                    size: 14
                  }
                },
                grid: {
                  color: 'rgba(0,0,0,0.05)'
                }
              }
            }
          }
        });
      });
    </script>
    <script>
      //-------------
    //- BAR CHART -
    //-------------
        document.addEventListener('DOMContentLoaded', function () {
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = {
            labels: @json($yearlyLabels),
            datasets: [
                {
                    label: '{{__("message.This Year Income")}}',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: @json($thisYearIncomeData)
                },
                {
                    label: '{{__("message.Last Year Income")}}',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: @json($yearBeforeIncomeData)
                }
            ]
        };

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });
    });
    </script>
    <script>
        //-------------
  // - PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = {
        labels: [
            '{{ \App\Enums\PaymentStatus::PAID->label() }}',
            '{{ \App\Enums\PaymentStatus::UNPAID->label() }}',
        ],
        datasets: [
            {
                data: [@json($sumPaidPayments), @json($sumUnpaidPayments)],
                backgroundColor: ['#00a65a', '#f56954'] // success (paid), danger (unpaid)
            }
        ]
    }
    var pieOptions = {
    legend: {
      display: false
    }
  }
  // Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  // eslint-disable-next-line no-unused-vars
  var pieChart = new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: pieData,
        options: pieOptions
  })
    </script>
  @endpush
@endsection