function sortTable(columnIndex, headerElement) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
  table = document.getElementById("sortableTable");
  switching = true;
  dir = "asc"; 

  resetArrows();

  headerElement.querySelector(".arrow").classList.add("asc");

  while (switching) {
      switching = false;
      rows = table.rows;

      for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[columnIndex];
          y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

          if (dir == "asc") {
              if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                  shouldSwitch = true;
                  break;
              }
          } else if (dir == "desc") {
              if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                  shouldSwitch = true;
                  break;
              }
          }
      }

      if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchCount++;
      } else {
          if (switchCount == 0 && dir == "asc") {
              dir = "desc";
              headerElement.querySelector(".arrow").classList.remove("asc");
              headerElement.querySelector(".arrow").classList.add("desc");
              switching = true;
          }
      }
  }
}
function sortTable1(columnIndex, headerElement) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
  table = document.getElementById("sortableTable1");
  switching = true;
  dir = "asc"; 

  resetArrows();

  headerElement.querySelector(".arrow").classList.add("asc");

  while (switching) {
      switching = false;
      rows = table.rows;

      for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[columnIndex];
          y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

          // Check if two rows should switch place
          if (dir == "asc") {
              if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                  shouldSwitch = true;
                  break;
              }
          } else if (dir == "desc") {
              if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                  shouldSwitch = true;
                  break;
              }
          }
      }

      if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchCount++;
      } else {
          if (switchCount == 0 && dir == "asc") {
              dir = "desc";
              headerElement.querySelector(".arrow").classList.remove("asc");
              headerElement.querySelector(".arrow").classList.add("desc");
              switching = true;
          }
      }
  }
}
$(document).ready(function () {
  $('#search-input').on('keyup', function () {
      var value = $(this).val().toLowerCase();
      $('#sortableTable tbody tr').filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
  });

  function sortTable(n, th) {
      var table = $(th).closest('table');
      var rows = table.find('tbody tr').get();

      rows.sort(function (a, b) {
          var A = $(a).children('td').eq(n).text().toUpperCase();
          var B = $(b).children('td').eq(n).text().toUpperCase();

          if (A < B) {
              return -1;
          }
          if (A > B) {
              return 1;
          }
          return 0;
      });

      $.each(rows, function (index, row) {
          table.children('tbody').append(row);
      });

      table.find('th .arrow').remove();
      $(th).append('<span class="arrow">&#9650;</span>'); // Example arrow up
  }
});
$(document).ready(function () {
  $('#search-input1').on('keyup', function () {
      var value = $(this).val().toLowerCase();
      $('#sortableTable1 tbody tr').filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
  });

  function sortTable(n, th) {
      var table = $(th).closest('table');
      var rows = table.find('tbody tr').get();

      rows.sort(function (a, b) {
          var A = $(a).children('td').eq(n).text().toUpperCase();
          var B = $(b).children('td').eq(n).text().toUpperCase();

          if (A < B) {
              return -1;
          }
          if (A > B) {
              return 1;
          }
          return 0;
      });

      $.each(rows, function (index, row) {
          table.children('tbody').append(row);
      });

      table.find('th .arrow').remove();
      $(th).append('<span class="arrow">&#9650;</span>'); // Example arrow up
  }
});
function resetArrows() {
  var arrows = document.querySelectorAll(".arrow");
  arrows.forEach(function (arrow) {
      arrow.classList.remove("asc", "desc");
  });
}