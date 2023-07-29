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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/loading.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/daterangepicker.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/timeline.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/pages.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>files/assets/css/coret.css" />
  <style type="text/css">
    td {
      position: relative;
    }

    td.strikeout:before {
      content: " ";
      position: absolute;
      top: 50%;
      left: 0;
      border-bottom: 1px solid #111;
      width: 100%;
    }

    td.strikeout:after {
      content: "\00B7";
      font-size: 1px;
    }
  </style>
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
                            <div class="dt-responsive table-responsiveXX">
                              <div class="form-group row">
                                <label class="col-md-2 col-sm-12 col-form-label m-t-30">Filter data by</label>
                                <div class="col-md-4 col-sm-12 m-t-30">
                                  <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span>
                                  </div>

                                  <input type="hidden" name="start_date" id="start_date">
                                  <input type="hidden" name="end_date" id="end_date">

                                </div>
                                <div class="col-md-2 col-sm-12 m-t-30">
                                  <select class="form-control" name="pilih_wh_mpr" id="pilih_wh_mpr" required="required">
                                    <option disabled="disabled">-- Pilih --</option>
                                    <option value="All" selected="selected">All</option>
                                    <option value="WH-RM">WH-RM</option>
                                    <option value="WH-RCK">WH-RCK</option>
                                  </select>
                                </div>
                                <div class="col-md-3 col-sm-12 m-t-30">
                                  <button id="btnCari" type="button" class="btn btn-info btn-full-mobile" onclick="cari();">TAMPILKAN</button>
                                </div>
                              </div>
                              <hr>
                              <table id="order-table" class="table table-striped table-bordered nowrap" width="100%" border="1" cellpadding="0" cellspacing="0">
                                <thead>
                                  <tr class="">
                                    <th rowspan="2" class="text-center align-middle bg-primary">No</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">#</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">No. Bukti</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Job Date</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Part ID</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Partname</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Qty</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Keterangan</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Create By</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Create Date</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Status</th>
                                    <th colspan="2" class="text-center  align-middle bg-primary">Action</th>
                                    <th rowspan="2" class="text-center align-middle bg-primary">Lihat Detail</th>
                                  </tr>
                                  <tr>
                                    <th class="text-center align-middle bg-primary">Proses</th>
                                    <th class="text-center align-middle bg-primary">Kirim</th>
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

  <!-- modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel1">Status Details</h5>
          <h5 class="modal-title" id="exampleModalLabel2">Detail MPR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="content">
            <!-- timeline disini -->
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- end modal -->

  <!-- modal with table inside -->
  <div class="modal fade" id="modalTable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cek dan Pilih Item dari <span id="mpr_terpilih" class="text-danger font-weight-bold"></span></h5>
          <button type="button" class="close" aria-label="Close" onclick="check_data()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <form id="form_pilihan">
              <table id="mpr-table" class="table table-bordered table-striped">
                <thead class="bg-primary">
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">
                      <input type="checkbox" id="checkAll" />
                    </th>
                    <th class="text-center">Part ID</th>
                    <th class="text-center">Part Name</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Std. Packing</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Qty. Sisa Prod.</th>
                    <th class="text-center">Unit ID</th>
                    <th class="text-center">Loc. ID</th>
                  </tr>
                </thead>
                <tbody id="contentPilihan">

                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal -->

  <!-- modal komentar -->
  <div class="modal fade" id="modalKomentar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambahkan catatan dari Item <span id="mpr_noted" class="text-danger font-weight-bold"></span></h5>
          <button type="button" class="close" aria-label="Close" onclick="tutup_modal_noted()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_catatan">
            <div class="container mt-4 mb-2">
              <div class="row">
                <div class="col-md-5 col-sm-12 mb-3">
                  <select name="item_mpr" id="item_mpr" class="form-control mt-3 mb-3">
                    <option disabled selected>-- Pilih Item --</option>
                  </select>

                  <button type="button" onclick="simpan_catatan()" class="btn btn-danger btn-block mt-3">Tambahkan Catatan</button>
                </div>
                <div class="col-md-7 col-sm-12">
                  <input type="hidden" name="nomor_mpr" id="nomor_mpr">
                  <textarea name="catatan_mpr" id="catatan_mpr" class="form-control text-capitalize" cols="30" rows="5" placeholder="Tambahkan catatan"></textarea>
                </div>
              </div>
            </div>
            <hr>
            <div class="mail-body">
              <div class="mail-body-content email-read">
                <div class="card">
                  <div id="isi_catatan" class="card-block">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- modal komentar end -->

  <!-- modal std packing -->
  <div class="modal fade" id="modalStdPacking" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit standard packing item <span class="text-danger" id="label_std_packing"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clear_data()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_update_std">
            <input type="hidden" value="" name="kode">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Part ID</label>
              <div class="col-sm-9">
                <input type="hidden" id="id_std_packing" name="id_std_packing">
                <input type="hidden" id="no_bukti" name="no_bukti">
                <input type="text" class="form-control" name="part_id" id="part_id" readonly="readonly">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Part Name</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="part_name" id="part_name" readonly="readonly">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Standart Packing</label>
              <div class="col-sm-9">
                <input type="text" class="form-control autonumber" name="std_packing" id="std_packing" data-v-min="00.0000" data-v-max="10000.0000">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Unit</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="unit" id="unit">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Satuan</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="satuan" id="satuan">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Location</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="location_id" id="location_id" readonly="readonly">
                <span class="help-block"></span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button id="btn_update" type="button" onclick="update_std_packing();" class="btn btn-primary waves-effect waves-light ">Update</button>
        </div>
      </div>
    </div>
  </div>
  <!-- modal std packing end -->

  <!-- LOADING -->
  <div id="loading-screen" class="loading">Loading&#8230;</div>

  <?php $this->load->view('adminx/components/bottom_js_datatable_fix_column'); ?>

  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/moment.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/daterangepicker.min.js"></script>

  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/pages/form-masking/inputmask.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/pages/form-masking/jquery.inputmask.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/pages/form-masking/autoNumeric.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/pages/form-masking/form-mask.js"></script>

  <script type="text/javascript">
    //RANGE DATE PICKER
    $(function() {

      var start = moment().subtract(7, 'days'); //moment();
      var end = moment();

      function cb(start, end) {
        $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        var start_date = start.format('YYYY-MM-DD');
        var end_date = end.format('YYYY-MM-DD');
        $("#start_date").val(start_date);
        $("#end_date").val(end_date);
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      }, cb);

      cb(start, end);

    });

    //RESET FORM EDIT STD PACKING
    function clear_data() {
      $("#form_update_std")[0].reset();
      let no_bukti = $("#no_bukti").val();
      mpr_detail(no_bukti);
    }

    //FUNGSI UPDATE STD PACKING
    function update_std_packing() {
      let id = $("#id_std_packing").val();
      let std_packing = $("#std_packing").val();
      let unit = $("#unit").val();
      let satuan = $("#satuan").val();
      let no_bukti = $("#no_bukti").val();

      if (std_packing == '' || std_packing == null) {
        alert("Kolom standard packing wajib diisi!");
        $("#std_packing").focus();
      } else if (unit == '' || unit == null) {
        alert("Kolom unit wajib diisi!");
        $("#std_packing").focus();
      } else if (satuan == '' || satuan == null) {
        alert("Kolom satuan wajib diisi!");
        $("#std_packing").focus();
      } else {
        $.ajax({
          url: "<?php echo base_url(); ?>warehouse_mpr/update_std_packing",
          data: {
            id_std_packing: id,
            standard_packing: std_packing,
            unit_packing: unit,
            satuan_packing: satuan
          },
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            if (hasil.status_code == 200) {
              $('#modalStdPacking').modal('hide');
              mpr_detail(no_bukti);
              Swal.fire({
                icon: 'success',
                title: hasil.status,
                text: hasil.message
              })
            } else {
              Swal.fire({
                icon: 'info',
                title: hasil.status,
                text: hasil.message
              })
            }
            $("#loading-screen").hide();
          },
          error: function(request, status, error) {
            alert("error when update data");
          }
        })
      }
    }

    //FUNGSI EDIT STD PACKING
    function edit_std_packing(PartID, LocID, NoBukti) {
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/edit_std_packing",
        data: {
          part_id: PartID,
          loc_id: LocID,
          no_bukti: NoBukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            $("#label_std_packing").html(PartID);
            $('#modalStdPacking').modal('show');
            $('#modalTable').modal('hide');
            $("#loading-screen").hide();

            $("#no_bukti").val(hasil.no_bukti);
            $("#id_std_packing").val(hasil.data.id);
            $("#part_id").val(hasil.data.PartID);
            $("#part_name").val(hasil.data.PartName);
            $("#std_packing").val(hasil.data.StandartPacking);
            $("#unit").val(hasil.data.Unit);
            $("#satuan").val(hasil.data.Satuan);
            $("#location_id").val(hasil.data.Location);
          } else {
            $("#loading-screen").hide();
            Swal.fire({
              icon: 'info',
              title: 'Oops...',
              text: hasil.message
            })
          }
        },
        error: function(request, status, error) {
          alert("error when update data");
        }
      })
    }

    //FUNGSI HAPUS CATATAN
    function hapus_catatan(no_bukti, id_catatan) {
      $.ajax({
        url: "<?php echo base_url(); ?>produksi_mpr/hapus_catatan",
        data: {
          id: id_catatan,
          no_mpr: no_bukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          open_modal_comment(no_bukti);
        },
        error: function(request, status, error) {
          alert("error when delete data");
        }
      })
    }

    //FUNGSI TUTUP MODAL CATATAN
    function tutup_modal_noted() {
      $('#modalKomentar').modal('hide');
      $("#form_catatan")[0].reset();
    }

    //SIMPAN CATATAN
    function simpan_catatan() {
      let no_mpr = $("#nomor_mpr").val();
      let isi_catatan = $("#catatan_mpr").val();
      let isi_item = $("#item_mpr").val();

      if (isi_catatan == '' || isi_catatan == null) {
        alert("Kolom catatan harus diisi!");
        $("#catatan_mpr").focus();
      } else if (isi_item == '' || isi_item == null) {
        alert("Kolom item harus diisi!");
      } else {

        $.ajax({
          url: "<?php echo base_url(); ?>produksi_mpr/simpan_catatan",
          data: {
            catatan: isi_catatan,
            item: isi_item,
            mpr: no_mpr
          },
          type: 'POST',
          dataType: 'JSON',
          beforeSend: function() {
            $("#loading-screen").show();
          },
          success: function(hasil) {
            $("#loading-screen").hide();
            if (hasil.status_code == 200) {
              $("#form_catatan")[0].reset();
              open_modal_comment(hasil.data.no_bukti);
            } else {
              Swal.fire({
                icon: 'error',
                title: hasil.status,
                text: hasil.message
              })
            }
          },
          error: function(request, status, error) {
            alert("error when saving data");
          }
        })
      }
    }

    //MODAL CATATAN
    function open_modal_comment(nobukti) {
      $('#modalKomentar').modal('show');
      $("#mpr_noted").html(nobukti);
      $("#nomor_mpr").val(nobukti);

      $.ajax({
        url: "<?php echo base_url(); ?>produksi_mpr/get_mpr_items",
        data: {
          nobukti: nobukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          var options = '';
          options += '<option selected disabled>-- Pilih Item --</option>';
          for (var i = 0; i < hasil.data.length; i++) {
            var nomor = i + 1;
            options += '<option value="' + hasil.data[i].id + '">' +
              nomor + '. ' + hasil.data[i].PartID + ' - ' + hasil.data[i].PartName + '</option>';
          }
          $("#item_mpr").html(options);
          $("#isi_catatan").html(hasil.noted_data);
          $("#loading-screen").hide();
        },
        error: function(request, status, error) {
          alert(request.responseText);
        }
      })
    }

    //FUNCTION CARI
    function cari() {
      var pilih_wh = $('#pilih_wh_mpr').val();
      localStorage.setItem("pilih_wh_mpr", pilih_wh);
      reload_table();
    }

    //FUNCTION CEK ITEM MPR
    function kirim_mpr_wh(nobukti, jlh_data_asli) {
      var loc_id = 'WH002';
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/cek_data_details",
        data: {
          nobukti: nobukti,
          loc_id: loc_id
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          if (hasil.jumlah_data == jlh_data_asli) {
            save_mpr_wh(nobukti)
          } else {
            $("#loading-screen").hide();
            let sisa_item = jlh_data_asli - hasil.jumlah_data;
            Swal.fire({
              title: "Oops",
              html: "Anda masih menyisakan <span class='text-danger font-weight-bold' style='font-size: 24px;'>" + sisa_item + "</span> item yang belum dipilih.",
              icon: 'info'
            })
          }
        },
        error: function(request, status, error) {
          Swal.fire(
            'Oops',
            'Something went wrong',
            'error'
          )
        }
      })
    }

    //FUNCTION KIRIM MPR
    function save_mpr_wh(nobukti) {
      var loc_id = 'WH002';
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/kirim_mpr_wh",
        data: {
          nobukti: nobukti,
          loc_id: loc_id
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            $("#loading-screen").hide();
            Swal.fire(
              'OK',
              hasil.message,
              'success'
            );
            reload_table();
          } else if (hasil.status_code == 409) {
            Swal.fire(
              'Oops',
              hasil.message,
              'warning'
            );
            $("#loading-screen").hide();
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

    function kirim_mpr_wh_OLD(nobukti) {
      // console.log(nobukti);

      var loc_id = 'WH002';
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/kirim_mpr_wh",
        data: {
          nobukti: nobukti,
          loc_id: loc_id
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            // $("#data_ng").html(hasil.html);
            $("#loading-screen").hide();
            // $('#modal_ng').modal('show');
            Swal.fire(
              'OK',
              hasil.message,
              'success'
            );
            reload_table();
          } else if (hasil.status_code == 409) {
            Swal.fire(
              'Oops',
              hasil.message,
              'warning'
            );
            $("#loading-screen").hide();
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

    //FUNCTION TERIMA MPR
    function proses_mpr_wh(nobukti) {
      // console.log(nobukti);
      var bulan = $("#bulan").val();
      var tahun = $("#tahun").val();
      var loc_id = 'WH001';
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/proses_mpr_wh",
        data: {
          nobukti: nobukti,
          bulan: bulan,
          tahun: tahun,
          loc_id: loc_id
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {

            // $("#data_ng").html(hasil.html);
            $("#loading-screen").hide();
            Swal.fire(
              'OK',
              hasil.message,
              'success'
            );
            // $('#modal_ng').modal('show');
            reload_table();
          } else if (hasil.status_code == 409) {
            Swal.fire(
              'Oops',
              hasil.message,
              'warning'
            );
            $("#loading-screen").hide();
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

    //LIHAT STATUS
    function lihat_status(nobukti) {
      // console.log(nobukti);
      $.ajax({
        url: "<?php echo base_url(); ?>ppic_mpr/lihat_status",
        data: {
          nobukti: nobukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            $('#exampleModalLabel1').show();
            $('#exampleModalLabel2').hide();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
          } else if (hasil.status_code == 409) {
            $('#exampleModalLabel1').show();
            $('#exampleModalLabel2').hide();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
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

    function saveChangeQty() {
      // console.log(nobukti);
      var id = $("#id").val();
      var partID = $("#partid").val();
      var QtyStandart = parseFloat($("#standart_packing").val());
      var Unit = $("#unit").val();
      var Satuan = $("#satuan").val();
      var no_mpr = localStorage.getItem("no_mpr");

      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/saveChangeQty",
        data: {
          id: id,
          QtyStandart: QtyStandart,
          Unit: Unit,
          Satuan: Satuan
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            Swal.fire(
              'OK',
              hasil.message,
              'success'
            );
            $("#loading-screen").hide();
            mpr_detail(no_mpr);
          } else if (hasil.status_code == 404) {
            Swal.fire(
              'Oops',
              hasil.message,
              'warning'
            );
            $("#loading-screen").hide();
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

    //CHANGE QTY
    function changeQty(partid, location) {
      // console.log();

      var partid = partid;
      var location = location;

      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/changeQty",
        data: {
          partid: partid,
          location: location
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          if (hasil.status_code == 200) {
            // console.log(hasil.message);
            $('#exampleModalLabel1').hide();
            $('#exampleModalLabel2').show();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
          } else if (hasil.status_code == 404) {
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

    //CEK DATA
    function check_data() {
      $('#form_pilihan')[0].reset();
      let jlh_data = localStorage.getItem("jumlah_mpr_item_produksi");
      let jlh_terpilih = [];
      let sisa_data = 0;
      $("input:checkbox[name=item]:checked").each(function() {
        jlh_terpilih.push($(this).val());
      });

      if (jlh_data == jlh_terpilih.length) {
        $('#modalTable').modal('hide');
      } else {
        sisa_data = jlh_data - jlh_terpilih.length;
        Swal.fire({
          title: "Yakin ingin keluar?",
          html: "Anda menyisakan <span class='text-danger font-weight-bold' style='font-size: 24px;'>" + sisa_data + "</span> item yang belum dipilih.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, keluar',
          cancelButtonText: 'Tidak'
        }).then((result) => {
          if (result.isConfirmed) {
            $('#modalTable').modal('hide');
          }
        })
      }
    }

    //CEKLIS
    function ceklis(id, qtyStd, stockSimpan, qtySisa) {
      var isi = $('#ceklis' + id).val();
      var array_data = isi.split(",");
      var isi_data = array_data[4].replace(/[']/g, '');

      var qtyStd = qtyStd;
      var stockSimpan = stockSimpan;
      var qtySisa = qtySisa;

      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/ceklis_update",
        data: {
          id: id,
          value: isi_data,
          qtyStd: qtyStd,
          stockSimpan: stockSimpan,
          qtySisa: qtySisa
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          mpr_detail(hasil.nobukti);
        }
      })
    }

    function ceklis_OLD(id, qtyStd, stockSimpan, qtySisa) {
      var isi = $('#ceklis' + id).val();
      var qtyStd = qtyStd;
      var stockSimpan = stockSimpan;
      var qtySisa = qtySisa;

      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/ceklis_update",
        data: {
          id: id,
          value: isi,
          qtyStd: qtyStd,
          stockSimpan: stockSimpan,
          qtySisa: qtySisa
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          mpr_detail(hasil.nobukti);
        }
      })
    }

    //MPR DETAIL
    function mpr_detail(nobukti) {
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/mpr_detail",
        data: {
          nobukti: nobukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          if (hasil.status_code == 200) {
            localStorage.setItem("jumlah_mpr_item_produksi", hasil.jumlah_data);
            $('#exampleModalLabel1').hide();
            $('#exampleModalLabel2').show();
            $("#contentPilihan").html(hasil.html);
            $("#mpr_terpilih").html(nobukti);
            $("#loading-screen").hide();
            $('#modalTable').modal('show');

            let jlh_actual = $('input[name="item"]:checked').serializeArray();
            if (jlh_actual.length == hasil.jumlah_data) {
              $('#checkAll').prop('checked', true);
            } else {
              $('#checkAll').prop('checked', false);
            }

          } else if (hasil.status_code == 409) {
            $('#exampleModalLabel1').hide();
            $('#exampleModalLabel2').show();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
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

    //MPR DETAIL LAMA
    function mpr_detail_OLD(nobukti) {
      localStorage.setItem("no_mpr", nobukti);
      $.ajax({
        url: "<?php echo base_url(); ?>warehouse_mpr/mpr_detail",
        data: {
          nobukti: nobukti
        },
        type: 'POST',
        dataType: 'JSON',
        beforeSend: function() {
          $("#loading-screen").show();
        },
        success: function(hasil) {
          console.log(hasil);
          if (hasil.status_code == 200) {
            $('#exampleModalLabel1').hide();
            $('#exampleModalLabel2').show();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
          } else if (hasil.status_code == 409) {
            $('#exampleModalLabel1').hide();
            $('#exampleModalLabel2').show();
            $("#content").html(hasil.html);
            $("#loading-screen").hide();
            $('#exampleModal').modal('show');
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

    //FUNCTION RELOAD TABLE
    function reload_table() {
      table.ajax.reload(null, false);
    }

    $(document).ready(function() {
      //CHECK ALL AND NOT
      $("#checkAll").change(function() {
        $("input:checkbox").prop('checked', $(this).prop("checked"));

        let jlh_terpilih = [];
        $("input:checkbox[name=item]:checked").each(function() {
          jlh_terpilih.push($(this).val());
        });

        if (jlh_terpilih.length > 0) {
          $.ajax({
            url: "<?php echo base_url(); ?>warehouse_mpr/ceklis_multiple",
            data: {
              value: jlh_terpilih
            },
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function() {
              $("#loading-screen").show();
            },
            success: function(hasil) {
              mpr_detail(hasil.no_bukti);
            }
          })
        } else {
          let jlh_terpilih2 = [];
          $("input:checkbox[name=item]").each(function() {
            jlh_terpilih2.push($(this).val());
          });

          $.ajax({
            url: "<?php echo base_url(); ?>warehouse_mpr/ceklis_multiple_unselected",
            data: {
              value: jlh_terpilih2
            },
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function() {
              $("#loading-screen").show();
            },
            success: function(hasil) {
              mpr_detail(hasil.no_bukti);
            }
          })
        }
      });

      $('.chk').on('click', function() {
        if ($('.chk:checked').length == $('.chk').length) {
          $('#checkAll').prop('checked', true);
        } else {
          $('#checkAll').prop('checked', false);
        }
      });
      //CHECK ALL AND NOT END

      $("#loading-screen").hide();

      var pilih_wh = localStorage.getItem("pilih_wh_mpr");
      console.log(pilih_wh);
      if (pilih_wh != null) {
        $('#pilih_wh_mpr').val(pilih_wh);
      } else {
        $('#pilih_wh_mpr').val('All');
      }

      table = $('#order-table').DataTable({
        dom: 'Bfrltip',
        buttons: [
          'excel'
          //'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        scrollY: "500px",
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        // fixedColumns: {
        //   leftColumns: 2
        //   // rightColumns: 1
        // },
        'processing': true,
        'serverSide': false,
        'serverMethod': 'POST',
        'ajax': {
          url: "<?php echo base_url(); ?>warehouse_mpr/monitoring_mpr_list_new",
          type: 'POST',
          "data": function(data) {
            data.start_date = $('#start_date').val();
            data.end_date = $('#end_date').val();
            data.pilih_wh = $('#pilih_wh_mpr').val();
          }
        },

        'aoColumns': [{
            "No": "No",
            "sClass": "text-right"
          },
          {
            "#": "#",
            "sClass": "text-center"
          },
          {
            "No. Bukti": "No. Bukti",
            "sClass": "text-left"
          },
          {
            "Job Date": "Job Date",
            "sClass": "text-right"
          },
          {
            "Part ID": "Part ID",
            "sClass": "text-left"
          },
          {
            "Part name": "Part name",
            "sClass": "text-left"
          },
          {
            "Qty": "Qty",
            "sClass": "text-right"
          },
          {
            "Keterangan": "Keterangan",
            "sClass": "text-left"
          },
          {
            "Create By": "Create By",
            "sClass": "text-left"
          },
          {
            "Create Date": "Create Date",
            "sClass": "text-left"
          },
          {
            "Status": "Status",
            "sClass": "text-center"
          },
          {
            "Proses": "Proses",
            "sClass": "text-center"
          },
          {
            "Kirim": "Kirim",
            "sClass": "text-center"
          },
          {
            "Lihat Detail": "Lihat Detail",
            "sClass": "text-center"
          }
        ],

        "columnDefs": [{
          "targets": [-1, 0, 1], //last column
          "orderable": false, //set not orderable
          className: 'text-right'
        }, ]
      });
    });
  </script>
</body>

</html>