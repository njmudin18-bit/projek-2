<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_mpr extends CI_Controller
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
      $data['group_halaman']   = "WAREHOUSE";
      $data['nama_halaman']   = "WAREHOUSE MPR";
      $data['icon_halaman']   = "icon-airplay";
      $data['perusahaan']     = $this->perusahaan->get_details();

      //ADDING TO LOG
      $log_url     = base_url() . $this->contoller_name . "/" . $this->function_name;
      $log_type   = "VIEW";
      $log_data   = "";

      log_helper($log_url, $log_type, $log_data);
      //END LOG
      $this->load->view('adminx/warehouse/monitoring_mpr_warehouse', $data, FALSE);
    } else {
      redirect('errorpage/error403');
    }
  }

  //FUNGSI CEKLIS ALL
  public function ceklis_multiple()
  {
    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $update_data  = array();
    $id_array     = array();
    $array_data   = $this->input->post('value');
    foreach ($array_data as $key => $value) {
      $array_data_lop = explode(',', str_replace("'", "", $value));
      $id_update      = $array_data_lop[0];
      $qty_std        = $array_data_lop[1];
      $qty_stock      = $array_data_lop[2];
      $qty_sisa       = $array_data_lop[3];
      $id_array[]     = $array_data_lop[0];
      $isi_ceklis     = 1;

      $update_data[] = array(
        'id'              => $id_update,
        'StandartPacking' => floatval($qty_std),
        'StockSimpan'     => floatval($qty_stock),
        'QtySisaProduksi' => floatval($qty_sisa),
        'StatusWH'        => $isi_ceklis,
        'CreateByWH'      => $this->session->userdata('user_name'),
        'CreateDateWH'    => date('Y-m-d H:i:s')
      );
    };

    //GET NOMOR BUKTI
    $no_bukti = $second_DB->query("SELECT * FROM tbl_monitoring_mpr_detail where id = '$id_array[0]'")->row()->NoBukti;

    // //UPDATE DATA
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
    $id_array     = array();
    $array_data   = $this->input->post('value');
    foreach ($array_data as $key => $value) {
      $array_data_lop = explode(',', str_replace("'", "", $value));
      $id_update      = $array_data_lop[0];
      $qty_std        = $array_data_lop[1];
      $qty_stock      = $array_data_lop[2];
      $qty_sisa       = $array_data_lop[3];
      $id_array[]     = $array_data_lop[0];
      $isi_ceklis     = 0;

      $update_data[] = array(
        'id'              => $id_update,
        'StandartPacking' => floatval($qty_std),
        'StockSimpan'     => floatval($qty_stock),
        'QtySisaProduksi' => floatval($qty_sisa),
        'StatusWH'        => $isi_ceklis,
        'CreateByWH'      => $this->session->userdata('user_name'),
        'CreateDateWH'    => date('Y-m-d H:i:s')
      );
    };

    //GET NOMOR BUKTI
    $no_bukti = $second_DB->query("SELECT * FROM tbl_monitoring_mpr_detail where id = '$id_array[0]'")->row()->NoBukti;

    // //UPDATE DATA
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

  //FUNGSI CEKLIS SATU2 BARU
  public function ceklis_update()
  {
    $id           = $this->input->post('id');
    $value        = $this->input->post('value');
    $qtyStd       = $this->input->post('qtyStd');
    $stockSimpan  = $this->input->post('stockSimpan');
    $qtySisa      = $this->input->post('qtySisa');

    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $isi_ceklis   = $value == 0 ? '1' : '0';
    $data = array(
      'StandartPacking' => floatval($qtyStd),
      'StockSimpan'     => floatval($stockSimpan),
      'QtySisaProduksi' => floatval($qtySisa),
      'StatusWH'        => $isi_ceklis,
      'CreateByWH'      => $this->session->userdata('user_name'),
      'CreateDateWH'    => date('Y-m-d H:i:s')
    );

    $second_DB->where('id', $id);
    $second_DB->update('tbl_monitoring_mpr_detail', $data);
    $sql = "SELECT NoBukti
            FROM tbl_monitoring_mpr_detail
            WHERE id = '$id'";

    $query        = $second_DB->query($sql);
    $result       = $query->row();

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

  //FUNGSI LAMA
  public function ceklis_update_OLD()
  {
    $id           = $this->input->post('id');
    $value        = $this->input->post('value');
    $qtyStd       = $this->input->post('qtyStd');
    $stockSimpan  = $this->input->post('stockSimpan');
    $qtySisa      = $this->input->post('qtySisa');

    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $isi_ceklis   = $value == 0 ? '1' : '0';
    $data = array(
      'StandartPacking' => floatval($qtyStd),
      'StockSimpan'     => floatval($stockSimpan),
      'QtySisaProduksi' => floatval($qtySisa),
      'StatusWH'        => $isi_ceklis,
      'CreateByWH'      => $this->session->userdata('user_name'),
      'CreateDateWH'    => date('Y-m-d H:i:s')
    );

    $second_DB->where('id', $id);
    $second_DB->update('tbl_monitoring_mpr_detail', $data);
    $sql = "SELECT NoBukti
            FROM tbl_monitoring_mpr_detail
            WHERE id = '$id'";

    $query         = $second_DB->query($sql);
    $result       = $query->row();

    echo json_encode(
      array(
        "status_code" => 200,
        "status"     => "success",
        "message"   => "sukses menampilkan data",
        "data"       => $data,
        "nobukti"   => $result->NoBukti
      )
    );
  }

  public function saveChangeQty()
  {
    $id             = $this->input->post('id');
    $QtyStandart     = $this->input->post('QtyStandart');
    $Unit           = $this->input->post('Unit');
    $Satuan         = $this->input->post('Satuan');

    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $data = array(
      'StandartPacking' => $QtyStandart,
      'Unit' => $Unit,
      'Satuan' => $Satuan,
      'Updateby' => $this->session->userdata('user_name'),
      'Updatedate' => date('Y-m-d H:i:s')
    );

    $second_DB->where('id', $id);
    $result = $second_DB->update('tbl_standart_packing', $data);

    if ($result > 0) {
      echo json_encode(
        array(
          "status_code" => 200,
          "status"     => "success",
          "message"   => "sukses menampilkan data",
          "data"       => $data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 404,
          "status"     => "error",
          "message"   => "data tidak ada",
          "data"       => array(),
          "html"       => '<h3>Data tidak ada</h3>'
        )
      );
    }
  }

  public function changeQty()
  {
    $PartID       = $this->input->post('partid');
    $Location     = $this->input->post('location');

    $second_DB    = $this->load->database('bjsmas01_db', TRUE);
    $sql          = "SELECT * FROM tbl_standart_packing
                    WHERE PartID='$PartID' AND Location='$Location'
                    ";

    $query         = $second_DB->query($sql);
    $result       = $query->row();
    $text         = "";

    if ($result) {

      $id           = $result->id;
      $PartID       =  $result->PartID;
      $PartName     =  $result->PartName;
      $QtyStandart  = $result->StandartPacking;
      $Unit         = $result->Unit;
      $Satuan       = $result->Satuan;
      $Location     = $result->Location;

      $data = array(
        'id' => $id,
        'PartID' => $PartID,
        'PartName' => $PartName,
        'QtyStandart' => $QtyStandart,
        'Unit' => $Unit,
        'Satuan' => $Satuan,
        'Location' => $Location
      );

      $text .= '
      <form>
        <div class="form-group">
          <label for="partid">Standart Packing</label>
          <input type="hidden" class="form-control" id="id" value="' . $id . '" readonly>
          <input type="text" class="form-control" id="partid" value="' . $PartID . '" readonly>
        </div>
        <div class="form-group">
          <label for="partname">Standart Packing</label>
          <input type="text" class="form-control" id="partname" value="' . $PartName . '" readonly>
        </div>
        <div class="form-group">
          <label for="standart_packing">Standart Packing</label>
          <input type="text" class="form-control" id="standart_packing" value="' . $QtyStandart . '">
        </div>
        <div class="form-group">
          <label for="unit">Unit</label>
          <input type="text" class="form-control" id="unit" value="' . $Unit . '">
        </div>
        <div class="form-group">
          <label for="satuan">Satuan</label>
          <input type="text" class="form-control" id="satuan" value="' . $Satuan . '">
        </div>
        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" class="form-control" id="location" value="' . $Location . '" readonly>
        </div>
        
        <button type="submit" class="btn btn-primary" onclick="saveChangeQty()" >Save</button>
      </form>
        ';

      echo json_encode(
        array(
          "status_code" => 200,
          "status"     => "success",
          "message"   => "sukses menampilkan data",
          "data"       => $data,
          "html"       => $text

        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 404,
          "status"     => "error",
          "message"   => "data tidak ada",
          "data"       => array(),
          "html"       => '<h3>Data tidak ada</h3>'
        )
      );
    }
  }

  //FUNGSI FUNGSI STD PACKING
  public function update_std_packing()
  {
    $id               = $this->input->post('id_std_packing');
    $standard_packing = $this->input->post('standard_packing');
    $unit_packing     = $this->input->post('unit_packing');
    $satuan_packing   = $this->input->post('satuan_packing');
    $second_DB        = $this->load->database('bjsmas01_db', TRUE);

    $data = array(
      'StandartPacking' => $standard_packing,
      'Unit'            => $unit_packing,
      'Satuan'          => $satuan_packing,
      'UpdateBy'        => $this->session->userdata('user_name'),
      'Updatedate'      => date('Y-m-d H:i:s'),
    );

    $second_DB->where('id', $id);
    $update = $second_DB->update('tbl_standart_packing', $data);
    if ($update) {
      echo json_encode(
        array(
          "status_code" => 200,
          "status"      => "success",
          "message"     => "Standard packing sukses di update",
          "data"        => $data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 500,
          "status"      => "error",
          "message"     => "Standard packing gagal di update",
          "data"        => array()
        )
      );
    }
  }

  //FUNGSI EDIT STD PACKING
  public function edit_std_packing()
  {
    $part_id    = $this->input->post('part_id');
    $loc_id     = $this->input->post('loc_id');
    $no_bukti   = $this->input->post('no_bukti');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $data       = $second_DB->query("SELECT * FROM tbl_standart_packing 
                                     WHERE PartID = '$part_id' AND Location = '$loc_id'");
    $count      = $data->num_rows();
    if ($count > 0) {
      $data     = $data->row();
      echo json_encode(
        array(
          "status_code" => 200,
          "status"      => "success",
          "message"     => "PartID dan Loc ID ditemukan",
          "no_bukti"    => $no_bukti,
          "data"        => $data
        )
      );
    } else {
      echo json_encode(
        array(
          "status_code" => 404,
          "status"      => "error",
          "message"     => "PartID: " . $part_id . " dan Loc ID: " . $loc_id . " tidak ditemukan",
          "no_bukti"    => "",
          "data"        => array()
        )
      );
    }
  }

  //FUNGSI MPR DETAIL
  public function mpr_detail()
  {
    $nobukti    = $this->input->post('nobukti');
    $trans      = explode('/', $nobukti);
    $tahunbulan = $trans[2];
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $sql = "SELECT a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking AS QtyStd, a.StockSimpan, SUM(c.Qty) AS Stock, a.UnitID, a.LocationID, a.Keterangan, a.StatusWH  
            FROM tbl_monitoring_mpr_detail a
            LEFT JOIN tbl_standart_packing b ON b.PartID = a.PartID AND b.location = a.LocationID
            LEFT JOIN Buku_Stock$tahunbulan c ON c.PartID = a.PartID AND c.locationID = a.LocationID  AND c.nobukti <> a.nobukti AND replace(convert(char(10),c.Tgl,20),'-','') + replace(convert(char(12),c.CreateDate,14),':','') < (SELECT MAX(TANGGAL) FROM (SELECT  replace(convert(char(10),CreateDate,20),'-','') + replace(convert(char(12),CreateDate,14),':','') AS TANGGAL FROM buku_stock$tahunbulan  where NoBukti='$nobukti' GROUP BY CreateDate) TGL)
            WHERE a.NoBukti = '$nobukti'
            GROUP BY a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking, a.StockSimpan, a.UnitID, a.LocationID, a.Keterangan, a.StatusWH
            ORDER BY a.LocationID DESC";

    $query      = $second_DB->query($sql);
    $result     = $query->result();
    $no         = 1;

    if (count($result) > 0) {
      $text = '';
      foreach ($result as $key => $value) {
        $coret  = $value->StatusWH == 1 ? 'strikeout' : '';
        $pro = '';

        if ($value->StockSimpan == 0) {
          $stock = $value->Stock;
        } else {
          $stock = $value->StockSimpan;
        }

        if ($value->QtyStd == null || $value->QtyStd == 0.0000) {
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
            while ($kelipatan < $nilai1) {
              $kelipatan += $nilai2;
            }

            if ($kelipatan < $nilai3) {
              $hasil = $kelipatan - $nilai1;
            } else  if ($kelipatan > $nilai3) {
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
        if ($value->StatusWH == 1) {
          $checked  = "'1'";
          $pro      = '<input type="checkbox" class="chk" name="item" id="ceklis' . $value->id . '" value="' . $isiStock . ', ' . $checked . '" onclick="ceklis(' . $isiStock . ')" checked="checked">';
        } else {
          $checked  = "'0'";
          $pro      = '<input type="checkbox" class="chk" name="item" id="ceklis' . $value->id . '" value="' . $isiStock . ', ' . $checked . '" onclick="ceklis(' . $isiStock . ')">';
        }

        if ($hasil < 0) {
          $pro1 = '<td class="text-right text-danger">' . number_format($hasil, 4) . '</td>';
        } else {
          $pro1 = '<td class="text-right ' . $coret . '">' . number_format($hasil, 4) . '</td>';
        }

        $changeQty = "'" . $value->PartID . "', '" . $value->LocationID . "'";
        $cekCeklisQty = "";
        if ($value->StatusWH == 0) {
          $cekCeklisQty = '<td class="text-right"><a href="#" onClick="changeQty(' . $changeQty . ')">' . number_format(floatval($QtyStandart), 4) . '</a></td>';
        } else {
          $cekCeklisQty = '<td class="text-right">' . number_format(floatval($QtyStandart), 4) . '</td>';
        }

        $isi = "'" . $value->PartID . "', '" . $value->LocationID . "', '" . $nobukti . "'";

        $text .= '<tr>
                    <td class="text-right ' . $coret . '">' . $no++ . '</td>
                    <td>' . $pro . '</td>
                    <td class="' . $coret . '">' . $value->PartID . '</td>
                    <td class="' . $coret . '">' . $value->PartName . '</td>
                    <td class="text-right ' . $coret . '">' . number_format($value->Qty, 4) . '</td>
                    <td class="text-right">
                      <button onclick="edit_std_packing(' . $isi . ')" id="item_' . $key . '" type="button" class="btn btn-danger btn-sm pull-left mr-3" title="Edit standard packing">
                        <i class="fa fa-edit"></i>
                      </button>
                      ' . number_format(floatval($QtyStandart), 4) . '
                    </td>
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
          "data"        => array(),
          "html"        => '<h3>Data tidak ada</h3>'
        )
      );
    }
  }

  //FUNGSI CEK DATA DETAILS
  public function cek_data_details()
  {
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $no_bukti   = $this->input->post('nobukti');
    $loc_id     = $this->input->post('loc_id');

    $cek_data   = "SELECT COUNT(NoBukti) AS jlh_data FROM tbl_monitoring_mpr_detail 
                   WHERE NoBukti = '$no_bukti' 
                   AND StatusWH = '1'";
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

  //FUNGSI MPR DETAIL LAMA
  public function mpr_detail_OLD()
  {
    $nobukti     = $this->input->post('nobukti');
    $trans      = explode('/', $nobukti);
    $tahunbulan = $trans[2];
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $sql = "SELECT a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking AS QtyStd, a.StockSimpan, SUM(c.Qty) AS Stock, a.UnitID, a.LocationID, a.Keterangan, a.StatusWH  
              FROM tbl_monitoring_mpr_detail a
              LEFT JOIN tbl_standart_packing b ON b.PartID = a.PartID AND b.location = a.LocationID
              LEFT JOIN Buku_Stock$tahunbulan c ON c.PartID = a.PartID AND c.locationID = a.LocationID  AND c.nobukti <> a.nobukti AND replace(convert(char(10),c.Tgl,20),'-','') + replace(convert(char(12),c.CreateDate,14),':','') < (SELECT MAX(TANGGAL) FROM (SELECT  replace(convert(char(10),CreateDate,20),'-','') + replace(convert(char(12),CreateDate,14),':','') AS TANGGAL FROM buku_stock$tahunbulan  where NoBukti='$nobukti' GROUP BY CreateDate) TGL)
              WHERE a.NoBukti = '$nobukti'
              GROUP BY a.id, a.NoBukti,  a.PartID, a.PartName,  a.Qty, b.standartpacking, a.StockSimpan, a.UnitID, a.LocationID, a.Keterangan, a.StatusWH
              ORDER BY a.LocationID DESC
                ";
    // echo $sql;exit;

    $query         = $second_DB->query($sql);
    $result       = $query->result();
    $no           = 1;

    if (count($result) > 0) {
      $text = '';
      foreach ($result as $key => $value) {
        // echo $value->QtyStd."aa";exit;
        $coret = $value->StatusWH == 1 ? 'class_coret' : '';
        $pro = '';

        if ($value->StockSimpan == 0) {
          $stock = $value->Stock;
        } else {
          $stock = $value->StockSimpan;
        }

        if ($value->QtyStd == null || $value->QtyStd == 0.0000) {
          $QtyStandart = 0;
        } else {
          $QtyStandart = $value->QtyStd;
        }

        // echo $QtyStandart."dd";exit;
        $nilai1 = $value->Qty; // nilai 1 itu qty ppic
        $nilai2 = $QtyStandart; // nilai 2 itu standart qty
        $nilai3 = $stock; // nilai 3 itu stok
        $hasil  = 0;


        if ($nilai2 != null) {
          if ($nilai1 > $nilai2 and $nilai2 < $nilai3) {
            $kelipatan = 0;
            while ($kelipatan < $nilai1) {
              $kelipatan += $nilai2;
            }

            if ($kelipatan < $nilai3) {
              $hasil = $kelipatan - $nilai1;
            } else  if ($kelipatan > $nilai3) {
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
        // echo $nilai1." ".$nilai2." ".$nilai3."<br>";
        // echo $hasil."aa";

        $isiStock = "'" . $value->id . "', '" . $QtyStandart . "', '" . $stock . "', '" . $hasil . "'";
        if ($value->StatusWH == 1) {
          $pro = '<input type="checkbox"  name="ceklis" id="ceklis' . $value->id . '" value="' . $value->StatusWH . '" onclick="ceklis(' . $isiStock . ')" checked="checked">';
        } else {
          $pro = '<input type="checkbox"  name="ceklis" id="ceklis' . $value->id . '" value="' . $value->StatusWH . '" onclick="ceklis(' . $isiStock . ')" >';
        }
        // echo $isiStock."dd";exit;
        if ($hasil < 0) {
          $pro1 = '<td class="text-right text-danger">' . number_format($hasil, 4) . '</td>';
        } else {
          $pro1 = '<td class="text-right ">' . number_format($hasil, 4) . '</td>';
        }

        // echo $pro1;exit;
        $changeQty = "'" . $value->PartID . "', '" . $value->LocationID . "'";
        $cekCeklisQty = "";
        if ($value->StatusWH == 0) {
          $cekCeklisQty = '<td class="text-right"><a href="#" onClick="changeQty(' . $changeQty . ')">' . number_format(floatval($QtyStandart), 4) . '</a></td>';
        } else {
          $cekCeklisQty = '<td class="text-right">' . number_format(floatval($QtyStandart), 4) . '</td>';
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
              <td class="text-right">' . number_format($value->Qty, 4) . '</td>' . $cekCeklisQty . '
              
              <td class="text-right">' . number_format($stock, 4) . '</td>' . $pro1 . '
              <td>' . $value->UnitID . '</td>
              <td>' . $value->LocationID . '</td>
            </tr>
          </tbody>
        </table>
        <br>
        ';
      }
      // print_r($result);exit;

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

  public function kirim_mpr_wh()
  {

    $nobukti    = $this->input->post('nobukti');
    $loc_id     = $this->input->post('loc_id');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $sql = "SELECT NoBukti, JobDate, PartID, PartName, Qty, Keterangan, pilih_wh
            FROM tbl_monitoring_mpr 
            WHERE NoBukti = '$nobukti'";

    $query         = $second_DB->query($sql);
    $result       = $query->row();
    $data         = [];

    $NoBukti    = $result->NoBukti;
    $JobDate    = substr($result->JobDate, 0, -4);
    $PartID     = $result->PartID;
    $PartName   = $result->PartName;
    $Qty        = $result->Qty;
    $Keterangan = $result->Keterangan;
    $pilih_wh   = $result->pilih_wh;

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

    $query_wh1       = $second_DB->query("SELECT * FROM tbl_monitoring_mpr 
                                        WHERE NoBukti = '$NoBukti' AND loc_id = 'WH001'");
    $cek_wh1        = $query_wh1->num_rows();

    if ($cek_wh1 == 1) {
      $query       = $second_DB->query("SELECT * FROM tbl_monitoring_mpr 
                                      WHERE NoBukti = '$NoBukti' AND loc_id = 'WH002'");
      $cek         = $query->num_rows();
      if ($cek == 0) {
        $second_DB->trans_start();
        $insert = $second_DB->insert('tbl_monitoring_mpr', $data);
        $second_DB->trans_complete();
        if ($second_DB->trans_status() === FALSE) {
          echo json_encode(
            array(
              "status_code" => 400,
              "status"       => "error",
              "message"     => "MPR gagal dikirim!",
              "data"         => $data
            )
          );
        } else {
          echo json_encode(
            array(
              "status_code" => 200,
              "status"       => "success",
              "message"     => "MPR sukses dikirim!",
              "data"         => $data
            )
          );
        }
      } else {
        echo json_encode(
          array(
            "status_code" => 409,
            "status"       => "success",
            "message"     => "MPR sudah dikirim!",
            "data"         => $data
          )
        );
      }
    } else {
      echo json_encode(
        array(
          "status_code" => 405,
          "status"       => "error",
          "message"     => "MPR blm di proses!",
          "data"         => $data
        )
      );
    }
  }

  public function proses_mpr_wh()
  {
    $nobukti     = $this->input->post('nobukti');
    $loc_id     = $this->input->post('loc_id');
    $second_DB  = $this->load->database('bjsmas01_db', TRUE);
    $sql = "SELECT NoBukti, JobDate, PartID, PartName, Qty, Keterangan, pilih_wh 
            FROM tbl_monitoring_mpr 
            WHERE NoBukti = '$nobukti'";
    $query         = $second_DB->query($sql);
    $result       = $query->row();
    $data         = [];

    $NoBukti    = $result->NoBukti;
    $JobDate    = substr($result->JobDate, 0, -4);
    $PartID     = $result->PartID;
    $PartName   = $result->PartName;
    $Qty        = $result->Qty;
    $Keterangan = $result->Keterangan;
    $pilih_wh   = $result->pilih_wh;

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

    $query       = $second_DB->query("SELECT * FROM tbl_monitoring_mpr 
                                    WHERE NoBukti = '$NoBukti' AND loc_id = 'WH001'");
    $cek         = $query->num_rows();

    if ($cek == 0) {
      $second_DB->trans_start();
      $insert = $second_DB->insert('tbl_monitoring_mpr', $data);
      $second_DB->trans_complete();
      if ($second_DB->trans_status() === FALSE) {
        echo json_encode(
          array(
            "status_code" => 400,
            "status"       => "error",
            "message"     => "MPR gagal diproses!",
            "data"         => $data
          )
        );
      } else {
        echo json_encode(
          array(
            "status_code" => 200,
            "status"       => "success",
            "message"     => "MPR sukses diproses!",
            "data"         => $data
          )
        );
      }
    } else {
      echo json_encode(
        array(
          "status_code" => 409,
          "status"       => "success",
          "message"     => "MPR sudah diproses!",
          "data"         => $data
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
    $pilih_wh   = $this->input->post('pilih_wh');

    ($pilih_wh == 'All') ? $where = '' : $where = "AND pilih_wh='$pilih_wh'";
    // echo $where;exit;

    $sql = "SELECT jumlah_mpr ,A.NoBukti, A.JobDate, A.PartID, A.PartName, A.Qty, A.Keterangan, A.CreateBy, A.CreateDate, A.loc_id
            FROM tbl_monitoring_mpr A
            LEFT JOIN (SELECT NoBukti, COUNT(NoBukti) AS jumlah_mpr FROM tbl_monitoring_mpr GROUP BY NoBukti ) C ON A.NoBukti = C.NoBukti
            WHERE CAST(CreateDate AS date) BETWEEN '$start_date' AND '$end_date'  AND loc_id='PPIC001'
            $where
            ORDER BY CreateDate DESC";
    // echo $sql;exit;

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
      //$noBuktiMpr = "'" . $value->NoBukti . "'";
      $jumlah_mpr = $value->jumlah_mpr == null ? 0 : $value->jumlah_mpr;
      $status     = '';
      if ($jumlah_mpr == 4) {
        $status   = '<span class="badge badge-success" style="font-size: 14px;">COMPLETE</span>
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
        '<button class="btn btn-warning btn-block text-white btn-sm" onclick="proses_mpr_wh(' . $noBuktiMpr . ')">PROSES</button>',
        '<button class="btn btn-primary btn-block text-white btn-sm" onclick="kirim_mpr_wh(' . $noBuktiMpr . ')">KIRIM</button>',
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
    exit();
  }
}
