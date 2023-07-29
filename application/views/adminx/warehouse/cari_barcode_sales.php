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

  <?php $this->load->view('adminx/components/header_css_datatable_fix_column'); ?>
  <?php //$this->load->view('adminx/components/header_css_datatable'); 
  ?>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/loading.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/sweetalert2.min.css">
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
                          <div class="card-block m-t-30 m-b-30">
                            <div class="text-center" style="background: #eee;">
                              <video id="previewKamera" style="width: 300px;height: 300px;"></video>
                              <br>
                              <div class="form-group row justify-content-center">
                                <select id="pilihKamera" class="form-control" style="width: 40%;">
                                </select>
                              </div>
                            </div>
                            <hr>
                            <form id="scanForm">
                              <div class="form-group row justify-content-center"><!-- EX05900060SA0001, EX04000060SA0002 -->
                                <input type="search" id="code_barcode" name="code_barcode" class="form-control form-control-round form-control-uppercase text-center form-control-lg form-txt-danger form-control-danger form-search" autofocus="on" autocomplete="off" placeholder="SCAN BARCODE DISINI">
                              </div>
                            </form>
                            <hr class="m-t-50 m-b-50">
                            <div class="dt-responsive table-responsiveXX">
                              <div class="form-group row">
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
                              </div>
                              <hr>
                              <table id="order-table" class="table table-striped table-bordered nowrap" width="100%" border="1" cellpadding="0" cellspacing="0">
                                <thead>
                                  <tr class="bg-primary">
                                    <th class="text-center">NO.</th>
                                    <th class="text-center">#</th>
                                    <th class="text-center">NO. DO</th>
                                    <th class="text-center">PO CUSTOMER</th>
                                    <th class="text-center">BARCODE ID</th>
                                    <th class="text-center">LOKASI SCAN</th>
                                    <th class="text-center">APPROVED BY</th>
                                    <th class="text-center">DIVISI</th>
                                    <th class="text-center">TGL. SCAN</th>
                                    <th class="text-center">JLH. BOX</th>
                                    <th class="text-center">QTY. BOX</th>
                                    <th class="text-center">QTY. ORDER</th>
                                    <th class="text-center">PART ID</th>
                                    <th class="text-center">PART NAME</th>
                                    <th class="text-center">CUSTOMER</th>
                                    <th class="text-center">DRIVER + MOBIL</th>
                                    <th class="text-center">TGL. KIRIM ULANG</th>
                                    <th class="text-center">NOTE KIRIM ULANG</th>
                                    <th class="text-center">KIRIM ULANG BY</th>
                                  </tr>
                                </thead>
                                <tbody id="body_barcode_sales"></tbody>
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

  <!-- Modal -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambahkan Driver dan Mobil</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="RegisterValidation">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Nama Driver</label>
              <div class="col-sm-10">
                <!-- <input type="text" id="nama_driver" name="nama_driver" class="form-control text-capitalize" required="required"> -->
                <select id="nama_driver" name="nama_driver" class="form-control">
                  <option selected="selected" disabled="disabled">-- Pilih --</option>
                  <option value="MUSTAKIM">MUSTAKIM</option>
                  <option value="BATARA">BATARA</option>
                  <option value="UDIN">UDIN</option>
                  <option value="ANWAR">ANWAR</option>
                  <option value="INDRA">INDRA</option>
                </select>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">No. Polisi</label>
              <div class="col-sm-10">
                <!-- <input type="text" id="no_polisi" name="no_polisi" class="form-control text-uppercase" required="required"> -->
                <select id="no_polisi" name="no_polisi" class="form-control">
                  <option selected="selected" disabled="disabled">-- Pilih --</option>
                  <option value="A 8552 ZT">A 8552 ZT (Engkel)</option>
                  <option value="A 9372 ZA">A 9372 ZA (Double)</option>
                  <option value="A 9403 ZX">A 9403 ZX (Double)</option>
                  <option value="A 8762 YX">A 8762 YX (Engkel)</option>
                </select>
                <span class="help-block"></span>
              </div>
            </div>
            <hr>
            <div class="form-group row">
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">No. DO :</label>
                <input type="text" class="form-control" name="no_do" id="no_do" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">No. PO :</label>
                <input type="text" class="form-control" name="no_po" id="no_po" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">Customer :</label>
                <input type="text" class="form-control" name="nm_customer" id="nm_customer" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">QR Code :</label>
                <input type="text" class="form-control" name="no_barcode" id="no_barcode" readonly="readonly">
                <input type="hidden" name="part_no" id="part_no">
                <input type="hidden" name="qty_order" id="qty_order">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
          <button id="btnSave" type="button" onclick="update_status();" class="btn btn-primary waves-effect waves-light ">Approved</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal keterangan -->
  <div class="modal fade" id="modal_keterangan" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambahkan Keterangan</h4>
          <button type="button" onclick="reset_keterangan()" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="resend_form">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Keterangan perubahan</label>
              <div class="col-sm-10">
                <input type="text" name="keterangan_kirim_ulang" id="keterangan_kirim_ulang" class="form-control text-capitalize">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Tanggal perubahan</label>
              <div class="col-sm-10">
                <input type="text" id="tanggal_kirim_ulang" name="tanggal_kirim_ulang" class="form-control">
              </div>
            </div>
            <hr>
            <div class="form-group row">
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">No. DO :</label>
                <input type="text" class="form-control" name="no_do_ket" id="no_do_ket" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">No. PO :</label>
                <input type="text" class="form-control" name="no_po_ket" id="no_po_ket" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">Customer :</label>
                <input type="text" class="form-control" name="nm_customer_ket" id="nm_customer_ket" readonly="readonly">
              </div>
              <div class="col-md-6 col-sm-12">
                <label class="col-form-label">QR Code :</label>
                <input type="text" class="form-control" name="no_barcode_ket" id="no_barcode_ket" readonly="readonly">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="reset_keterangan()" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
          <button id="btnSave" type="button" onclick="update_keterangan();" class="btn btn-primary waves-effect waves-light ">Tambahkan</button>
        </div>
      </div>
    </div>
  </div>

  <div id="loading-screen" class="loading">Loading&#8230;</div>

  <?php $this->load->view('adminx/components/bottom_js_datatable_fix_column'); ?>
  <?php //$this->load->view('adminx/components/bottom_js_datatable'); 
  ?>
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script> -->

  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/index.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/daterangepicker.min.js"></script>
  <script type="text/javascript">
    let selectedDeviceId = null;
    const codeReader = new ZXing.BrowserMultiFormatReader();
    const sourceSelect = $("#pilihKamera");

    $(document).on('change', '#pilihKamera', function() {
      selectedDeviceId = $(this).val();
      if (codeReader) {
        codeReader.reset()
        initScanner()
      }
    })

    function initScanner() {
      codeReader
        .listVideoInputDevices()
        .then(videoInputDevices => {
          videoInputDevices.forEach(device =>
            console.log(`${device.label}, ${device.deviceId}`)
          );

          if (videoInputDevices.length > 0) {

            if (selectedDeviceId == null) {
              if (videoInputDevices.length > 1) {
                selectedDeviceId = videoInputDevices[1].deviceId
              } else {
                selectedDeviceId = videoInputDevices[0].deviceId
              }
            }


            if (videoInputDevices.length >= 1) {
              sourceSelect.html('');
              videoInputDevices.forEach((element) => {
                const sourceOption = document.createElement('option')
                sourceOption.text = element.label
                sourceOption.value = element.deviceId
                if (element.deviceId == selectedDeviceId) {
                  sourceOption.selected = 'selected';
                }
                sourceSelect.append(sourceOption)
              })
            }

            codeReader
              .decodeOnceFromVideoDevice(selectedDeviceId, 'previewKamera')
              .then(result => {

                //hasil scan
                console.log(result.text)
                $("#code_barcode").val(result.text);
                $('#scanForm').submit();
                if (codeReader) {
                  //codeReader.reset();
                  initScanner()
                }
              })
              .catch(err => console.error(err));
          } else {
            alert("Camera not found!")
          }
        })
        .catch(err => console.error(err));
    }

    if (navigator.mediaDevices) {
      initScanner()
    } else {
      alert('Cannot access camera.');
    }
  </script>
  <script type="text/javascript">
    $(function() {
      $("#scanForm").submit(function() {

        $.ajax({
          url: "<?php echo base_url(); ?>warehouse/cari_barcode",
          data: $('#scanForm').serialize(),
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {

            if (hasil.status_code == 200) {
              $("#loading-screen").hide();
              console.log(hasil);
              var no_do = hasil.data[0].nodo;
              var no_po = hasil.data[0].pocustomer;
              var no_barcode = hasil.data[0].barcodeid;
              //var nm_customer = hasil.customer.PartnerName;
              var nm_customer = hasil.data[0].customer;
              var part_no = hasil.data[0].partid;
              var qty_order = parseFloat(hasil.data[0].qtyorder);
              console.log(qty_order);

              open_modal_driver(no_do, no_po, no_barcode, nm_customer, part_no, qty_order);
            } else {
              Swal.fire(
                'Oops',
                hasil.message,
                'warning'
              );
              $("#loading-screen").hide();
            }
          }
        })
        return false;
      });
    });

    //OPEN MODAL DRIVER
    function open_modal_driver(no_do, no_po, no_barcode, nm_customer, part_no, qty_order) {
      $('#no_do').val(no_do);
      $('#no_po').val(no_po);
      $('#no_barcode').val(no_barcode);
      $('#nm_customer').val(nm_customer);
      $('#part_no').val(part_no);
      $('#qty_order').val(qty_order);
      $('#modal').modal('show');
    }

    //DATERANGE PICKER
    $(function() {
      var min = moment().subtract(7, 'days');
      var max = moment();

      $('#tanggal_kirim_ulang').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: min,
        maxDate: max,
        minYear: parseInt(moment().format('YYYY'), 10),
        maxYear: parseInt(moment().format('YYYY'), 10)
      }, function(start, end, label) {
        console.log(start);
        console.log(end);
        console.log(label);
        //var years = moment().diff(start, 'years');
        //$('#start_date').val(start.format('YYYY-MM-DD'));
        //alert("You are " + years + " years old!");
      });
    });

    //TAMBAHKAN KETERANGAN UNTUK PENGIRIMAN ULANG
    function tambah_keterangan(barcode_id, no_do, no_po, nama_customer) {
      $('#no_do_ket').val(no_do);
      $('#no_po_ket').val(no_po);
      $('#no_barcode_ket').val(barcode_id);
      $('#nm_customer_ket').val(nama_customer);

      $('#modal_keterangan').modal('show');
    }

    //KOSONGKAN FORM KETERANGAN
    function reset_keterangan() {
      $('#keterangan_kirim_ulang').val('');
    }

    //UPDATE TABLE
    function update_keterangan() {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>warehouse/tambah_keterangan_kirim_ulang",
        data: $('#resend_form').serialize(),
        dataType: "JSON",
        beforeSend: function(res) {
          $("#loading-screen").show();
        },
        success: function(response) {
          $("#loading-screen").hide();
          if (response.status_code == 200) {
            Swal.fire(
              response.status.toUpperCase(),
              response.message,
              'warning'
            );
            $('#modal_keterangan').modal('hide');
          } else {
            Swal.fire(
              response.status.toUpperCase(),
              response.message,
              'warning'
            );
          }
          reload_table();
        },
        error: function(error) {
          $("#loading-screen").hide();
          alert('Oops something went wrong');
        }
      });
    }

    //APPROVED BARCODE
    function update_status() {

      var nama_driver = $('#nama_driver').val();
      var no_polisi = $('#no_polisi').val();
      console.log(nama_driver);
      console.log(no_polisi);
      if (nama_driver == '' || nama_driver == null) {
        alert("Nama driver harus diisi");
        $("#nama_driver").focus();
      } else if (no_polisi == '' || no_polisi == null) {
        alert("Nomor polisi harus diisi");
        $("#no_polisi").focus();
      } else {

        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Data yang sudah di approved tidak bisa dirubah",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Approved!',
          cancelButtonText: 'Tidak, jangan'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "<?php echo base_url(); ?>warehouse/approved_status",
              type: "POST",
              data: $('#RegisterValidation').serialize(),
              dataType: "JSON",
              beforeSend: function() {
                $("#loading-screen").show();
              },
              success: function(data) {
                $("#loading-screen").hide();
                if (data.status_code == 200 || data.status == 'success') {
                  Swal.fire(
                    data.status.toUpperCase(),
                    data.message,
                    'success'
                  )
                  location.reload();
                } else {
                  Swal.fire(
                    'Oops!',
                    data.message,
                    'info'
                  )
                  $('#modal').modal('hide');
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                $("#loading-screen").hide();
              }
            });
          } else {
            console.log("aaa");
          }
        })
      }
    }

    //FUNCTION CARI BERDASARKAN TANGGAL
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
        scrollY: "100%",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        fixedColumns: {
          leftColumns: 2,
          rightColumns: 0
        },
        'processing': true,
        'serverSide': false,
        'serverMethod': 'post',
        'ajax': {
          url: "<?php echo base_url(); ?>warehouse/produk_terkirim_list",
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
            "#": "#",
            "sClass": "text-center"
          },
          {
            "BARCODE ID": "BARCODE ID",
            "sClass": "text-center"
          },
          {
            "NO. DO": "NO. DO",
            "sClass": "text-left"
          },
          {
            "PO CUSTOMER": "PO CUSTOMER",
            "sClass": "text-left"
          },
          {
            "LOKASI SCAN": "LOKASI SCAN",
            "sClass": "text-center"
          },
          {
            "APPROVED BY": "APPROVED BY",
            "sClass": "text-left"
          },
          {
            "DIVISI": "DIVISI",
            "sClass": "text-left"
          },
          {
            "TGL. SCAN": "TGL. SCAN",
            "sClass": "text-center"
          },
          {
            "JLH. BOX": "JLH. BOX",
            "sClass": "text-center"
          },
          {
            "QTY. BOX": "QTY. BOX",
            "sClass": "text-right"
          },
          {
            "QTY. ORDER": "QTY. ORDER",
            "sClass": "text-right"
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
            "CUSTOMER": "CUSTOMER",
            "sClass": "text-left"
          },
          {
            "DRIVER + MOBIL": "DRIVER + MOBIL",
            "sClass": "text-left"
          },
          {
            "TGL. KIRIM ULANG": "TGL. KIRIM ULANG",
            "sClass": "text-left"
          },
          {
            "NOTE KIRIM ULANG": "NOTE KIRIM ULANG",
            "sClass": "text-left"
          },
          {
            "KIRIM ULANG BY": "KIRIM ULANG BY",
            "sClass": "text-left"
          }
        ],

        "columnDefs": [{
          "targets": [0], //last column
          "orderable": false, //set not orderable
          className: 'text-right'
        }, ]
      });
    });
  </script>
</body>

</html>