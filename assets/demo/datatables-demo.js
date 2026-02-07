// DataTables Configuration - Cleaned and Optimized
$(document).ready(function () {
  $("#dataTable").DataTable({
    language: {
      lengthMenu: "Show _MENU_ entries",
      search: "Search:",
      paginate: {
        first: "First",
        last: "Last",
        next: "Next",
        previous: "Previous"
      },
      info: "Showing _START_ to _END_ of _TOTAL_ entries",
      emptyTable: "No data available in table",
      infoFiltered: "(filtered from _MAX_ total entries)"
    },
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"]
    ],
    pageLength: 10,
    responsive: true,
    dom: "lfrtip",
    initComplete: function () {
      // Ensure the select shows the correct value
      setTimeout(function () {
        $('select[name="dataTable_length"]').val(10);
      }, 100);
    }
  });
});
