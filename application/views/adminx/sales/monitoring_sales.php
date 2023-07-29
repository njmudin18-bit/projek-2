<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $nama_halaman; ?> | <?php echo APPS_NAME; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="<?php echo APPS_DESC; ?>" />
  <meta name="keywords" content="<?php echo APPS_KEYWORD; ?>" />
  <meta name="author" content="<?php echo APPS_AUTHOR; ?>" />
  <meta http-equiv="refresh" content="<?php echo APPS_REFRESH; ?>">

  <?php $this->load->view('adminx/components/header_css_datatable'); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/loading.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/daterangepicker.css" />
</head>

<body>

  <div class="loader-bg">
    <div class="loader-bar"></div>
  </div>

  <div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

      <?php $this->load->view('adminx/components/navbar'); ?>

      <?php $this->load->view('adminx/components/navbar_chat'); ?>

      <div class="pcoded-main-container">
        <div class="pcoded-wrapper">

          <?php $this->load->view('adminx/components/sidebar'); ?>

          <div class="pcoded-content">

            <?php $this->load->view('adminx/components/breadcrumb'); ?>

            <div class="pcoded-inner-content">
              <div class="main-body">
                <div class="page-wrapper">
                  <div class="page-body">
                    <div class="row">
                      <div class="col-sm-12">

                        <div class="card">
                          <div class="card-header text-center">
                            <h5>
                              <?php echo strtoupper($nama_halaman); ?>
                            </h5>
                          </div>
                          <div class="card-block">
                            <div class="dt-responsive table-responsive">
                              <!-- <div class="form-group row">
                                <label class="col-md-2 col-sm-12 col-form-label m-t-30">Filter data by</label>
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <select name="tanggal" id="tanggal" class="form-control">
                                    <option value="All">All</option>
                                    <option disabled="disabled">-- Pilih --</option>
                                    <?php
                                    $now = date('d');
                                    for ($day = 1; $day <= 31; $day++) {
                                      if ($day <= 9) {
                                        if ($day == $now) {
                                          echo "<option selected value = '0" . $day . "'>0" . $day . "</option>";
                                        } else {
                                          echo "<option value = '0" . $day . "'>0" . $day . "</option>";
                                        }
                                      } else {
                                        if ($day == $now) {
                                          echo "<option selected value = '" . $day . "'>" . $day . "</option>";
                                        } else {
                                          echo "<option value = '" . $day . "'>" . $day . "</option>";
                                        }
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <select class="form-control" name="bulan" id="bulan" required="required">
                                    <option value="All">All</option>
                                    <option disabled="disabled">-- Bulan --</option>
                                    <?php
                                    $now  = new DateTime('now');
                                    $bln1 = $now->format('m');
                                    for ($m = 1; $m <= 12; ++$m) {
                                      if ($bln1 == $m) {
                                        echo '<option selected value=' . $m . '>' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>' . "\n";
                                      } else {
                                        echo '<option  value=' . $m . '>' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>' . "\n";
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <select class="form-control" name="tahun" id="tahun" required="required">
                                    <option disabled="disabled">-- Tahun --</option>
                                    <?php
                                    $now    = new DateTime('now');
                                    $year1  = $now->format('Y');
                                    for ($year = 2022; $year <= 2050; ++$year) {
                                      if ($year1 == $year) {
                                        echo '<option selected value=' . $year . '>' . $year . '</option>' . "\n";
                                      } else {
                                        echo '<option  value=' . $year . '>' . $year . '</option>' . "\n";
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>
                                <div class="col-md-3 col-sm-12 m-t-30">
                                  <button id="btnCari" type="button" class="btn btn-info btn-full-mobile" onclick="cari();">TAMPILKAN</button>
                                </div>
                              </div> -->
                              <div class="form-group row">
                                <label class="col-md-2 col-sm-12 col-form-label m-t-30">Filter date</label>
                                <div class="col-md-4 col-sm-12 m-t-30">
                                  <div class="input-group">
                                    <input type="text" class="form-control" name="tanggal" id="tanggal">
                                    <span class="input-group-append">
                                      <label class="input-group-text"><i class="icofont icofont-calendar"></i></label>
                                    </span>
                                  </div>

                                  <input type="hidden" name="start_date" id="start_date">
                                  <input type="hidden" name="end_date" id="end_date">
                                </div>
                                <div class="col-md-3 col-sm-12 m-t-30">
                                  <button id="btnCari" type="button" class="btn btn-info btn-full-mobile" onclick="cari();">TAMPILKAN</button>
                                </div>
                              </div>
                              <hr>
                              <table id="order-table" class="table table-striped table-bordered nowrap" width="100%" border="1" cellpadding="0" cellspacing="0">
                                <thead class="bg-primary">
                                  <tr>
                                    <th class="text-center">NO.</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">NO. DO</th>
                                    <th class="text-center">NO. PO</th>
                                    <th class="text-center">QTY. ORDER</th>
                                    <th class="text-center">CUSTOMER</th>
                                    <th class="text-center">PART ID</th>
                                    <th class="text-center">PART NAME</th>
                                    <th class="text-center">DRIVER + MOBIL</th>
                                    <th class="text-center">SCAN ON WAREHOUSE</th>
                                    <th class="text-center">SCAN ON DELIVERY</th>
                                  </tr>
                                </thead>
                                <tbody>

                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="styleSelector"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="loading-screen" class="loading">Loading&#8230;</div>

  <!-- Modal -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table id="modal-table"></table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="<?php echo base_url(); ?>files/bower_components/jquery/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
  <?php $this->load->view('adminx/components/bottom_js_datatable'); ?>
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/daterangepicker.min.js"></script>
  <script type="text/javascript">
    $(function() {

      var start = moment().subtract(7, 'days');
      var end = moment();

      function cb(start, end) {
        var sd = start.format('YYYY-MM-DD');
        var ed = end.format('YYYY-MM-DD');

        $('#tanggal').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
      }

      $('#tanggal').daterangepicker({
        startDate: start,
        endDate: end,
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
        },
        function(start, end, label) {
          startDate = start;
          endDate = end;
          console.log(startDate);
          console.log(endDate);
          console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
      }, cb);
      cb(start, end);
    });
  </script>
  <script>
    function lihat_details(no_po, no_do, part_id) {
      $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "<?php echo base_url(); ?>sales/sales_detail_listing",
        data: {
          no_po: no_po,
          no_do: no_do,
          part_id: part_id
        },
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(response) {
          if (response.status_code == 200) {
            $('#modal').modal('show');
            $("#loading-screen").hide();
            table = $('#modal-table').DataTable({
              dom: 'Bfrltip',
              buttons: [
                'excel'
                //'copy', 'csv', 'excel', 'pdf', 'print'
              ],
              "pagingType": "full_numbers",
              "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
              ],
              responsive: true,
              language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records",
              },
              'processing': true,
              'serverSide': false,
              'serverMethod': 'post',
              'ajax': {
                url: "<?php echo base_url(); ?>sales/monitoring_sales_list_new",
                type: 'POST',
                "data": function(data) {
                  data.bulan = $('#bulan').val();
                  data.tahun = $('#tahun').val();
                  data.tanggal = $('#tanggal').val();
                }
              },

              'aoColumns': [{
                  "NO": "NO",
                  "sClass": "text-right"
                },
                {
                  "STATUS": "STATUS",
                  "sClass": "text-center"
                },
                {
                  "NO. DO": "NO. DO",
                  "sClass": "text-left"
                },
                {
                  "NO. PO": "NO. PO",
                  "sClass": "text-left"
                },
                {
                  "QTY. ORDER": "QTY. ORDER",
                  "sClass": "text-right"
                },
                {
                  "CUSTOMER": "CUSTOMER",
                  "sClass": "text-left"
                },
                {
                  "PART ID": "PART ID",
                  "sClass": "text-left"
                },
                {
                  "PART NAME": "PART NAME",
                  "sClass": "text-left"
                },
                {
                  "DRIVER + MOBIL": "DRIVER + MOBIL",
                  "sClass": "text-left"
                },
                {
                  "SCAN ON": "SCAN ON",
                  "sClass": "text-center"
                }
              ],

              "columnDefs": [{
                //"targets": [ 1 ], //last column
                "orderable": false, //set not orderable
                className: 'text-right'
              }, ]
            });
          } else {

          }
          console.log(response);
          console.log(response.data);
        },
        error: function(err) {
          $("#loading-screen").hide();
        }
      });
    }

    function cari() {
      reload_table();
    }

    //FUNCTION RELOAD TABLE
    function reload_table() {
      table.ajax.reload(null, false);
    }

    $(document).ready(function() {
      $("#loading-screen").hide();
      table = $('#order-table').DataTable({
        dom: 'Bfrltip',
        buttons: [
          'excel'
          //'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search records",
        },
        'processing': true,
        'serverSide': false,
        'serverMethod': 'post',
        'ajax': {
          url: "<?php echo base_url(); ?>sales/monitoring_sales_list_new",
          type: 'POST',
          "data": function(data) {
            data.start_date = $('#start_date').val();
            data.end_date = $('#end_date').val();
            // data.bulan = $('#bulan').val();
            // data.tahun = $('#tahun').val();
            // data.tanggal = $('#tanggal').val();
          }
        },

        'aoColumns': [{
            "NO": "NO",
            "sClass": "text-right"
          },
          {
            "STATUS": "STATUS",
            "sClass": "text-center"
          },
          {
            "NO. DO": "NO. DO",
            "sClass": "text-left"
          },
          {
            "NO. PO": "NO. PO",
            "sClass": "text-left"
          },
          {
            "QTY. ORDER": "QTY. ORDER",
            "sClass": "text-right"
          },
          {
            "CUSTOMER": "CUSTOMER",
            "sClass": "text-left"
          },
          {
            "PART ID": "PART ID",
            "sClass": "text-left"
          },
          {
            "PART NAME": "PART NAME",
            "sClass": "text-left"
          },
          {
            "DRIVER + MOBIL": "DRIVER + MOBIL",
            "sClass": "text-left"
          },
          {
            "SCAN ON": "SCAN ON WAREHOUSE",
            "sClass": "text-left"
          },
          {
            "SCAN ON DELIVERY": "SCAN ON DELIVERY",
            "sClass": "text-left"
          }
        ],

        "columnDefs": [{
          //"targets": [ 1 ], //last column
          "orderable": false, //set not orderable
          className: 'text-right'
        }, ]
      });
    });
  </script>
</body>

</html>