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
  <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
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
                              <?php echo strtoupper($nama_halaman); ?> QC
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
                              <div class="form-group row justify-content-center"> <!-- value="|PCG/JOB/43/202302/00053-003|003|-|0|WH-GRS00|5200|400|-|" -->
                                <input type="search" id="barcode_no" name="barcode_no" class="form-control form-control-round form-control-uppercase text-center form-control-lg form-txt-danger form-control-danger form-search" autofocus="on" autocomplete="off" placeholder="SCAN BARCODE DISINI" readonly="readonly ><!-- value=" |PCG/JOB/12/202212/00001-001|001|-|0|WH-GRS01|15000|200|-|" -->
                              </div>
                            </form>
                            <hr class="m-t-50 m-b-50">
                            <div class="dt-responsive table-responsiveXX">
                              <h5 class="text-center">HASIL SCAN QC</h5>
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
                              <form id="frm-example" action="#" method="POST">
                                <p><button class="btn btn-danger">SET NG/ OK ALL</button></p>
                                <hr>
                                <table id="example" class="table table-striped table-bordered nowrap" width="100%" border="1" cellpadding="0" cellspacing="0">
                                  <thead>
                                    <tr class="bg-primary">
                                      <th class="text-center">
                                        <input name="select_all" value="1" id="example-select-all" type="checkbox" />
                                      </th>
                                      <th class="text-center">NO.</th>
                                      <th class="text-center">STATUS</th>
                                      <th class="text-center">NO. JOB</th>
                                      <th class="text-center">DATE/ TIME SCANNED</th>
                                      <th class="text-center">PART ID.</th>
                                      <th class="text-center">PART NAME</th>
                                      <th class="text-center">QTY. JOB</th>
                                      <th class="text-center">QTY. BOX</th>
                                      <th class="text-center">UNIT ID</th>
                                      <th class="text-center">DATE CREATED</th>
                                      <th class="text-center">LOC. RESULT</th>
                                      <th class="text-center">NOTES</th>
                                      <th class="text-center">BARCODE NO.</th>
                                      <th class="text-center">SCANNED BY</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>
                              </form>
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

  <!-- MODAL SAVE -->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Approved</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="RegisterValidation">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Barcode No.</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="no_barcode" id="no_barcode" readonly="readonly">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Status</label>
              <div class="col-sm-10">
                <select id="status" name="status" class="form-control">
                  <option disabled="disabled">-- Plih --</option>
                  <option value="OK" selected="selected">OK</option>
                  <option value="NG">NG</option>
                  <option value="SA">SA</option>
                </select>
                <span class="help-block"></span>

                <input type="hidden" name="status_old" id="status_old">
              </div>
            </div>
            <!-- <div id="show_details">
              <hr>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penyebab NG</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penyebab" id="penyebab" autocomplete="off">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Qty.</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="qty" id="qty" max="4" maxlength="4" autocomplete="off" placeholder="masukan hanya nomor" onkeypress="if(this.value.length==4) return false;">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">PIC Repair</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control text-capitalize" name="pic_repair" id="pic_repair">
                  <span class="help-block"></span>
                </div>
              </div>
            </div> -->
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
          <button id="btnSave" type="button" onclick="save_transaksi();" class="btn btn-primary waves-effect waves-light ">Approved</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL NG ALL -->
  <div class="modal fade" id="modal_ng_all" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Approved</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="save_ng_all">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Status</label>
              <div class="col-sm-10">
                <select id="status_all" name="status_all" class="form-control" onchange="getval(this);">
                  <option disabled="disabled">-- Plih --</option>
                  <option value="OK">OK</option>
                  <option value="NG" selected="selected">NG</option>
                </select>
                <span class="help-block"></span>
              </div>
            </div>
            <div id="show_details_all">
              <hr>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penyebab NG</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control text-capitalize" name="penyebab_all" id="penyebab_all" autocomplete="off">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Qty.</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="qty_all" id="qty_all" max="9" maxlength="9" autocomplete="off" placeholder="masukan hanya nomor" onkeypress="if(this.value.length == 9) return false;">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">PIC Repair</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control text-capitalize" name="pic_repair_all" id="pic_repair_all">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal">Close</button>
          <button id="btnSave" type="button" onclick="save_transaksi_all();" class="btn btn-primary waves-effect waves-light ">Approved</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL VIEW NG DETAIL -->
  <div class="modal fade" id="modal_ng" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Produk NG</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="reset_all()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="dt-responsive table-responsive">
            <table class="table table-bordered nowrap">
              <thead>
                <tr class="bg-info">
                  <th class="text-center">No</th>
                  <th class="text-center">Barcode No.</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Qty.</th>
                  <th class="text-center">PIC Repair</th>
                  <th class="text-center">Tanggal</th>
                </tr>
              </thead>
              <tbody id="data_ng">

              </tbody>
            </table>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <button id="btn_tambah" type="button" class="btn btn-warning text-white float-right">TAMBAH</button>
            </div>
          </div>
          <form id="more_ng_form">
            <div id="contentku">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Barcode No.</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="no_barcode_view" id="no_barcode_view" readonly="readonly">

                  <input type="hidden" name="scan_id_view" id="scan_id_view">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="status_view" id="status_view" readonly="readonly">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Penyebab NG</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="penyebab_view" id="penyebab_view">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Qty.</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" name="qty_view" id="qty_view" max="9" maxlength="9" autocomplete="off" placeholder="masukan hanya nomor" onkeypress="if(this.value.length == 9) return false;">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">PIC Repair</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control text-capitalize" name="pic_repair_view" id="pic_repair_view">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary" onclick="save_more_ng()">SIMPAN</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-outline-danger waves-effect md-trigger" data-dismiss="modal" onclick="reset_all()">Close</button>
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
  <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
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
    //FUNCITON SIMPAN ALL
    function save_transaksi_all() {
      var status_all = $('#status_all').val();
      var penyebab_all = $("#penyebab_all").val();
      var qty_all = $("#qty_all").val();
      var pic_repair_all = $("#pic_repair_all").val();
      var data_scan = localStorage.getItem('data_scan_id');

      let data_form = {
        'status_all': status_all,
        'penyebab_all': penyebab_all,
        'qty_all': qty_all,
        'pic_repair_all': pic_repair_all,
        'data_scan_id': data_scan
      };

      //JIKA STATUS "NG" INSERT KE TABLE tbl_scanbarcode_job_details
      if (status_all == 'NG') {
        if (penyebab_all == '' || penyebab_all == null) {
          alert("Penyebab tidak boleh kosong");
          $("#penyebab_all").focus();
        } else if (qty_all == '' || qty_all == null) {
          alert("Qty tidak boleh kosong");
          $("#qty_all").focus();
        } else if (pic_repair_all == '' || pic_repair_all == null) {
          alert("PIC Repair tidak boleh kosong");
          $("#pic_repair_all").focus();
        } else {
          $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>qc/insert_status_all",
            data: data_form,
            dataType: "JSON",
            beforeSend: function(res) {
              $("#loading-screen").show();
            },
            success: function(response) {
              if (response.status_code == 200) {
                Swal.fire(
                  response.status,
                  response.message,
                  'success'
                );
                $("#loading-screen").hide();
                $('#modal_ng_all').modal('hide');
                $('#save_ng_all')[0].reset();
                $('#frm-example')[0].reset();
                reload_table();
                localStorage.removeItem("data_scan_id");
              } else if (response.status_code == 204) {
                $("#loading-screen").hide();
                Swal.fire({
                  title: 'Apakah anda yakin?',
                  text: response.message + ' dan Ingin menambahkan data NG baru',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>qc/insert_status_all_ng_more",
                      data: data_form,
                      dataType: "JSON",
                      beforeSend: function(res) {
                        $("#loading-screen").show();
                      },
                      success: function(response) {
                        console.log(response);
                        if (response.status_code == 200) {
                          Swal.fire(
                            response.status,
                            response.message,
                            'success'
                          );
                          $("#loading-screen").hide();
                          $('#modal_ng_all').modal('hide');
                          $('#save_ng_all')[0].reset();
                          $('#frm-example')[0].reset();
                          reload_table();
                          localStorage.removeItem("data_scan_id");
                        } else {
                          Swal.fire(
                            response.status,
                            response.message,
                            'error'
                          )
                        }
                      }
                    });
                  }
                })
              } else {
                alert("Oops ERROR !");
              }
            },
            error: function(xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              //alert(thrownError);
            }
          });
        }
      } else {
        //JIKA STATUS "OK" UPDATE TABLE tbl_scanbarcode_job
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>qc/update_status_all",
          data: data_form,
          dataType: "JSON",
          beforeSend: function(res) {
            $("#loading-screen").show();
          },
          success: function(response) {
            if (response.status_code == 200) {
              Swal.fire(
                response.status,
                response.message,
                'success'
              );
              $("#loading-screen").hide();
              $('#modal_ng_all').modal('hide');
              $('#save_ng_all')[0].reset();
              $('#frm-example')[0].reset();
              reload_table();
              localStorage.removeItem("data_scan_id");
            } else {
              alert("Oops ERROR !");
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
          }
        });
      }
    }

    //CHECK OK OR NG
    function getval(sel) {
      if (sel.value == 'NG') {
        $('#show_details_all').show();
      } else {
        $('#show_details_all').hide();
      }
    }

    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }

    //FUNCTION SCAN BARCODE
    $(function() {
      $("#scanForm").submit(function() {
        const barcode_no = $("#barcode_no").val();
        $.ajax({
          url: "<?php echo base_url(); ?>qc/cek_barcode",
          data: {
            barcode_no: barcode_no
          },
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            $("#loading-screen").hide();
            if (hasil.status_code == 200) {

              if (hasil.data != null) {
                open_modal(barcode_no);
                $('#status').val(hasil.data.scan_status);
                $('#status_old').val(hasil.data.scan_status);
              } else {
                $('#status').val("OK");
                open_modal(barcode_no);
              }

            } else {
              Swal.fire(
                'Oops',
                hasil.message,
                'error',
              )
            }
          }
        })
        return false;
      });
    });

    //RESET ALL MODAL FORM
    function reset_all() {
      $("#contentku").hide();
      $("#btn_tambah").text("TAMBAH");
    }

    //SAVE MORE NG
    function save_more_ng() {
      var penyebab = $("#penyebab_view").val();
      var qty = $("#qty_view").val();
      var pic_repair_view = $("#pic_repair_view").val();
      var data = $("#more_ng_form").serialize();
      if (penyebab == '' || penyebab == null) {
        alert("Penyebab tidak boleh kosong");
        $("#penyebab_view").focus();
      } else if (qty == '' || qty == null) {
        alert("Qty tidak boleh kosong");
        $("#qty_view").focus();
      } else if (pic_repair_view == '' || pic_repair_view == null) {
        alert("PIC Repair tidak boleh kosong");
        $("#pic_repair_view").focus();
      } else {

        $.ajax({
          url: "<?php echo base_url(); ?>qc/save_more_ng",
          data: data,
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {

            if (hasil.status_code == 200) {
              $("#loading-screen").hide();
              $('#more_ng_form')[0].reset();
              $("#contentku").hide();
              $("#btn_tambah").text("TAMBAH");
              view_details(hasil.data.scan_id)
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
      }
    }

    //FUNCTION SHOW HIDE BUTTON TAMBAH/ SEMBUNYIKAN
    $("#btn_tambah").click(function(ev) {
      var a = $(this).text();
      setTimeout(function() {
        document.getElementById("contentku").scrollIntoView({
          behavior: "smooth"
        });
        if (a == 'TAMBAH') {
          $("#btn_tambah").text("SEMBUNYIKAN");
        } else {
          $("#btn_tambah").text("TAMBAH");
        }
      }, 0);
      $("#contentku").toggle("slow");
    });

    //FUNCTION CARI BERDASARKAN TANGGAL
    function cari() {
      //SET JENIS PART INTO LOCAL STORAGE
      //UNTUK DEFAULT PILIHAN, CUKUP PILIH SEKALI
      var jenis_part = $('#jenis_part').val();
      localStorage.setItem("jenis_part_qc", jenis_part);

      reload_table();
    }

    //FUNCTION CHECK STATUS
    $("#status").change(function() {
      var selValue = $(this).val();
      if (selValue == 'NG') {
        $("#show_details").show();
      } else {
        $("#show_details").hide();
      }
    });

    //FUNCTION SAVE TRANSAKSI
    function save_transaksi() {
      var status = $("#status").val();
      var data = $("#RegisterValidation").serialize();
      if (status == 'NG') {

        var data = $("#RegisterValidation").serialize();
        $.ajax({
          url: "<?php echo base_url(); ?>qc/save_barcode",
          data: data,
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            if (hasil.status_code == 200) {
              $("#loading-screen").hide();
              $('#RegisterValidation')[0].reset();
              $('#scanForm')[0].reset();
              $("#show_details").hide();
              reload_table();
              reset_all();
              $('#modal').modal('hide');
            } else if (hasil.status_code == 400) {
              Swal.fire(
                'Oops',
                hasil.message,
                'error'
              );
              $("#loading-screen").hide();
              $('#modal').modal('hide');
              $('#scanForm')[0].reset();
            } else if (hasil.status == 'forbidden') {
              $("#loading-screen").hide();
              Swal.fire(
                'FORBIDDEN',
                'Access Denied',
                'info',
              )
            } else {
              Swal.fire(
                'Oops',
                hasil.message,
                'warning'
              );
              $("#loading-screen").hide();
              $('#modal').modal('hide');
              $('#scanForm')[0].reset();
            }

          }
        })
      } else {
        $.ajax({
          url: "<?php echo base_url(); ?>qc/save_barcode",
          data: data,
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            if (hasil.status_code == 200) {
              $("#loading-screen").hide();
              $('#RegisterValidation')[0].reset();
              $('#scanForm')[0].reset();
              $("#show_details").hide();
              reload_table();
              reset_all();
              $('#modal').modal('hide');
            } else if (hasil.status_code == 400) {
              Swal.fire(
                'Oops',
                hasil.message,
                'error'
              );
              $("#loading-screen").hide();
              $('#modal').modal('hide');
              $('#scanForm')[0].reset();
            } else if (hasil.status == 'forbidden') {
              $("#loading-screen").hide();
              Swal.fire(
                'FORBIDDEN',
                'Access Denied',
                'info',
              )
            } else {
              Swal.fire(
                'Oops',
                hasil.message,
                'warning'
              );
              $("#loading-screen").hide();
              $('#modal').modal('hide');
              $('#scanForm')[0].reset();
            }
          }
        })
      }
    }

    //FUNCTION VIEW DETAIL NG
    function view_details(scan_id, job_no) {
      console.log(job_no);
      $.ajax({
        url: "<?php echo base_url(); ?>qc/view_product_ng",
        data: {
          scan_id: scan_id
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          if (hasil.status_code == 200) {
            $('#scan_id_view').val(hasil.header.scan_id);
            $('#no_barcode_view').val(hasil.header.barcode_no);
            $('#status_view').val(hasil.header.scan_status);

            $("#data_ng").html(hasil.html);
            $("#loading-screen").hide();
            $('#modal_ng').modal('show');
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
    }

    //FUNCTION OPEN MODAL
    function open_modal(barcode_no) {
      $('#no_barcode').val(barcode_no);
      $('#modal').modal('show');
    }

    //FUNCTION RELOAD TABLE
    function reload_table() {
      table.ajax.reload(null, false);
    }

    $(document).ready(function() {
      $("#show_details").hide();
      $("#contentku").hide();
      $("#loading-screen").hide();

      var jenis_part = localStorage.getItem("jenis_part_qc");
      if (jenis_part != null) {
        $('#jenis_part').val(jenis_part);
      } else {
        $('#jenis_part').val('WH-FG');
      }

      table = $('#example').DataTable({
        dom: 'Bfrltip',
        buttons: [
          'excel'
          //'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        scrollY: "100%",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        // fixedColumns: {
        //   leftColumns: 2,
        //   rightColumns: 0
        // },
        "ordering": false,
        'processing': true,
        'serverSide': false,
        'serverMethod': 'post',
        'ajax': {
          url: "<?php echo base_url(); ?>qc/show_barcode_data_list",
          type: 'POST',
          "data": function(data) {
            data.bulan = $('#bulan').val();
            data.tahun = $('#tahun').val();
            data.tanggal = $('#tanggal').val();
            data.jenis_part = $('#jenis_part').val();
          }
        },

        'aoColumns': [{
            "#": "#",
            "sClass": "text-center"
          },
          {
            "NO.": "NO.",
            "sClass": "text-right"
          },
          {
            "STATUS": "STATUS",
            "sClass": "text-center"
          },
          {
            "NO. JOB": "NO. JOB",
            "sClass": "text-left"
          },
          {
            "DATE/ TIME SCANNED": "DATE/ TIME SCANNED",
            "sClass": "text-center"
          },
          {
            "PART ID.": "PART ID.",
            "sClass": "text-left"
          },
          {
            "PART NAME": "PART NAME",
            "sClass": "text-left"
          },
          {
            "QTY. JOB": "QTY. JOB",
            "sClass": "text-right"
          },
          {
            "QTY. BOX": "QTY. BOX",
            "sClass": "text-right"
          },
          {
            "UNIT ID": "UNIT ID",
            "sClass": "text-center"
          },
          {
            "DATE CREATED": "DATE CREATED",
            "sClass": "text-right"
          },
          {
            "LOC. RESULT": "LOC. RESULT",
            "sClass": "text-center"
          },
          {
            "NOTES": "NOTES",
            "sClass": "text-center"
          },
          {
            "BARCODE NO.": "BARCODE NO.",
            "sClass": "text-center"
          },
          {
            "SCANNED BY": "SCANNED BY",
            "sClass": "text-left"
          }
        ],

        'columnDefs': [{
          'targets': 0,
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          'render': function(data, type, full, meta) {
            if (full[15] == 'NG') {
              return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
            } else {
              return '-';
            }
          }
        }],

        'select': {
          'style': 'multi'
        },

        'order': [
          [1, 'asc']
        ]
      });

      // Handle click on "Select all" control
      $('#example-select-all').on('click', function() {
        // Check/uncheck all checkboxes in the table
        var rows = table.rows({
          'search': 'applied'
        }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });

      // Handle click on checkbox to set state of "Select all" control
      $('#example tbody').on('change', 'input[type="checkbox"]', function() {
        // If checkbox is not checked
        if (!this.checked) {
          var el = $('#example-select-all').get(0);
          // If "Select all" control is checked and has 'indeterminate' property
          if (el && el.checked && ('indeterminate' in el)) {
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
          }
        }
      });

      $('#frm-example').on('submit', function(e) {
        var form = this;
        e.preventDefault();

        // Iterate over all checkboxes in the table
        table.$('input[type="checkbox"]').each(function() {
          // If checkbox doesn't exist in DOM
          if (!$.contains(document, this)) {
            // If checkbox is checked
            if (this.checked) {
              // Create a hidden element 
              $(form).append(
                $('<input>')
                .attr('type', 'hidden')
                .attr('name', this.name)
                .val(this.value)
              );
            }
          }
        });

        // FOR TESTING ONLY

        // Output form data to a console
        $('#example-console').text($(form).serialize());
        //console.log("Form submission", $(form).serialize());
        var data_array = table.$('input[type="checkbox"]').serializeArray();
        if (data_array.length > 0) {
          localStorage.setItem("data_scan_id", JSON.stringify(data_array));
          $('#modal_ng_all').modal('show');
        } else {
          alert("Silahkan pilih data dahulu");
          return false;
        }

        // Prevent actual form submission
        e.preventDefault();
      });

    });
  </script>
</body>

</html>