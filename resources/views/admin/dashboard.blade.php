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
      <x-dashboard-cards :totalIncome="$totalIncome" :currentMonth="$currentMonth" :totalOutcome="$totalOutcome" :profit="$profit" :totalStudents="$totalStudents" :totalOutdatedPayments="$totalOutdatedPayments" />
      </div><!-- end dashboard cards -->
      <div class="d-flex flex-column flex-lg-row gap-5">
        <!-- chart data -->
        <div style="position: relative; height:500px; margin-bottom: 20px">
          <h3 class="text-center mb-4">{{__('message.Income, Outcome, and Profit for')}} {{$currentMonth}}</h3>
          <canvas id="lineChart" class="w-100"></canvas>
        </div><!-- end chart data -->
        <!-- Upcoming Payments -->
        <div class="card flex-grow-1 p-2 mt-4 mt-lg-0" style="min-width: 420px; max-width: 520px; height: fit-content;">
        <div class="card-header bg-green text-white text-center">
            <h3 class="text-white">{{__('message.Upcoming Payments') }}</h3>
         </div>
          @forelse ($upcomigPayments as $income)
            @foreach ($income->payments as $payment)
              <div class="d-flex justify-content-between align-items-center gap-3 my-2 p-3 bg-success-subtle rounded shadow-sm">
                <div class="d-flex align-items-center">
                  <i class="fa fa-user text-success me-2"></i>
                  <div>
                    <strong>{{ $income->client->full_name }}</strong><br>
                    <small class="text-muted">
                      {{ $income->client->client_phone ?? __('No phone') }}
                    </small>
                  </div>
                </div>
                <div class="text-end">
                  <i class="fa fa-dollar-sign text-success me-1"></i>
                  <strong>${{ number_format($payment->payment_amount) }}</strong><br>
                  <small class="text-muted">
                    {{ date('M d, Y', strtotime($payment->next_payment ?? $income->next_payment)) }}
                  </small>
                </div>
              </div>
            @endforeach
          @empty
            <p class="text-muted p-2">{{ __('message.No upcoming payments') }}</p>
          @endforelse
        </div><!-- end Upcoming Payments -->

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
                display: true,
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

  @endpush
@endsection