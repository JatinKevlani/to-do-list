$(document).ready(function () {
    $(".data-table").each(function (_, table) {
      $(table).DataTable();
    });
  });
  
  // Your existing jQuery and DataTables script
  $(document).ready(function () {
    let table = $(".data-table").DataTable();
    table.rows().every(function () {
      // For each row, set the background color of the row to red
      this.node().style.backgroundColor = 'red';
    });
  });