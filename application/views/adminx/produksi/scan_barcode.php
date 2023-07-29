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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/loading.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/sweetalert2.min.css">
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
                              <?php echo strtoupper($nama_halaman); ?> PRODUKSI
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
                              <div class="form-group row justify-content-center"><!-- value="|PCG/JOB/43/202302/00053-003|003|-|0|WH-GRS00|5200|400|-|" -->
                                <input type="search" id="barcode_no" name="barcode_no" class="form-control form-control-round form-control-uppercase text-center form-control-lg form-txt-danger form-control-danger form-search" autofocus="on" autocomplete="off" placeholder="SCAN BARCODE DISINI" readonly="readonly">
                              </div>
                            </form>
                            <hr class="m-t-50 m-b-50">
                            <div class="dt-responsive table-responsive">
                              <h5 class="text-center">HASIL SCAN PRODUKSI</h5>
                              <hr class="m-t-20 m-b-20">
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
                                    <!-- <option value="All">All</option> -->
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
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <select class="form-control" name="jenis_part" id="jenis_part" required="required">
                                    <option disabled="disabled">-- Pilih --</option>
                                    <option value="WH-FG" selected="selected">WH-FG</option>
                                    <option value="WH-FG01">WH-FG01</option>
                                    <option value="WH-MDN">WH-MDN</option>
                                  </select>
                                </div>
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <button id="btnCari" type="button" class="btn btn-info btn-full-mobile" onclick="cari();">TAMPILKAN</button>
                                </div>
                              </div>
                              <hr>
                              <table id="order-table" class="table table-striped table-bordered nowrap" width="100%" border="1" cellpadding="0" cellspacing="0">
                                <thead>
                                  <tr class="bg-primary">
                                    <th class="text-center">NO.</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">DATE/ TIME SCANNED</th>
                                    <th class="text-center">NO. JOB</th>
                                    <th class="text-center">PART NAME</th>
                                    <th class="text-center">PART ID.</th>
                                    <th class="text-center">QTY. JOB</th>
                                    <th class="text-center">QTY. IN</th>
                                    <th class="text-center">LOC. RESULT</th>
                                    <th class="text-center">NOTES</th>
                                    <th class="text-center">BARCODE NO.</th>
                                    <th class="text-center">SCANNED BY</th>
                                    <th class="text-center">#</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
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
          <h4 class="modal-title">Tambahkan Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_simpan">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Barcode No.</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="no_barcode" id="no_barcode" readonly>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Status</label>
              <div class="col-sm-10">
                <select id="status_scan" name="status_scan" class="form-control">
                  <option value="0" selected="selected">-- Pilih --</option>
                  <option value="OK">OK</option>
                  <option value="NG">NG</option>
                </select>
                <span class="help-block"></span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
          <button id="btnSave" type="button" onclick="simpan_barcode();" class="btn btn-primary waves-effect waves-light ">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <div id="loading-screen" class="loading">Loading&#8230;</div>

  <?php $this->load->view('adminx/components/bottom_js_datatable'); ?>
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/index.min.js"></script>
  <script type="text/javascript" src=""></script>
  <!-- <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script> -->
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
                $("#barcode_no").val(result.text);
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
        const barcode_no = $("#barcode_no").val();
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>produksi/cek_barcode",
          data: {
            barcode_no: barcode_no
          },
          dataType: "JSON",
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            if (hasil.status_code == 200) {
              $("#loading-screen").hide();

              open_modal_approved(barcode_no);
            } else {
              $("#loading-screen").hide();
              $('#scanForm')[0].reset();
              Swal.fire(
                'Oops!',
                hasil.message,
                'error'
              );
            }
          },
          error: function(res) {
            alert('Something is wrong');
          }
        });
        return false;
      });
    });

    //FUNCTION OPEN MODAL APPROVED
    function open_modal_approved(barcode_no) {
      $('#no_barcode').val(barcode_no);
      $('#modal').modal('show');
    }

    //FUNCTION SIMPAN BARCODE
    function simpan_barcode() {
      var status = $('#status_scan').find(":selected").val();
      if (status == 0 || status == '0') {
        alert('Harap pilih status');
      } else {

        var array_data = $("#form_simpan").serializeArray();
        $.ajax({
          url: "<?php echo base_url(); ?>produksi/save_barcode",
          data: array_data,
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            console.log(hasil);
            if (hasil.status_code == 200) {
              $("#loading-screen").hide();
              $('#scanForm')[0].reset();
              $('#modal').modal('hide');
              reload_table();
            } else if (hasil.status_code == 400) {
              $("#loading-screen").hide();
              Swal.fire(
                'Oops!',
                hasil.message,
                'error'
              );
              $("#loading-screen").hide();
            } else if (hasil.status == 'forbidden') {
              $("#loading-screen").hide();
              Swal.fire(
                'FORBIDDEN',
                'Access Denied',
                'info',
              )
            } else {
              Swal.fire(
                'Oops!',
                hasil.message,
                'warning'
              );
              $("#loading-screen").hide();
            }
          }
        })
      }
    }

    //FUNCTION HAPUS DATA
    function hapus_data(id) {
      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Tidak, batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '<?php echo base_url(); ?>produksi/delete_data',
            type: 'POST',
            data: {
              scan_id: id
            },
            error: function() {
              alert('Something is wrong');
            },
            success: function(data) {
              var result = JSON.parse(data);
              console.log(result);
              if (result.status == 'forbidden') {
                Swal.fire(
                  'FORBIDDEN',
                  'Access Denied',
                  'info',
                )
              } else if (result.status == 'error') {
                Swal.fire(
                  'Oops',
                  result.message,
                  'info',
                )
              } else {
                $("#" + id).remove();
                Swal.fire(
                  'Sukses!',
                  'Anda sukses menghapus data',
                  'success'
                )
                reload_table();
              }
            }
          });
        }
      })
    }

    //FUNCTION CARI BERDASARKAN TANGGAL
    function cari() {
      //SET JENIS PART INTO LOCAL STORAGE
      //UNTUK DEFAULT PILIHAN, CUKUP PILIH SEKALI
      var jenis_part = $('#jenis_part').val();
      localStorage.setItem("jenis_part", jenis_part);

      reload_table();
    }

    //FUNCTION RELOAD TABLE
    function reload_table() {
      table.ajax.reload(null, false);
    }

    $(document).ready(function() {
      $("#loading-screen").hide();

      var jenis_part = localStorage.getItem("jenis_part");
      if (jenis_part != null) {
        $('#jenis_part').val(jenis_part);
      } else {
        $('#jenis_part').val('WH-FG');
      }

      table = $('#order-table').DataTable({
        dom: 'Bfrltip',
        buttons: [
          'excel'
          //'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'processing': true,
        'serverSide': false,
        'serverMethod': 'post',
        'ajax': {
          url: "<?php echo base_url(); ?>produksi/show_barcode_data_list",
          type: 'POST',
          "data": function(data) {
            data.bulan = $('#bulan').val();
            data.tahun = $('#tahun').val();
            data.tanggal = $('#tanggal').val();
            data.jenis_part = $('#jenis_part').val();
          }
        },

        'aoColumns': [{
            "NO.": "NO.",
            "sClass": "text-right"
          },
          {
            "STATUS": "STATUS",
            "sClass": "text-center"
          },
          {
            "DATE/ TIME SCANNED": "DATE/ TIME SCANNED",
            "sClass": "text-left"
          },
          {
            "NO. JOB": "NO. JOB",
            "sClass": "text-left"
          },
          {
            "PART NAME": "PART NAME",
            "sClass": "text-left"
          },
          {
            "PART ID.": "PART ID.",
            "sClass": "text-left"
          },
          {
            "QTY. JOB": "QTY. JOB",
            "sClass": "text-right"
          },
          {
            "QTY. IN": "QTY. IN",
            "sClass": "text-right"
          },
          {
            "LOC. RESULT": "LOC. RESULT",
            "sClass": "text-center"
          },
          {
            "NOTES": "NOTES",
            "sClass": "text-left"
          },
          {
            "BARCODE NO.": "BARCODE NO.",
            "sClass": "text-left"
          },
          {
            "SCANNED BY": "SCANNED BY",
            "sClass": "text-center"
          },
          {
            "#": "#",
            "sClass": "text-center"
          },
        ],

        /*'aoColumns': [
          { "NO.": "NO." , "sClass": "text-right"},
          { "BARCODE NO.": "BARCODE NO." , "sClass": "text-left" },
          { "DATE/ TIME SCANNED": "DATE/ TIME SCANNED" , "sClass": "text-center" },
          { "DATE CREATED": "DATE CREATED" , "sClass": "text-left" },
          { "NO. JOB": "NO. JOB" , "sClass": "text-left" },
          { "PART ID.": "PART ID." , "sClass": "text-left" },
          { "PART NAME": "PART NAME" , "sClass": "text-left" },
          { "UNIT ID": "UNIT ID" , "sClass": "text-center" },
          { "QTY.": "QTY." , "sClass": "text-left" },
          { "LOC. RESULT": "LOC. RESULT" , "sClass": "text-center" },
          { "SCANNED BY": "SCANNED BY" , "sClass": "text-center" },
          { "NOTES": "NOTES" , "sClass": "text-left" }
        ],*/

        "columnDefs": [{
          "targets": [-1, 0], //last column
          "orderable": false, //set not orderable
          className: 'text-right'
        }, ]
      });
    });
  </script>
</body>

</html>