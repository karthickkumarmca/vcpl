$(function() {

  $('[data-toggle="tooltip"]').tooltip();
    
  $('#singleDate').daterangepicker({
    //autoUpdateInput: false,
    "locale": {
      "format": "DD/MM/YYYY",
    },
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    defaultDate: null,
    maxYear: parseInt(moment().format('YYYY'),10),
    drops: "up",
    //maxDate: moment()
  });

  $('#singleDate2').daterangepicker({
    //autoUpdateInput: false,
    "locale": {
      "format": "DD/MM/YYYY",
    },
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    maxYear: parseInt(moment().format('YYYY'),40),
    drops: "up",
    //maxDate: moment()
  });

  var timezone = moment.tz.guess();
  var start = moment().subtract(29, 'days');
  var end = moment();

  $("#timezone").val(timezone);
  $("#timezone2").val(timezone);

  function cb(start, end) {
    $('#customDate span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#start_date').val(start.format('YYYY-MM-DD'));
    $('#end_date').val(end.format('YYYY-MM-DD'));
  }

  function cbc(start, end) {
    $('#customDate2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#start_date2').val(start.format('YYYY-MM-DD'));
    $('#end_date2').val(end.format('YYYY-MM-DD'));
  }

  $('#customDate').daterangepicker({
    startDate: start,
    endDate: end,
    drops: "up",
    maxDate: moment(),
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  }, cb);

  $('#customDate2').daterangepicker({
    startDate: start,
    endDate: end,
    drops: "up",
    maxDate: moment(),
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  }, cbc);

  cb(start, end);
  cbc(start, end);

  $("input[name=report]").on("click", function () {
    if ($(this).val() === "single") {
      $("#singleDate").parent().removeClass("d-none");
      $("#customDate").parent().addClass("d-none");
      $("#singleDate").prop("disabled", false);
      $("#start_date").prop("disabled", true);              
      $("#end_date").prop("disabled", true);              
    } else {
        $("#customDate").parent().removeClass("d-none");
        $("#singleDate").parent().addClass("d-none");
        $("#start_date").prop("disabled", false);              
        $("#end_date").prop("disabled", false);
        $("#singleDate").prop("disabled", true);
    }
  });

  $("input[name=report2]").on("click", function () {
    if ($(this).val() === "single2") {
      $("#singleDate2").parent().removeClass("d-none");
      $("#customDate2").parent().addClass("d-none");
      $("#singleDate2").prop("disabled", false);
      $("#start_date2").prop("disabled", true);
      $("#end_date2").prop("disabled", true);
    } else {
      $("#customDate2").parent().removeClass("d-none");
      $("#singleDate2").parent().addClass("d-none");
      $("#start_date2").prop("disabled", false);
      $("#end_date2").prop("disabled", false);
      $("#singleDate2").prop("disabled", true);
    }
  });
    
});
