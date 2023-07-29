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
                        <div class="row gutters-sm">
                          <div class="col-md-4 mb-3">
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                  <?php
                                  if ($karyawan_detail[0]->GENDER == 'M') {
                                  ?>
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                  <?php
                                  } elseif ($karyawan_detail[0]->GENDER == 'F') {
                                  ?>
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar8.png" alt="Admin" class="rounded-circle" width="150">
                                  <?php
                                  } else {
                                  ?>
                                    <img src="https://cdn-icons-png.flaticon.com/512/1177/1177568.png" alt="Admin" class="rounded-circle" width="150">
                                  <?php
                                  }
                                  ?>

                                  <!-- <?php //if ($karyawan_detail[0]->Gender == 'M') : 
                                        ?>
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                                  <?php //else : 
                                  ?>
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar8.png" alt="Admin" class="rounded-circle" width="150">
                                  <?php //endif 
                                  ?> -->

                                  <div class="mt-3">
                                    <h4><?php echo strtoupper($this->session->userdata('user_name')); ?></h4>
                                    <p class="text-secondary mb-1"><?php echo $this->session->userdata('user_email'); ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="card mb-3">
                              <div class="card-header text-center">
                                <h5><?php echo strtoupper($nama_halaman); ?></h5>
                              </div>
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">DEPARTMENT</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php //echo $karyawan_detail[0]->DEPTNAME; 
                                    ?>
                                    <?php echo $this->session->userdata('user_dept_name'); ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">NIP</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php //echo $karyawan_detail[0]->SSN; 
                                    ?>
                                    <?php echo $this->session->userdata('user_nip'); ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php //echo $karyawan_detail[0]->Name; 
                                    ?>
                                    <?php echo $this->session->userdata('user_realName'); ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Gender</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php 
                                      echo $karyawan_detail[0]->GENDER == 'M' ? 'Laki-laki' : 'Perempuan'; 
                                    ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">BOD</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php
                                    $date       = $karyawan_detail[0]->BIRTHDAY;
                                    $createDate = new DateTime($date);
                                    $strip      = $createDate->format('Y-m-d');

                                    echo date_indo($strip);
                                    ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php echo $this->session->userdata('user_email'); ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php echo $karyawan_detail[0]->OPHONE == '' ? '-' : $karyawan_detail[0]->OPHONE; ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Mobile</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php echo $karyawan_detail[0]->FPHONE == '' ? '-' : $karyawan_detail[0]->FPHONE; ?>
                                  </div>
                                </div>
                                <hr>
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                  </div>
                                  <div class="col-sm-9 text-secondary">
                                    <?php echo $karyawan_detail[0]->STREET == '' ? '-' : $karyawan_detail[0]->STREET; ?>
                                  </div>
                                </div>
                              </div>
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

  <script type="text/javascript" src="<?php echo base_url(); ?>files/bower_components/jquery/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
  <?php $this->load->view('adminx/components/bottom_js_datatable'); ?>
  <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <script type="text/javascript" src="<?php echo base_url(); ?>files/assets/js/sweetalert2.all.min.js"></script>
  <script>

  </script>
</body>

</html>