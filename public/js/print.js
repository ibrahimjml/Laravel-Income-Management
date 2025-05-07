document.getElementById('print-report').addEventListener('click', function () {
  window.print();
});
$(document).ready(function () {
  $('#filterModal').on('shown.bs.modal', function () {
      $('#month').val('');
      $('#year').val('');
      $('#dateFrom').val('');
      $('#dateTo').val('');
  });

  $('#clearBtn').click(function () {
      $('#month').val('');
      $('#year').val('');
      $('#dateFrom').val('');
      $('#dateTo').val('');
      $('#filterForm').submit();
  });

  $('#filterBtn').click(function () {
      $('#filterForm').submit();
  });

  const months = [
      'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const years = [];
  const currentYear = new Date().getFullYear();
  for (let year = currentYear; year >= currentYear - 10; year--) {
      years.push(year);
  }

  months.forEach((month, index) => {
      $('#month').append(`<option value="${index + 1}">${month}</option>`);
  });

  years.forEach(year => {
      $('#year').append(`<option value="${year}">${year}</option>`);
  });
});