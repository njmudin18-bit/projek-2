<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produksi extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(array('url', 'form', 'cookie'));
		$this->load->library(array('session', 'cart'));

		$this->load->model('auth_model', 'auth');
		if ($this->auth->isNotLogin());

		//START ADD THIS FOR USER ROLE MANAGMENT
		$this->contoller_name = $this->router->class;
		$this->function_name 	= $this->router->method;
		$this->load->model('Rolespermissions_model');
		//END

		$this->load->model('Dashboard_model');
		$this->load->model('perusahaan_model', 'perusahaan');
		$this->load->model('barcode_model', 'barcode_sales');
	}

	public function index()
	{
		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {
			$data['group_halaman'] 	= "Produksi";
			$data['nama_halaman'] 	= "Scan Barcode";
			$data['icon_halaman'] 	= "icon-airplay";
			$data['perusahaan'] 		= $this->perusahaan->get_details();

			//ADDING TO LOG
			$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
			$log_type 	= "VIEW";
			$log_data 	= "";

			log_helper($log_url, $log_type, $log_data);
			//END LOG

			$this->load->view('adminx/produksi/scan_barcode', $data, FALSE);
		} else {
			redirect('errorpage/error403');
		}
	}

	public function cek_barcode()
	{
		$barcode_no = $this->input->post('barcode_no');

		$second_DB  = $this->load->database('bjsmas01_db', TRUE);
		//CEK DAHULU APAKAH SUDAH ADA
		$query 			= $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																		 WHERE barcode_no = '$barcode_no' AND loc_id = 'PR001'");
		$cek 				= $query->num_rows();
		if ($cek == 0) {
			echo json_encode(
				array(
					"status_code" => 200,
					"status" 			=> "success",
					"message" 		=> "Barcode " . $barcode_no . " belum discan!",
				)
			);
		} else {
			echo json_encode(
				array(
					"status_code" => 500,
					"status" 			=> "error",
					"message" 		=> "Barcode " . $barcode_no . " sudah discan!",
				)
			);
		}
	}

	public function save_barcode()
	{
		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {
			$barcode_no 	= $this->input->post("no_barcode");
			$status_scan 	= $this->input->post("status_scan");

			if (strlen($barcode_no) < 50) {
				echo json_encode(
					array(
						"status_code" => 400,
						"status" 			=> "error",
						"message" 		=> "Barcode " . $barcode_no . " tidak terdaftar!",
					)
				);
			} else if (strlen($barcode_no) > 50) {

				$second_DB  = $this->load->database('bjsmas01_db', TRUE);
				//CEK DAHULU APAKAH SUDAH ADA
				$query 			= $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																				 WHERE barcode_no = '$barcode_no' AND loc_id = 'PR001'");
				$cek 				= $query->num_rows();
				if ($cek == 0) {

					//EXPLODE ISI BARCODE
					$barcode_isi 	= explode('|', $barcode_no);
					$job_array 		= explode('-', $barcode_isi[1]);
					$no_job 			= substr($job_array[0], 4);

					//SET ARRAY DATA
					$data = array(
						'barcode_no' 		=> $barcode_no,
						'no_job' 				=> $no_job,
						'loc_id' 				=> "PR001",
						'qty_job' 			=> $barcode_isi[6],
						'qty_box' 			=> $barcode_isi[7],
						'loc_result' 		=> $barcode_isi[5],
						'scan_status' 	=> $status_scan,
						'scan_date' 		=> date('Y-m-d H:i:s'),
						'scan_by' 			=> $this->session->userdata('user_name')
					);

					//INSERT INTO TABLE
					$second_DB->trans_start();
					$insert = $second_DB->insert('tbl_scanbarcode_job', $data);
					$second_DB->trans_complete();

					if ($second_DB->trans_status() === FALSE) {
						echo json_encode(
							array(
								"status_code" => 400,
								"status" 			=> "error",
								"message" 		=> "Barcode " . $barcode_no . " gagal discan!",
								"data" 				=> $data
							)
						);
					} else {
						echo json_encode(
							array(
								"status_code" => 200,
								"status" 			=> "success",
								"message" 		=> "Barcode " . $barcode_no . " sukses discan!",
								"data" 				=> $data
							)
						);
					}

					//ADDING TO LOG
					$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
					$log_type 	= "ADD";
					$log_data 	= json_encode($data);

					log_helper($log_url, $log_type, $log_data);
					//END LOG
				} else {
					echo json_encode(
						array(
							"status_code" => 404,
							"status" 			=> "error",
							"message" 		=> "Barcode " . $barcode_no . " sudah discan!",
						)
					);
				}
			}
		} else {
			echo json_encode(array("status" => "forbidden"));
		}
	}

	public function show_barcode_data_list()
	{
		$draw 			= intval($this->input->get("draw"));
		$start 			= intval($this->input->get("start"));
		$length 		= intval($this->input->get("length"));
		$now 				= date("Y-m-d");

		$tanggal 		= $this->input->post('tanggal');
		$bulan 			= $this->input->post('bulan');
		$tahun 			= $this->input->post('tahun');
		$jenis_part = $this->input->post('jenis_part');
		$sql 				= "";

		$second_DB  = $this->load->database('bjsmas01_db', TRUE);

		// if ($tanggal == 'All') {
		// 	$sql = "SELECT * FROM tbl_scanbarcode_job
		// 					WHERE loc_id = 'PR001'
		// 					AND loc_result IN ('WH-FG', 'WH-MDN')
		// 					AND MONTH(scan_date) = '$bulan'
		// 					AND YEAR(scan_date) = '$tahun'
		// 					ORDER BY scan_id DESC";
		// } else {
		// 	$sql = "SELECT * FROM tbl_scanbarcode_job
		// 					WHERE loc_id = 'PR001'
		// 					AND loc_result IN ('WH-FG', 'WH-MDN')
		// 					AND DAY(scan_date) = '$tanggal'
		// 					AND MONTH(scan_date) = '$bulan'
		// 					AND YEAR(scan_date) = '$tahun'
		// 					ORDER BY scan_id DESC";
		// }

		$new_bulan    = 0;
		if (strlen($bulan) == 1) {
			$new_bulan  = "0" . $bulan;
		} else {
			$new_bulan  = $bulan;
		}

		$table_name   = 'Trans_Job' . $tahun . $new_bulan; //Trans_Job202301

		$sql = "SELECT 
							a.*, 
							b.PartID,
							b.Keterangan,
							c.PartName 
						FROM 
							tbl_scanbarcode_job a 
							LEFT JOIN $table_name b on b.NoBukti = a.no_job 
							LEFT JOIN Ms_Part c ON c.PartID = b.PartID 
						WHERE 
							a.loc_id = 'PR001' 
							AND a.loc_result = '$jenis_part'
							AND DAY(a.scan_date) = '$tanggal' 
							AND MONTH(a.scan_date) = '$new_bulan' 
							AND YEAR(a.scan_date) = '$tahun' 
						ORDER BY 
							a.scan_id DESC;";

		// echo $sql;
		// exit;

		$query 			= $second_DB->query($sql);
		$result 		= $query->result();
		$data 			= [];
		$no 				= 1;
		$status 		= "";
		$lokasi_1 	= "";
		$lokasi_2 	= "";

		foreach ($result as $key => $value) {
			//ambil nilai barcode
			$isi_barcode 				= explode('|', $value->barcode_no); //explode barcode
			$no_job_array 			= explode('-', $isi_barcode[1]);
			$no_job 						= substr($no_job_array[0], 4);
			$tahun_job_array 		= explode('/', $no_job);
			$tahun_job 					= $tahun_job_array[2];

			//$job_data = get_job_details($no_job, $tahun_job);

			$data[] = array(
				$no++,
				$value->scan_status == 'OK' ? '<h5><span class="badge badge-success">' . $value->scan_status . '</span></h5>' : '<h5><span class="badge badge-danger">' . $value->scan_status . '</span></h5>',
				substr($value->scan_date, 0, -4),
				$no_job, //$job_data->NoBukti, //$no_job, ,
				$value->PartName, //$job_data->PartName,
				$value->PartID, //$job_data->PartID,
				number_format($isi_barcode[6], 0),
				number_format($isi_barcode[7], 0),
				$isi_barcode[5],
				$value->Keterangan == '' ? '-' : $value->Keterangan, //$job_data->Keterangan,
				$value->barcode_no,
				$value->scan_by,
				'<button onclick="hapus_data(' . $value->scan_id . ')" class="btn btn-danger btn-block btn-sm">HAPUS</button>'
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

	//FUNCTION HAPUS DATA BARCODE
	public function delete_data()
	{
		$scan_id = $this->input->post('scan_id');

		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {

			//get data barcode
			$data_qr = $this->barcode_sales->get_barcode_by_id($scan_id); //from tbl_scanbarcode_job
			if (count($data_qr) > 0) {

				$barcode_no = $data_qr[0]->barcode_no;

				//SET ARRAY DATA
				$data = array(
					'barcode_no' 		=> $barcode_no,
					'details' 			=> json_encode($data_qr),
					'deleted_date' 	=> date('Y-m-d H:i:s'),
					'deleted_by' 		=> $this->session->userdata('user_name')
				);

				//SET DB
				$second_DB = $this->load->database('bjsmas01_db', TRUE);

				//INSERT INTO TABLE
				$second_DB->trans_start();
				$insert = $second_DB->insert('tbl_scanbarcode_job_dh', $data);
				$second_DB->trans_complete();

				if ($second_DB->trans_status() === FALSE) {
					echo json_encode(
						array(
							"status_code" => 400,
							"status" 			=> "error",
							"message" 		=> "Barcode gagal dihapus!",
							"data" 				=> $data
						)
					);
				} else {

					$delete = $second_DB->query("DELETE FROM tbl_scanbarcode_job WHERE scan_id = '$scan_id'");

					if ($delete) {
						echo json_encode(
							array(
								"status_code" => 200,
								"status" 			=> "success",
								"message" 		=> "Barcode sukses dihapus!",
								"data" 				=> $data
							)
						);
					} else {
						echo json_encode(
							array(
								"status_code" => 200,
								"status" 			=> "error",
								"message" 		=> "Barcode gagal dihapus!",
								"data" 				=> $data
							)
						);
					}
				}
			} else {
				echo json_encode(
					array(
						"status_code" => 404,
						"status" 			=> "error",
						"message" 		=> "Barcode tidak ditemukan!",
					)
				);
			}

			//ADDING TO LOG
			$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
			$log_type 	= "DELETE";
			$log_data 	= json_encode($data_qr);

			log_helper($log_url, $log_type, $log_data);
			//END LOG
		} else {
			echo json_encode(array("status" => "forbidden"));
		}
	}
}
