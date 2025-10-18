document.addEventListener('DOMContentLoaded', function () {

  // ************************************
  //       date picker by month and year
  // ************************************

    const filterModal = document.getElementById('filterModal');
    const monthSelect = document.getElementById('month');
    const yearSelect = document.getElementById('year');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const filterForm = document.getElementById('filterForm');
    const clearBtn = document.getElementById('clearBtn');
    const filterBtn = document.getElementById('filterBtn');

  
    filterModal.addEventListener('shown.bs.modal', function () {
        monthSelect.value = '';
        yearSelect.value = '';
        dateFrom.value = '';
        dateTo.value = '';
    });


    clearBtn.addEventListener('click', function () {
        monthSelect.value = '';
        yearSelect.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        filterForm.submit();
    });


    filterBtn.addEventListener('click', function () {
        filterForm.submit();
    });

    
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    months.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index + 1;
        option.textContent = month;
        monthSelect.appendChild(option);
    });

    
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 10; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // ************************************
   //       date range picker custom range
  // ************************************
    $(function () {

      $('#datepicker').daterangepicker({
        opens: 'left',
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
          format: 'YYYY-MM-DD'
        }
      }, function (start, end, label) {

        $('#dateFrom').val(start.format('YYYY-MM-DD'));
        $('#dateTo').val(end.format('YYYY-MM-DD'));
      });


      $('#dateFrom').val(moment().startOf('month').format('YYYY-MM-DD'));
      $('#dateTo').val(moment().endOf('month').format('YYYY-MM-DD'));

      // if dateFrom and To are empty 
      $('#filterForm').submit(function (e) {

        if (!$('#dateFrom').val() || !$('#dateTo').val()) {

          $('#dateFrom').val(moment().startOf('month').format('YYYY-MM-DD'));
          $('#dateTo').val(moment().endOf('month').format('YYYY-MM-DD'));
        }
      });
    });
});

