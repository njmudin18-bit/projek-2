<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi_mpr extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

    //start tidak usah di ubah
    $this->load->helper(array('url', 'form', 'cookie'));
    $this->load->library(array('session', 'cart'));

    $this->load->model('auth_model', 'auth');
    if ($this->auth->isNotLogin());

    //START ADD THIS FOR USER ROLE MANAGMENT
    $this->contoller_name = $this->router->class;
    $this->function_name   = $this->router->method;
    $this->load->model('Rolespermissions_model');
    //END


    $this->load->model('Dashboard_model');
    $this->load->model('perusahaan_model', 'perusahaan');
    //end tidak usah di ubah

    $this->load->model('barcode_model', 'barcode_sales');
  }

  public function index()
  {
    //CHECK FOR ACCESS FOR EACH FUNCTION
    $user_level       = $this->session->userdata('user_level');
    $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
    if ($check_permission->num_rows() == 1) {
      $data['group_halaman']   = "PRODUKSI";
      $data['nama_halaman']   = "PRODUKSI MPR";
      $data['icon_halaman']   = "icon-airplay";
      $data['perusahaan']     = $this->perusahaan->get_details();

      //ADDING TO LOG
      $log_url     = base_url() . $this->contoller_name . "/" . $this->function_name;
      $log_type   = "VIEW";
      $log_data   = "";

      log_helper($log_url, $log_type, $log_data);
      //END LOG
      $this->load->view('adminx/produksi/monitoring_mpr_produksi', $data, FALSE);
    } else {
      redirect('errorpage/error403');
    }
  }

  //FUNGSI CEK DATA
  public function cek_data_details()
  {
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $no_bukti   = $this->input->post('nobukti');
    $loc_id     = $this->input->post('loc_id');

    $cek_data   = "SELECT COUNT(NoBukti) AS jlh_data FROM tbl_monitoring_mpr_detail 
                   WHERE NoBukti = '$no_bukti' 
                   AND StatusPR = '1'";
    $query      = $second_DB->query($cek_data);
    $result     = $query->row()->jlh_data;

    if ($result > 0) {
      $data = $query->result();
      echo json_encode(
        array(
          "status_code" => 200,
          "status"      => "success",
          "message"     => "NoBukti ditemukan",
          "jumlah_data" => $result
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 404,
          "status"      => "error",
          "message"     => "NoBukti tidak ditemukan",
          "jumlah_data" => $result
        )
      );
    }
  }

  //FUNGSI CEKLIS ALL
  public function ceklis_multiple()
  {
    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $update_data  = array();
    $array_data   = $this->input->post('value');
    $id_array     = array();
    foreach ($array_data as $key => $value) {
      $array_data_lop = explode(',', str_replace("'", "", $value));
      $id_update      = $array_data_lop[0];
      $id_array[]     = $array_data_lop[0];
      $isi_update     = $array_data_lop[4];
      $isi_ceklis     = 1;

      $update_data[] = array(
        'id'            => $id_update,
        'StatusPR'      => $isi_ceklis,
        'CreateByPR'    => $this->session->userdata('user_name'),
        'CreateDatePR'  => date('Y-m-d H:i:s')
      );
    };

    //GET NOMOR BUKTI
    $no_bukti = $second_DB->query("SELECT * FROM tbl_monitoring_mpr_detail where id = '$id_array[0]'")->row()->NoBukti;

    //UPDATE DATA
    $second_DB->trans_start();
    $second_DB->update_batch('tbl_monitoring_mpr_detail', $update_data, 'id');
    $second_DB->trans_complete();
    if ($second_DB->trans_status() === FALSE) {
      echo json_encode(
        array(
          "status_code"   => 500,
          "status"        => "error",
          "message"       => "MPR gagal diupdate!",
          "no_bukti"      => $no_bukti,
          "data"          => $update_data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code"   => 200,
          "status"        => "success",
          "message"       => "MPR sukses diupdate!",
          "no_bukti"      => $no_bukti,
          "data"          => $update_data
        )
      );
    }
  }

  //FUNGSI UNCEKLIS ALL
  public function ceklis_multiple_unselected()
  {
    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $update_data  = array();
    $array_data   = $this->input->post('value');
    $id_array     = array();
    foreach ($array_data as $key => $value) {
      $array_data_lop = explode(',', str_replace("'", "", $value));
      $id_update      = $array_data_lop[0];
      $id_array[]     = $array_data_lop[0];
      $isi_update     = $array_data_lop[4];
      $isi_ceklis     = '0';

      $update_data[] = array(
        'id'            => $id_update,
        'StatusPR'      => $isi_ceklis,
        'CreateByPR'    => $this->session->userdata('user_name'),
        'CreateDatePR'  => date('Y-m-d H:i:s')
      );
    };

    //GET NOMOR BUKTI
    $no_bukti = $second_DB->query("SELECT * FROM tbl_monitoring_mpr_detail where id = '$id_array[0]'")->row()->NoBukti;

    //UPDATE DATA
    $second_DB->trans_start();
    $second_DB->update_batch('tbl_monitoring_mpr_detail', $update_data, 'id');
    $second_DB->trans_complete();
    if ($second_DB->trans_status() === FALSE) {
      echo json_encode(
        array(
          "status_code"   => 500,
          "status"        => "error",
          "message"       => "MPR gagal diupdate!",
          "no_bukti"      => $no_bukti,
          "data"          => $update_data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code"   => 200,
          "status"        => "success",
          "message"       => "MPR sukses diupdate!",
          "no_bukti"      => $no_bukti,
          "data"          => $update_data
        )
      );
    }
  }

  //FUNGSI GET MPR DETAIL
  public function get_mpr_items()
  {
    $nobukti    = $this->input->post('nobukti');
    $trans      = explode('/', $nobukti);
    $tahunbulan = $trans[2];
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    //GET ITEMS MPR
    $sql    = "SELECT * FROM tbl_monitoring_mpr_detail 
               WHERE NoBukti = '$nobukti' ORDER BY PartName";

    $query  = $second_DB->query($sql);
    $result = $query->result();

    //GET NOTED MPR
    $sql_noted  = " SELECT a.*, b.PartID, b.PartName FROM tbl_catatan_mpr a
                    LEFT JOIN tbl_monitoring_mpr_detail b ON b.id = a.item_id
                    WHERE a.no_bukti = '$nobukti'
                    ORDER BY a.created_date DESC";
    $q_noted    = $second_DB->query($sql_noted);
    $res_noted  = $q_noted->result();
    $html_noted = "";

    foreach ($res_noted as $key => $value) {

      $isi = "'" . $nobukti . "', '" . $value->id . "'";

      $html_noted .= '<div class="media m-b-20">
                        <div class="media-left photo-table">
                          <a href="#">
                            <img class="media-object img-radius" src="https://img.freepik.com/free-icon/user_318-180888.jpg" alt="' . ucwords($value->created_by) . '">
                          </a>
                        </div>
                        <div class="media-body photo-contant">
                          <a href="#">
                            <h6 class="user-name txt-primary">' . ucwords($value->created_by) . ' 
                              <span class="pull-right">on ' . substr($value->created_date, 0, -4) . '
                                <i onclick="hapus_catatan(' . $isi . ')" class="fa fa-times-circle-o text-danger fa-lg ml-4" title="Hapus catatan"></i>
                              </span>
                            </h6>
                          </a>
                          <a class="user-mail txt-muted" href="#">
                            <h6>Item: ' . $value->PartID . ' - ' . $value->PartName . '</h6>
                          </a>
                          <div>
                            <p class="email-content mt-4">' . $value->catatan . '</p>
                          </div>
                        </div>
                      </div>';
    }

    echo json_encode(
      array(
        "status_code"   => 200,
        "status"        => "success",
        "message"       => "MPR item ditemukan",
        "no_bukti"      => $nobukti,
        "data"          => $result,
        "noted_data"    => $html_noted
      )
    );
  }

  //FUNGSI HAPUS CATATAN
  public function hapus_catatan()
  {
    $id         = $this->input->post('id');
    $no_mpr     = $this->input->post('no_mpr');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $data_del   = $second_DB->delete('tbl_catatan_mpr', array('id' => $id));
    if ($data_del) {
      echo json_encode(
        array(
          "status_code"   => 200,
          "status"        => "success",
          "message"       => "Catatan MPR sukses dihapus.",
          "no_mpr"        => $no_mpr,
          "data"          => $id
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code"   => 500,
          "status"        => "error",
          "message"       => "Catatan MPR gagal dihapus.",
          "no_mpr"        => $no_mpr,
          "data"          => $id
        )
      );
    }
  }

  //FUNGSI SIMPAN CATATAN
  public function simpan_catatan()
  {
    $no_mpr   = $this->input->post('mpr');
    $catatan  = ucfirst($this->input->post('catatan'));
    $item     = $this->input->post('item');

    $data = array(
      'no_bukti'      => $no_mpr,
      'item_id'       => $item,
      'catatan'       => $catatan,
      'created_date'  => date('Y-m-d H:i:s'),
      'created_by'    => $this->session->userdata('user_name'),
    );

    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $second_DB->trans_start();
    $second_DB->insert('tbl_catatan_mpr', $data);
    $second_DB->trans_complete();

    if ($second_DB->trans_status() === FALSE) {
      echo json_encode(
        array(
          "status_code"   => 500,
          "status"        => "error",
          "message"       => "Catatan MPR gagal disimpan.",
          "data"          => $data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code"   => 200,
          "status"        => "success",
          "message"       => "Catatan MPR sukses disimpan.",
          "data"          => $data
        )
      );
    }
  }

  public function ceklis_update()
  {
    $id         = $this->input->post('id');
    $value      = $this->input->post('value');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $isi_ceklis = $value == 0 ? '1' : '0';
    $data = array(
      'StatusPR' => $isi_ceklis,
      'CreateByPR' => $this->session->userdata('user_name'),
      'CreateDatePR' => date('Y-m-d H:i:s')
    );

    $second_DB->where('id', $id);
    $second_DB->update('tbl_monitoring_mpr_detail', $data);
    $sql = "SELECT NoBukti
            FROM tbl_monitoring_mpr_detail
            WHERE id = '$id'";

    $query  = $second_DB->query($sql);
    $result = $query->row();

    echo json_encode(
      array(
        "status_code" => 200,
        "status"      => "success",
        "message"     => "sukses menampilkan data",
        "data"        => $data,
        "nobukti"     => $result->NoBukti
      )
    );
  }

  //FUNGSI MPR
  public function mpr_detail()
  {
    $nobukti    = $this->input->post('nobukti');
    $trans      = explode('/', $nobukti);
    $tahunbulan = $trans[2];
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $sql = " SELECT a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking AS QtyStd, a.StockSimpan, SUM(c.Qty) AS Stock, a.UnitID, a.LocationID, a.Keterangan, a.StatusPR
             FROM tbl_monitoring_mpr_detail a
             LEFT JOIN tbl_standart_packing b ON b.PartID = a.PartID AND b.location = a.LocationID
             LEFT JOIN Buku_Stock$tahunbulan c ON c.PartID = a.PartID AND c.locationID = a.LocationID  AND c.nobukti <> a.nobukti AND replace(convert(char(10),c.Tgl,20),'-','') + replace(convert(char(12),c.CreateDate,14),':','') < (SELECT MAX(TANGGAL) FROM (SELECT  replace(convert(char(10),CreateDate,20),'-','') + replace(convert(char(12),CreateDate,14),':','') AS TANGGAL FROM buku_stock$tahunbulan  where NoBukti='$nobukti' GROUP BY CreateDate) TGL)
             WHERE a.NoBukti = '$nobukti'
             GROUP BY a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking, a.StockSimpan, a.UnitID, a.LocationID, a.Keterangan, a.StatusPR
             ORDER BY a.LocationID DESC";

    $query  = $second_DB->query($sql);
    $result = $query->result();
    $no     = 1;

    if (count($result) > 0) {
      $text = '';
      foreach ($result as $key => $value) {
        //$coret  = $value->StatusPR == 1 ? 'class_coret' : '';
        $coret  = $value->StatusPR == 1 ? 'strikeout' : '';
        $pro    = '';

        if ($value->StockSimpan == 0) {
          $stock = $value->Stock;
        } else {
          $stock = $value->StockSimpan;
        }

        if ($value->QtyStd == null) {
          $QtyStandart = 0;
        } else {
          $QtyStandart = $value->QtyStd;
        }

        $nilai1 = $value->Qty; // nilai 1 itu qty ppic
        $nilai2 = $QtyStandart; // nilai 2 itu standart qty
        $nilai3 = $stock; // nilai 3 itu stok
        $hasil  = 0;

        if ($nilai2 != null) {
          if ($nilai1 > $nilai2 and $nilai2 < $nilai3) {
            $kelipatan = 0;
            while ($kelipatan <= $nilai1) {
              $kelipatan += $nilai2;
            }
            if ($kelipatan > $nilai3) {
              $hasil = $nilai3 - $nilai1;
            } else {
              if ((fmod($nilai1, $nilai2)) != 0) {
                $hasil = $nilai2 - (fmod($nilai1, $nilai2)); //fmod() -> nilai 1 % nilai 2
              }
            }
          } elseif ($nilai1 < $nilai2 and $nilai2 < $nilai3) {
            $hasil = $nilai2 - $nilai1;
          } elseif ($nilai1 < $nilai2 and $nilai2 > $nilai3) {
            $hasil = $nilai3 - $nilai1;
          } elseif ($nilai2 == $nilai3) {
            $hasil = $nilai3 - $nilai1;
          } else {
            $hasil = 0;
          }
        } else {
          $hasil = 0;
        }

        $checked  = "";
        $isiStock = "'" . $value->id . "', '" . $QtyStandart . "', '" . $stock . "', '" . $hasil . "'";
        if ($value->StatusPR == 1) {
          $checked = "'1'";
          $pro = '<input type="checkbox" class="chk" name="item" id="ceklis' . $value->id . '" value="' . $isiStock . ', ' . $checked . '" onclick="ceklis(' . $isiStock . ')" checked="checked">';
          //$pro = '<input type="checkbox" name="checkGroup" id="ceklis' . $value->id . '" value="' . $value->StatusPR . '" onclick="ceklis(' . $isiStock . ')" checked="checked">';
        } else {
          $checked = "'0'";
          $pro = '<input type="checkbox" class="chk" name="item" id="ceklis' . $value->id . '" value="' . $isiStock . ', ' . $checked . '" onclick="ceklis(' . $isiStock . ')">';
          //$pro = '<input type="checkbox" name="checkGroup" id="ceklis' . $value->id . '" value="' . $value->StatusPR . '" onclick="ceklis(' . $isiStock . ')" >';
        }

        if ($hasil < 0) {
          $pro1 = '<td class="text-right text-danger ">' . number_format($hasil, 4) . '</td>';
        } else {
          $pro1 = '<td class="text-right ' . $coret . '">' . number_format($hasil, 4) . '</td>';
        }

        $text .= '<tr>
                    <td class="text-right ' . $coret . '">' . $no++ . '</td>
                    <td>' . $pro . '</td>
                    <td class="' . $coret . '">' . $value->PartID . '</td>
                    <td class="' . $coret . '">' . $value->PartName . '</td>
                    <td class="text-right ' . $coret . '">' . number_format($value->Qty, 4) . '</td>
                    <td class="text-right ' . $coret . '">' . number_format(floatval($QtyStandart), 4) . '</td>
                    <td class="text-right ' . $coret . '">' . number_format($stock, 4) . '</td>' . $pro1 . '
                    <td class="' . $coret . '">' . $value->UnitID . '</td>
                    <td class="' . $coret . '">' . $value->LocationID . '</td>
                  </tr>';
      }

      echo json_encode(
        array(
          "status_code" => 200,
          "status"      => "success",
          "message"     => "sukses menampilkan data",
          "jumlah_data" => count($result),
          "data"        => $result,
          "html"        => $text
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 409,
          "status"      => "error",
          "message"     => "data blm ada",
          "jumlah_data" => 0,
          "data"        => array(),
          "html"        => '<h3>Data tidak ada</h3>'
        )
      );
    }
  }

  public function ceklis_update_OLD()
  {
    $id         = $this->input->post('id');
    $value      = $this->input->post('value');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $isi_ceklis = $value == 0 ? '1' : '0';
    $data = array(
      'StatusPR' => $isi_ceklis,
      'CreateByPR' => $this->session->userdata('user_name'),
      'CreateDatePR' => date('Y-m-d H:i:s')
    );

    $second_DB->where('id', $id);
    $second_DB->update('tbl_monitoring_mpr_detail', $data);
    $sql = "SELECT NoBukti
            FROM tbl_monitoring_mpr_detail
            WHERE id = '$id'";

    $query  = $second_DB->query($sql);
    $result = $query->row();

    echo json_encode(
      array(
        "status_code" => 200,
        "status"      => "success",
        "message"     => "sukses menampilkan data",
        "data"        => $data,
        "nobukti"     => $result->NoBukti
      )
    );
  }

  public function mpr_detail_OLD()
  {
    $nobukti    = $this->input->post('nobukti');
    $trans      = explode('/', $nobukti);
    $tahunbulan = $trans[2];
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $sql = " SELECT a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking AS QtyStd, a.StockSimpan, SUM(c.Qty) AS Stock, a.UnitID, a.LocationID, a.Keterangan, a.StatusPR
             FROM tbl_monitoring_mpr_detail a
             LEFT JOIN tbl_standart_packing b ON b.PartID = a.PartID AND b.location = a.LocationID
             LEFT JOIN Buku_Stock$tahunbulan c ON c.PartID = a.PartID AND c.locationID = a.LocationID  AND c.nobukti <> a.nobukti AND replace(convert(char(10),c.Tgl,20),'-','') + replace(convert(char(12),c.CreateDate,14),':','') < (SELECT MAX(TANGGAL) FROM (SELECT  replace(convert(char(10),CreateDate,20),'-','') + replace(convert(char(12),CreateDate,14),':','') AS TANGGAL FROM buku_stock$tahunbulan  where NoBukti='$nobukti' GROUP BY CreateDate) TGL)
             WHERE a.NoBukti = '$nobukti'
             GROUP BY a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking, a.StockSimpan, a.UnitID, a.LocationID, a.Keterangan, a.StatusPR
             ORDER BY a.LocationID DESC";

    $query  = $second_DB->query($sql);
    $result = $query->result();
    $no     = 1;

    if (count($result) > 0) {
      $text = '';
      foreach ($result as $key => $value) {
        $coret = $value->StatusPR == 1 ? 'class_coret' : '';
        $pro = '';

        if ($value->StockSimpan == 0) {
          $stock = $value->Stock;
        } else {
          $stock = $value->StockSimpan;
        }

        if ($value->QtyStd == null) {
          $QtyStandart = 0;
        } else {
          $QtyStandart = $value->QtyStd;
        }

        $nilai1 = $value->Qty; // nilai 1 itu qty ppic
        $nilai2 = $QtyStandart; // nilai 2 itu standart qty
        $nilai3 = $stock; // nilai 3 itu stok
        $hasil  = 0;

        if ($nilai2 != null) {
          if ($nilai1 > $nilai2 and $nilai2 < $nilai3) {
            $kelipatan = 0;
            while ($kelipatan <= $nilai1) {
              $kelipatan += $nilai2;
            }
            if ($kelipatan > $nilai3) {
              $hasil = $nilai3 - $nilai1;
            } else {
              if ((fmod($nilai1, $nilai2)) != 0) {
                $hasil = $nilai2 - (fmod($nilai1, $nilai2)); //fmod() -> nilai 1 % nilai 2
              }
            }
          } elseif ($nilai1 < $nilai2 and $nilai2 < $nilai3) {
            $hasil = $nilai2 - $nilai1;
          } elseif ($nilai1 < $nilai2 and $nilai2 > $nilai3) {
            $hasil = $nilai3 - $nilai1;
          } elseif ($nilai2 == $nilai3) {
            $hasil = $nilai3 - $nilai1;
          } else {
            $hasil = 0;
          }
        } else {
          $hasil = 0;
        }

        $isiStock = "'" . $value->id . "', '" . $QtyStandart . "', '" . $stock . "', '" . $hasil . "'";
        if ($value->StatusPR == 1) {
          $pro = '<input type="checkbox"  name="ceklis" id="ceklis' . $value->id . '" value="' . $value->StatusPR . '" onclick="ceklis(' . $isiStock . ')" checked="checked">';
        } else {
          $pro = '<input type="checkbox"  name="ceklis" id="ceklis' . $value->id . '" value="' . $value->StatusPR . '" onclick="ceklis(' . $isiStock . ')" >';
        }

        if ($hasil < 0) {
          $pro1 = '<td class="text-right text-danger">' . number_format($hasil, 4) . '</td>';
        } else {
          $pro1 = '<td class="text-right ">' . number_format($hasil, 4) . '</td>';
        }


        $text .= '
        <div class="to-do-label">
          <div class="checkbox-fade fade-in-primary">
            <label class="check-task">' . $pro . '
              <span class="cr">
              <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
              </span>
              <span class="task-title-sp ' . $coret . '">' . $value->PartName . '</span>
            </label>
          </div>
        </div>
        <table class="table table-striped table-responsive">
          <thead>
            <tr>
              <th>No.</th>
              <th>PartID</th>
              <th>Qty</th>
              <th>Standart Packing</th>
              <th>Stock</th>
              <th>Qty Sisa Produksi</th>
              <th>Unit ID</th>
              <th>Location ID</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>' . $no++ . '</td>
              <td>' . $value->PartID . '</td>
              <td class="text-right">' . number_format($value->Qty, 4) . '</td>
              <td class="text-right">' . number_format(floatval($QtyStandart), 4) . '</td>
              <td class="text-right">' . number_format($stock, 4) . '</td>' . $pro1 . '
              <td>' . $value->UnitID . '</td>
              <td>' . $value->LocationID . '</td>
            </tr>
          </tbody>
        </table>
        <br>
        ';
      }

      echo json_encode(
        array(
          "status_code" => 200,
          "status"     => "success",
          "message"   => "sukses menampilkan data",
          "data"       => $result,
          "html"       => $text
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 409,
          "status"     => "error",
          "message"   => "data blm ada",
          "data"       => array(),
          "html"       => '<h3>Data tidak ada</h3>'
        )
      );
    }
  }

  public function monitoring_mpr_list_new()
  {

    $draw       = intval($this->input->get("draw"));
    $start      = intval($this->input->get("start"));
    $length     = intval($this->input->get("length"));
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $start_date = $this->input->post('start_date');
    $end_date   = $this->input->post('end_date');
    $pilih_pr   = $this->input->post('pilih_pr');

    ($pilih_pr == 'All') ? $where = '' : $where      = "AND pilih_wh='$pilih_pr'";

    $sql = "SELECT jumlah_mpr ,A.NoBukti, A.JobDate, A.PartID, A.PartName, A.Qty, A.Keterangan, A.CreateBy, A.CreateDate, A.loc_id
            FROM tbl_monitoring_mpr A
            LEFT JOIN (SELECT NoBukti, COUNT(NoBukti) AS jumlah_mpr FROM tbl_monitoring_mpr GROUP BY NoBukti ) C ON A.NoBukti = C.NoBukti
            WHERE CAST(CreateDate AS date) BETWEEN '$start_date' AND '$end_date'  AND loc_id='WH002'
            $where
            ORDER BY CreateDate DESC";

    $query        = $second_DB->query($sql);
    $result       = $query->result();
    $data         = [];
    $no           = 1;

    foreach ($result as $key => $value) {
      //GET JUMLAH MPR DETAIL
      $sql_det    = $second_DB->query("SELECT NoBukti FROM tbl_monitoring_mpr_detail 
                                       WHERE NoBukti = '$value->NoBukti'");
      $query_det  = $sql_det->result();
      $data_det   = count($query_det);

      $noBuktiMpr = "'" . $value->NoBukti . "', $data_det";
      $jumlah_mpr = $value->jumlah_mpr == null ? 0 : $value->jumlah_mpr;
      $status     = '';

      if ($jumlah_mpr == 4) {
        $status = '<span class="badge badge-success" style="font-size: 14px;">COMPLETE</span>
                    <br>
                    <span style="font-size: 12px;">' . $jumlah_mpr . ' dari 4 </span>';
      } else {
        $status = '<span class="badge badge-danger" style="font-size: 14px;">OPEN</span>
                    <br>
                    <span style="font-size: 12px;">' . $jumlah_mpr . ' dari 4 </span>';
      }

      $data[] = array(
        $no++,
        '<button id="btn_comment_' . $key . '" type="button" onclick="open_modal_comment(' . $noBuktiMpr . ')"  class="btn btn-secondary btn-sm" title="Tambah dan Lihat Catatan">
          <i class="fa fa-solid fa-comment fa-lg"></i>
        </button>',
        '<button class="btn btn-info btn-block text-white btn-sm" onclick="mpr_detail(' . $noBuktiMpr . ')">' . $value->NoBukti . '</button>',
        substr($value->JobDate, 0, -4),
        $value->PartID,
        $value->PartName,
        $value->Qty,
        $value->Keterangan,
        $value->CreateBy,
        substr($value->CreateDate, 0, -4),
        $status,
        '<button class="btn btn-warning btn-block text-white btn-sm" onclick="terima_mpr_produksi(' . $noBuktiMpr . ')">TERIMA</button>',
        '<button class="btn btn-danger btn-block text-white btn-sm" onclick="lihat_status(' . $noBuktiMpr . ')">DETAILS STATUS</button>'
      );
    }

    $result = array(
      "draw"             => $draw,
      "recordsTotal"     => $query->num_rows(),
      "recordsFiltered" => $query->num_rows(),
      "data"             => $data
    );

    echo json_encode($result);
  }

  public function terima_mpr_produksi()
  {
    $nobukti      = $this->input->post('nobukti');
    $loc_id       = $this->input->post('loc_id');

    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $sql          = " SELECT NoBukti, JobDate, PartID, PartName, Qty, Keterangan, pilih_wh
                      FROM tbl_monitoring_mpr 
                      WHERE NoBukti = '$nobukti'";

    $query        = $second_DB->query($sql);
    $result       = $query->row(); //gunakan row untuk 1 data saja
    $data         = [];

    $NoBukti      = $result->NoBukti; //gunakan row untuk 1 data saja
    $JobDate      = substr($result->JobDate, 0, -4);
    $PartID       = $result->PartID;
    $PartName     = $result->PartName;
    $Qty          = $result->Qty;
    $Keterangan   = $result->Keterangan;
    $pilih_wh     = $result->pilih_wh;

    $data = array(
      'NoBukti' => $NoBukti,
      'JobDate' => $JobDate,
      'PartID' => $PartID,
      'PartName' => $PartName,
      'Qty' => (int)$Qty,
      'Keterangan' => $Keterangan,
      'CreateBy' => $this->session->userdata('user_name'),
      'CreateDate' => date('Y-m-d H:i:s'),
      'loc_id' => $loc_id,
      'pilih_wh' => $pilih_wh

    );

    $query_wh1  = $second_DB->query("SELECT * FROM tbl_monitoring_mpr 
                                     WHERE NoBukti = '$NoBukti' AND loc_id = 'WH002'");
    $cek_wh1    = $query_wh1->num_rows();

    if ($cek_wh1 == 1) {
      $query    = $second_DB->query("SELECT * FROM tbl_monitoring_mpr 
                                     WHERE NoBukti = '$NoBukti' AND loc_id = 'PR001'");
      $cek      = $query->num_rows();

      if ($cek == 0) {
        $second_DB->trans_start();
        $insert = $second_DB->insert('tbl_monitoring_mpr', $data);
        $second_DB->trans_complete();

        if ($second_DB->trans_status() === FALSE) {
          echo json_encode(
            array(
              "status_code"   => 400,
              "status"        => "error",
              "message"       => "MPR gagal diterima!",
              "data"          => $data
            )
          );
        } else {
          echo json_encode(
            array(
              "status_code"   => 200,
              "status"        => "success",
              "message"       => "MPR sukses diterima!",
              "data"          => $data
            )
          );
        }
      } else {
        echo json_encode(
          array(
            "status_code"   => 409,
            "status"        => "success",
            "message"       => "MPR sudah diterima!",
            "data"          => $data
          )
        );
      }
    } else {
      echo json_encode(
        array(
          "status_code" => 405,
          "status"       => "error",
          "message"     => "MPR blm dikirim Warehouse!",
          "data"         => $data
        )
      );
    }
  }
}
