<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_sisa_mpr extends CI_Controller {

	public function __construct() {
    parent::__construct();
    
    //start tidak usah di ubah
    $this->load->helper(array('url', 'form', 'cookie'));
    $this->load->library(array('session', 'cart'));

    $this->load->model('auth_model', 'auth');
    if($this->auth->isNotLogin());

    //START ADD THIS FOR USER ROLE MANAGMENT
		$this->contoller_name = $this->router->class;
    $this->function_name 	= $this->router->method;
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
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);
		if($check_permission->num_rows() == 1){
			$data['group_halaman'] 	= "WAREHOUSE";
			$data['nama_halaman'] 	= "WAREHOUSE SISA MPR";
			$data['icon_halaman'] 	= "icon-airplay";
			$data['perusahaan'] 		= $this->perusahaan->get_details();

			//ADDING TO LOG
			$log_url 		= base_url().$this->contoller_name."/".$this->function_name;
			$log_type 	= "VIEW";
			$log_data 	= "";
			
			log_helper($log_url, $log_type, $log_data);
			//END LOG
			$this->load->view('adminx/warehouse/report_sisa_mpr', $data, FALSE);
		} else {
			redirect('errorpage/error403');
		}
	}

  public function report_sisa_mpr_list()
	{

		$draw 			= intval($this->input->get("draw"));

    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $start_date = $this->input->post('start_date');
    $end_date 	= $this->input->post('end_date');

    $sql = "SELECT * FROM tbl_monitoring_mpr_detail 
            WHERE CAST(CreatedateWH AS date) BETWEEN '$start_date' AND '$end_date'  AND QtySisaProduksi > 0";


    $query 				= $second_DB->query($sql);
    $result 			= $query->result();
    $data 				= [];
    $no 					= 1;
  
    
    foreach ($result as $key => $value) {

    if ($value->StatusWH == 1){
      $ceklisStatus = "Proses By";
    }else{
      $ceklisStatus = "unProses By";
    }
      $data[] = array(
        $no++,
        $value->NoBukti,
        $value->PartID,
        $value->PartName,
        number_format($value->Qty,4),
        number_format($value->StandartPacking,4),
        number_format($value->StockSimpan,4),
        number_format($value->QtySisaProduksi,4),
        $value->UnitID,
        $value->LocationID,
        $ceklisStatus,
        $value->CreatebyWH,
        substr($value->CreatedateWH,0,19)
      );
    }

    $result = array(
      "draw" 						=> $draw,
      "recordsTotal" 		=> $query->num_rows(),
      "recordsFiltered" => $query->num_rows(),
      "data" 						=> $data
    );

    echo json_encode($result);
    exit();
	}

  public function report_sisa_mpr_list_new()
	{

		$draw 			= intval($this->input->get("draw"));

    $second_DB  = $this->load->database('bjsmas01_db', TRUE);

    $start_date = $this->input->post('start_date');
    $end_date 	= $this->input->post('end_date');

    $pilih_wh   = $this->input->post('pilih_wh');

    ($pilih_wh == 'All') ? $where = '' : $where = "AND LocationID='$pilih_wh'" ;

    // $sql = "SELECT NoBukti, LocationID,  CAST(CreatedateWH AS date) as Tgl_transaksi FROM tbl_monitoring_mpr_detail 
    //         WHERE CAST(CreatedateWH AS date) BETWEEN '$start_date' AND '$end_date'  AND QtySisaProduksi > 0
    //         GROUP BY NoBukti, LocationID,  CAST(CreatedateWH AS date)
    //         ";
    $sql = "SELECT NoBukti, LocationID,  MIN(CAST(CreatedateWH AS date)) as Tgl_transaksi FROM tbl_monitoring_mpr_detail 
            WHERE CAST(CreatedateWH AS date)  BETWEEN '$start_date' AND '$end_date'  AND QtySisaProduksi > 0  $where
            GROUP BY NoBukti, LocationID
            HAVING COUNT(*) > 0
            ";

    // echo $sql;exit();

    $query 				= $second_DB->query($sql);
    $result 			= $query->result();
    $data 				= [];
    $no 					= 1;
  
    
    foreach ($result as $key => $value) {
      $data[] = array(
        $no++,
        $value->NoBukti,
        $value->LocationID,
        $value->Tgl_transaksi,
        '<a href="' . base_url() . 'warehouse_sisa_mpr/report_sisa_mpr_details/'. base64_encode($value->NoBukti).'" target="_blank"><button class="btn btn-danger btn-block btn-sm">DETAILS</button></a>'
      );
    }

    $result = array(
      "draw" 						=> $draw,
      "recordsTotal" 		=> $query->num_rows(),
      "recordsFiltered" => $query->num_rows(),
      "data" 						=> $data
    );

    echo json_encode($result);
    exit();
	}

  public function report_sisa_mpr_details($NoBukti)
	{
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {
			$data['group_halaman'] 	= "WAREHOUSE";
			$data['nama_halaman'] 	= "Monitoring MPR Details";
			$data['icon_halaman'] 	= "icon-airplay";
			$data['perusahaan'] 		= $this->perusahaan->get_details();
			$data['NoBukti'] 				= base64_decode($NoBukti);
      // echo $data['NoBukti'];exit;
			// $data['bulan'] 					= $bulan;
			// $data['tahun'] 					= $tahun;
			//ADDING TO LOG
			$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
			$log_type 	= "VIEW";
			$log_data 	= "";

			log_helper($log_url, $log_type, $log_data);
			//END LOG
			$this->load->view('adminx/warehouse/monitoring_mpr_details', $data, FALSE);
		} else {
			redirect('errorpage/error403');
		}
	}

	public function monitoring_sisa_mpr_details_list()
	{
		$draw 			= intval($this->input->get("draw"));
		$start 			= intval($this->input->get("start"));
		$length 		= intval($this->input->get("length"));

		$NoBukti 		= $this->input->post('no_bukti');

		$second_DB  = $this->load->database('bjsmas01_db', TRUE);

		$sql 		= " SELECT * FROM tbl_monitoring_mpr_detail 
                WHERE NoBukti='$NoBukti ' AND QtySisaProduksi > 0"; 

		$query 				= $second_DB->query($sql);
		$result 			= $query->result();
		$data 				= [];
		$no 					= 1;

		foreach ($result as $key => $value) {

		
      $query 				= $second_DB->query($sql);
      $result 			= $query->result();
      $data 				= [];
      $no 					= 1;
    
      
      foreach ($result as $key => $value) {
  
      if ($value->StatusWH == 1){
        $ceklisStatus = "Proses By";
      }else{
        $ceklisStatus = "unProses By";
      }
        $data[] = array(
          $no++,
          $value->PartID,
          $value->PartName,
          number_format($value->Qty,4),
          number_format($value->StandartPacking,4),
          number_format($value->StockSimpan,4),
          number_format($value->QtySisaProduksi,4),
          $value->UnitID,
          $value->LocationID,
          $ceklisStatus,
          $value->CreatebyWH,
          substr($value->CreatedateWH,0,19)
        );
		}

		$result = array(
			"draw" 						=> $draw,
			"recordsTotal" 		=> $query->num_rows(),
			"recordsFiltered" => $query->num_rows(),
			"data" 						=> $data
		);

		echo json_encode($result);
		exit();
	  }

  }
}


