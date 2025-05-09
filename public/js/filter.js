document.addEventListener('DOMContentLoaded', function () {
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
});

