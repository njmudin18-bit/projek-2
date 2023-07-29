<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qc extends CI_Controller
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
			$data['group_halaman'] 	= "QC";
			$data['nama_halaman'] 	= "Scan Barcode";
			$data['icon_halaman'] 	= "icon-airplay";
			$data['perusahaan'] 		= $this->perusahaan->get_details();

			//ADDING TO LOG
			$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
			$log_type 	= "VIEW";
			$log_data 	= "";

			log_helper($log_url, $log_type, $log_data);
			//END LOG

			$this->load->view('adminx/qc/scan_barcode', $data, FALSE);
		} else {
			redirect('errorpage/error403');
		}
	}

	public function cek_barcode()
	{
		$barcode_no 	= $this->input->post("barcode_no");

		$second_DB  = $this->load->database('bjsmas01_db', TRUE);
		$sql 				= "SELECT * FROM tbl_scanbarcode_job 
									 WHERE barcode_no = '$barcode_no' 
									 AND loc_id = 'PR001'";
		$query 			= $second_DB->query($sql);
		$cek 				= $query->num_rows();
		if ($cek > 0) {

			$res_cek			= $query->row();
			$status_scan 	= $res_cek->scan_status;
			//JIKA HASIL PRODUK OK
			if ($status_scan == 'OK') {

				//CEK APAKAH STATUS NYA OK ATAU NG
				$sql_2 		= "SELECT * FROM tbl_scanbarcode_job 
									 WHERE barcode_no = '$barcode_no' AND loc_id = 'QC001'";
				$query_2  = $second_DB->query($sql_2);
				$res 			= $query_2->row();
				echo json_encode(
					array(
						"status_code" => 200,
						"status" 			=> "success",
						"message" 		=> "Barcode " . $barcode_no . " sudah di scan produksi",
						"data" 				=> $res
					)
				);
			} else {
				echo json_encode(
					array(
						"status_code" => 500,
						"status" 			=> "error",
						"message" 		=> "Barcode " . $barcode_no . " ini dinyatakan NG oleh produksi",
						"data" 				=> array()
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"status_code" => 404,
					"status" 			=> "error",
					"message" 		=> "Barcode " . $barcode_no . " belum di scan produksi",
					"data" 				=> array()
				)
			);
		}
	}

	//FUNGSI MENYIMPAN PRODUK
	public function save_barcode()
	{
		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {
			$barcode_no 	= $this->input->post("no_barcode");
			$status 			= $this->input->post("status");
			$qty 					= $this->input->post("qty");
			//$status_old 	= $this->input->post("status_old");
			$penyebab 		= $this->input->post("penyebab");
			$pic_repair 	= ucwords($this->input->post("pic_repair"));

			$second_DB  	= $this->load->database('bjsmas01_db', TRUE);

			$cek_data 		= $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																				WHERE barcode_no = '$barcode_no' 
																				AND loc_id = 'QC001'");

			$res_cek 	= $cek_data->num_rows();
			if ($res_cek == 0) {
				//EXPLODE ISI BARCODE
				$barcode_isi 	= explode('|', $barcode_no);
				$job_array 		= explode('-', $barcode_isi[1]);
				$no_job 			= substr($job_array[0], 4);

				//SET ARRAY DATA
				$data = array(
					'barcode_no' 		=> $barcode_no,
					'no_job' 				=> $no_job,
					'loc_id' 				=> "QC001",
					'qty_job' 			=> $barcode_isi[6],
					'qty_box' 			=> $barcode_isi[7],
					'loc_result' 		=> $barcode_isi[5],
					'scan_status' 	=> $status,
					'scan_date' 		=> date('Y-m-d H:i:s'),
					'scan_by' 			=> $this->session->userdata('user_name')
				);

				//INSERT INTO TABLE
				$second_DB->trans_start();
				$insert 		= $second_DB->insert('tbl_scanbarcode_job', $data);
				$insert_id 	= $second_DB->insert_id();
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
			} else {
				$res 		= $cek_data->row();
				echo json_encode(
					array(
						"status_code" => 409,
						"status" 			=> "error",
						"message" 		=> "Barcode <strong style='font-weight: bolder'>" . $barcode_no . "</strong> sudah discan QC dengan status <strong style='font-weight: bolder'>" . $res->scan_status . "</strong>"
					)
				);
			}
		} else {
			echo json_encode(array("status" => "forbidden"));
		}
	}

	public function save_barcode_OLD()
	{
		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name, $this->function_name, $user_level);
		if ($check_permission->num_rows() == 1) {
			$barcode_no 	= $this->input->post("no_barcode");
			$status 			= $this->input->post("status");
			$qty 					= $this->input->post("qty");
			$status_old 	= $this->input->post("status_old");
			$penyebab 		= $this->input->post("penyebab");
			$pic_repair 	= ucwords($this->input->post("pic_repair"));

			$second_DB  = $this->load->database('bjsmas01_db', TRUE);

			if ($status_old == 'NG') {

				$data_update = array(
					'scan_status' 			=> $status,
					'scan_update_date' 	=> date('Y-m-d H:i:s'),
					'scan_update_by' 		=> $this->session->userdata('user_name')
				);

				$second_DB->trans_start();
				$array = array('barcode_no' => $barcode_no, 'loc_id' => 'QC001', 'scan_status' => 'NG');
				$second_DB->where($array);
				$second_DB->update('tbl_scanbarcode_job', $data_update);
				$second_DB->trans_complete();
				if ($second_DB->trans_status() === FALSE) {
					echo json_encode(
						array(
							"status_code" => 500,
							"status" 			=> "error",
							"message" 		=> "Barcode " . $barcode_no . " gagal di update",
							"data" 				=> $data_update
						)
					);
				} else {
					echo json_encode(
						array(
							"status_code" => 200,
							"status" 			=> "success",
							"message" 		=> "Barcode " . $barcode_no . " sukses di update",
							"data" 				=> $data_update
						)
					);
				}

				//ADDING TO LOG
				$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
				$log_type 	= "UPDATE";
				$log_data 	= json_encode($data_update);

				log_helper($log_url, $log_type, $log_data);
				//END LOG
			} else {

				//CEK DAHULU APAKAH SUDAH ADA BERDASARKAN STATUS
				if ($status == 'OK') {
					$query = $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																			WHERE barcode_no = '$barcode_no' 
																			AND loc_id = 'QC001' AND scan_status = '$status'");
				} elseif ($status == 'NG') {
					$query = $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																			WHERE barcode_no = '$barcode_no' 
																			AND loc_id = 'QC001' AND scan_status = '$status'");
				} else if ($status == 'RA') {
					$query = $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																			WHERE barcode_no = '$barcode_no' 
																			AND loc_id = 'QC001' AND scan_status = '$status'");
				}

				$cek 				= $query->num_rows();
				if ($cek == 0) {

					//EXPLODE ISI BARCODE
					$barcode_isi 	= explode('|', $barcode_no);

					//SET ARRAY DATA
					$data = array(
						'barcode_no' 		=> $barcode_no,
						'loc_id' 				=> "QC001",
						'qty_job' 			=> $barcode_isi[6],
						'qty_box' 			=> $barcode_isi[7],
						'loc_result' 		=> $barcode_isi[5],
						'scan_status' 	=> $status,
						'scan_date' 		=> date('Y-m-d H:i:s'),
						'scan_by' 			=> $this->session->userdata('user_name')
					);

					//INSERT INTO TABLE
					$second_DB->trans_start();
					$insert 		= $second_DB->insert('tbl_scanbarcode_job', $data);
					$insert_id 	= $second_DB->insert_id();
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

						//INSERT KE TABLE DETAIL JIKA STATUS NYA NG
						if ($status == 'NG') {

							//SET ARRAY DATA FOR DETAILS
							$data_det = array(
								'scan_id' 			=> $insert_id,
								'barcode_no' 		=> $barcode_no,
								'status' 				=> $status,
								'penyebab' 			=> $penyebab,
								'qty' 					=> $qty,
								'pic_repair' 		=> $pic_repair,
								'created_date' 	=> date('Y-m-d H:i:s')
							);

							$second_DB->trans_start();
							$insert_det = $second_DB->insert('tbl_scanbarcode_job_details', $data_det);
							$second_DB->trans_complete();

							if ($second_DB->trans_status() === FALSE) {
								echo json_encode(
									array(
										"status_code" => 400,
										"status" 			=> "error",
										"message" 		=> "Barcode " . $barcode_no . " gagal disimpan ke table detail",
										"data" 				=> $data_det
									)
								);
							} else {
								echo json_encode(
									array(
										"status_code" => 200,
										"status" 			=> "success",
										"message" 		=> "Barcode " . $barcode_no . " sukses discan dan di disimpan",
										"data" 				=> $data_det
									)
								);
							}
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
					}
				} else {
					echo json_encode(
						array(
							"status_code" => 409,
							"status" 			=> "error",
							"message" 		=> "Barcode " . $barcode_no . " sudah discan!"
						)
					);
				}
			}
		} else {
			echo json_encode(array("status" => "forbidden"));
		}
	}

	//FUNGSI SIMPAN STATUS "NG" KE TABLE tbl_scanbarcode_job_details
	public function insert_status_all()
	{
		$array_id 			= json_decode($this->input->post('data_scan_id'));
		$status_all 		= $this->input->post('status_all');
		$penyebab_all 	= ucfirst($this->input->post('penyebab_all'));
		$qty_all 				= $this->input->post('qty_all');
		$pic_repair_all = ucwords($this->input->post('pic_repair_all'));
		$insert_date  	= date('Y-m-d H:i:s');
		$insert_array 	= array();

		$second_DB  		= $this->load->database('bjsmas01_db', TRUE);

		//JIKA STATUS "NG" MAKA INSERT KE TABLE DETAIL
		foreach ($array_id as $key => $value) {
			$scan_id = $value->value;

			$data_barcode = $second_DB->query("SELECT * FROM tbl_scanbarcode_job WHERE scan_id = '$scan_id'")->row();
			$no_barcode 	= $data_barcode->barcode_no;
			$scan_status 	= $data_barcode->scan_status;

			//CEK DAHULU SUDAH ADA ATAU BELUM DENGAN ID HEADER DAN NO BARCODE YANG SAMA
			$data_details = $second_DB->query("SELECT * FROM tbl_scanbarcode_job_details 
																				 WHERE scan_id = '$scan_id' AND barcode_no = '$no_barcode'");
			$cek_details 	= $data_details->num_rows();
			if ($cek_details == 0) {
				$insert_array[] = array(
					'scan_id' 			=> $scan_id,
					'barcode_no' 		=> $no_barcode,
					'status' 				=> $scan_status,
					'penyebab' 			=> $penyebab_all,
					'qty' 					=> $qty_all,
					'pic_repair' 		=> $pic_repair_all,
					'created_date' 	=> $insert_date,
				);
			}
		}

		//JIKA ARRAY DATA ADA ISI
		if (count($insert_array) > 0) {
			//INSERT INTO TABLE tbl_scanbarcode_job_details
			$second_DB->trans_start();
			$insert 		= $second_DB->insert_batch('tbl_scanbarcode_job_details', $insert_array);
			$second_DB->trans_complete();

			if ($second_DB->trans_status() === FALSE) {
				echo json_encode(
					array(
						"status_code" => 400,
						"status" 			=> "error",
						"message" 		=> "Barcode gagal disimpan!",
						"data" 				=> $insert_array
					)
				);
			} else {
				echo json_encode(
					array(
						"status_code" => 200,
						"status" 			=> "success",
						"message" 		=> "Barcode sukses disimpan!",
						"data" 				=> $insert_array
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"status_code" => 204,
					"status" 			=> "success",
					"message" 		=> "Barcode sudah tersimpan",
					"data" 				=> $insert_array
				)
			);
		}
	}

	//FUNGSI SIMPAN LAGI "NG" KE TABLE tbl_scanbarcode_job_details
	public function insert_status_all_ng_more()
	{
		$array_id 			= json_decode($this->input->post('data_scan_id'));
		$status_all 		= $this->input->post('status_all');
		$penyebab_all 	= ucfirst($this->input->post('penyebab_all'));
		$qty_all 				= $this->input->post('qty_all');
		$pic_repair_all = ucwords($this->input->post('pic_repair_all'));
		$insert_date  	= date('Y-m-d H:i:s');
		$insert_array 	= array();

		$second_DB  		= $this->load->database('bjsmas01_db', TRUE);

		//JIKA STATUS "NG" MAKA INSERT KE TABLE DETAIL
		foreach ($array_id as $key => $value) {
			$scan_id = $value->value;

			$data_barcode = $second_DB->query("SELECT * FROM tbl_scanbarcode_job WHERE scan_id = '$scan_id'")->row();
			$no_barcode 	= $data_barcode->barcode_no;
			$scan_status 	= $data_barcode->scan_status;

			$insert_array[] = array(
				'scan_id' 			=> $scan_id,
				'barcode_no' 		=> $no_barcode,
				'status' 				=> $scan_status,
				'penyebab' 			=> $penyebab_all,
				'qty' 					=> $qty_all,
				'pic_repair' 		=> $pic_repair_all,
				'created_date' 	=> $insert_date,
			);
		}

		//echo json_encode($insert_array);
		//JIKA ARRAY DATA ADA ISI
		if (count($insert_array) > 0) {
			//INSERT INTO TABLE tbl_scanbarcode_job_details
			$second_DB->trans_start();
			$insert 		= $second_DB->insert_batch('tbl_scanbarcode_job_details', $insert_array);
			$second_DB->trans_complete();

			if ($second_DB->trans_status() === FALSE) {
				echo json_encode(
					array(
						"status_code" => 400,
						"status" 			=> "error",
						"message" 		=> "Barcode gagal disimpan!",
						"data" 				=> $insert_array
					)
				);
			} else {
				echo json_encode(
					array(
						"status_code" => 200,
						"status" 			=> "success",
						"message" 		=> "Barcode sukses disimpan!",
						"data" 				=> $insert_array
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"status_code" => 200,
					"status" 			=> "success",
					"message" 		=> "Barcode sudah tersimpan",
					"data" 				=> $insert_array
				)
			);
		}
	}

	//FUNGSI UPDATE STATUS "OK" KE TABLE tbl_scanbarcode_job
	public function update_status_all()
	{
		$array_id 			= json_decode($this->input->post('data_scan_id'));
		$status_all 		= $this->input->post('status_all');
		$update_date  	= date('Y-m-d H:i:s');
		$update_array 	= array();

		$second_DB  		= $this->load->database('bjsmas01_db', TRUE);

		//JIKA STATUS "NG" MAKA INSERT KE TABLE DETAIL
		foreach ($array_id as $key => $value) {
			$scan_id = $value->value;

			//CEK DAHULU SUDAH ADA ATAU BELUM DENGAN ID DAN NO BARCODE YANG SAMA
			$data_details = $second_DB->query("SELECT * FROM tbl_scanbarcode_job 
																				 WHERE scan_id = '$scan_id' AND scan_status = 'NG'");
			$cek_details 	= $data_details->num_rows();
			if ($cek_details > 0) {
				$update_array[] = array(
					'scan_id' 					=> $scan_id,
					'scan_status' 			=> $status_all,
					'scan_update_date' 	=> $update_date,
					'scan_update_by' 		=> $this->session->userdata('user_name')
				);
			}
		}

		//echo json_encode($update_array);
		//echo count($update_array);
		//JIKA DATA ARRAY ADA ISI
		if (count($update_array) > 0) {
			//INSERT INTO TABLE tbl_scanbarcode_job_details
			$second_DB->trans_start();
			$update 		= $second_DB->update_batch('tbl_scanbarcode_job', $update_array, 'scan_id');
			$second_DB->trans_complete();

			if ($second_DB->trans_status() === FALSE) {
				echo json_encode(
					array(
						"status_code" => 400,
						"status" 			=> "error",
						"message" 		=> "Barcode gagal diupdate!",
						"data" 				=> $update_array
					)
				);
			} else {
				echo json_encode(
					array(
						"status_code" => 200,
						"status" 			=> "success",
						"message" 		=> "Barcode sukses diupdate!",
						"data" 				=> $update_array
					)
				);
			}
		} else {
			echo json_encode(
				array(
					"status_code" => 200,
					"status" 			=> "success",
					"message" 		=> "Barcode sudah terupdate",
					"data" 				=> $update_array
				)
			);
		}
	}

	//FUNGSI MENAMPILKAN DATA
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
		// 					WHERE loc_id = 'QC001'
		// 					AND loc_result IN ('WH-FG', 'WH-MDN')
		// 					AND MONTH(scan_date) = '$bulan'
		// 					AND YEAR(scan_date) = '$tahun'
		// 					ORDER BY scan_id DESC";
		// } else {
		// 	$sql = "SELECT * FROM tbl_scanbarcode_job
		// 					WHERE loc_id = 'QC001'
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
							b.UnitID,
							b.Tgl,
							c.PartName 
						FROM 
							tbl_scanbarcode_job a 
							LEFT JOIN $table_name b on b.NoBukti = a.no_job 
							LEFT JOIN Ms_Part c ON c.PartID = b.PartID 
						WHERE 
							a.loc_id = 'QC001' 
							AND a.loc_result = '$jenis_part'
							AND DAY(a.scan_date) = '$tanggal' 
							AND MONTH(a.scan_date) = '$new_bulan' 
							AND YEAR(a.scan_date) = '$tahun' 
						ORDER BY 
							a.scan_id DESC;";

		$query 			= $second_DB->query($sql);
		$result 		= $query->result();
		$data 			= [];
		$no 				= 1;
		$attr 			= "";
		$isi 				= "";

		foreach ($result as $key => $value) {

			$isi = "'" . $value->scan_id . "', '" . $value->barcode_no . "', '" . $value->loc_id . "', '" . $value->scan_status . "'";

			if ($value->scan_status == 'NG') {
				$attr 	= '<span class="pointer" onclick="view_details(' . $isi . ')">' . $value->barcode_no . '</span>';
			} else {
				$attr 	= $value->barcode_no;
			}

			//ambil nilai barcode
			$isi_barcode 				= explode('|', $value->barcode_no); //explode barcode
			$no_job_array 			= explode('-', $isi_barcode[1]);
			$no_job 						= substr($no_job_array[0], 4);
			$tahun_job_array 		= explode('/', $no_job);
			$tahun_job 					= $tahun_job_array[2];

			//$job_data 					= get_job_details($no_job, $tahun_job);

			// $no_bukti 		= "";
			// if ($value->scan_status == 'NG') {
			// 	$no_bukti 	= '<a href="#" onclick="view_details(' . $isi . ')" style="font-weight: 700; font-size: 15px; color: #ff5370;">' . $job_data->NoBukti . '</a>';
			// } else {
			// 	$no_bukti 	= $job_data->NoBukti;
			// }

			$data[] = array(
				$value->scan_id,
				$no++,
				$value->scan_status == 'OK' ? '<span class="badge badge-success">' . $value->scan_status . '</span>' : '<span class="badge badge-danger pointer" onclick="view_details(' . $isi . ')">' . $value->scan_status . '</span>',
				$no_job, //$job_data->NoBukti,
				substr($value->scan_date, 0, -4),
				$value->PartID, //$job_data->PartID,
				$value->PartName, //$job_data->PartName,
				number_format($isi_barcode[6], 0), //number_format($job_data->QtyOrder, 0),
				number_format($isi_barcode[7], 0),
				$value->UnitID, //$job_data->UnitID,
				substr($value->Tgl, 0, -4), //$job_data->Tgl,
				'<span class="badge badge-warning text-white">' . $value->loc_id . '</span>',
				$value->Keterangan == '' ? '-' : $value->Keterangan, //$job_data->Keterangan,
				$value->barcode_no,
				$value->scan_by,
				$value->scan_status
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

	public function show_barcode_data_list_OLD()
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

		//JIKA PILIHAN TANGGAL TIDAK ALL DAN JENIS = ALL
		if ($tanggal != 'All' && $jenis_part == 'All') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job
							 WHERE loc_id = 'QC001'
							 AND loc_result IN ('WH-FG', 'WH-GRS00', 'WH-GRS01', 'WH-FG01')
							 AND DAY(scan_date) = '$tanggal'
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL TIDAK ALL DAN JENIS = POWER CORD
		if ($tanggal != 'All' && $jenis_part == 'Power Cord') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job 
							 WHERE loc_id = 'QC001'
							 AND loc_result IN ('WH-FG01', 'WH-GRS01')
							 AND DAY(scan_date) = '$tanggal'
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL TIDAK ALL DAN JENIS = WIRING
		if ($tanggal != 'All' && $jenis_part == 'Wiring') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job
							 WHERE loc_id = 'QC001'
							 AND loc_result IN ('WH-FG', 'WH-GRS00')
							 AND DAY(scan_date) = '$tanggal'
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL ALL DAN JENIS = ALL
		if ($tanggal == 'All' && $jenis_part == 'All') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job 
  						 WHERE loc_id = 'QC001'
  						 AND loc_result IN ('WH-FG', 'WH-GRS00', 'WH-GRS01', 'WH-FG01')
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL ALL DAN JENIS = POWER CORD
		if ($tanggal == 'All' && $jenis_part == 'Power Cord') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job 
							 WHERE loc_id = 'QC001'
							 AND loc_result IN ('WH-FG01', 'WH-GRS01')
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL ALL DAN JENIS = WIRING
		if ($tanggal == 'All' && $jenis_part == 'Wiring') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job
							 WHERE loc_id = 'QC001'
							 AND loc_result IN ('WH-FG', 'WH-GRS00')
							 AND MONTH(scan_date) = '$bulan'
							 AND YEAR(scan_date) = '$tahun'
							 ORDER BY scan_id DESC";
		}

		//JIKA PILIHAN TANGGAL ALL DAN JENIS = ALL
		if ($tanggal == 'All' && $jenis_part == 'All') {
			$sql 	= "SELECT * FROM tbl_scanbarcode_job 
  						 WHERE loc_id = 'QC001' 
  						 AND loc_result IN ('WH-FG', 'WH-GRS00', 'WH-GRS01', 'WH-FG01') 
  						 AND YEAR(scan_date) = '$tahun'
  						 ORDER BY scan_id DESC";
		}

		$query 			= $second_DB->query($sql);
		$result 		= $query->result();
		$data 			= [];
		$no 				= 1;
		$attr 			= "";
		$isi 				= "";

		foreach ($result as $key => $value) {

			$isi = "'" . $value->scan_id . "', '" . $value->barcode_no . "', '" . $value->loc_id . "', '" . $value->scan_status . "'";

			if ($value->scan_status == 'NG') {
				$attr 	= '<span class="pointer" onclick="view_details(' . $isi . ')">' . $value->barcode_no . '</span>';
			} else {
				$attr 	= $value->barcode_no;
			}

			$isi_barcode 	= explode('|', $value->barcode_no);
			$split_lagi 	= explode('-', $isi_barcode[1]);
			$no_job 			= substr($split_lagi[0], 4, 25);
			$job_data 		= get_job_details($no_job, $bulan, $tahun);

			$no_bukti 		= "";
			if ($value->scan_status == 'NG') {
				$no_bukti 	= '<a href="#" onclick="view_details(' . $isi . ')" style="font-weight: 700; font-size: 15px; color: #ff5370;">' . $job_data->NoBukti . '</a>';
			} else {
				$no_bukti 	= $job_data->NoBukti;
			}

			$data[] = array(
				$no++,
				$value->scan_status == 'OK' ? '<span class="badge badge-success">' . $value->scan_status . '</span>' : '<span class="badge badge-danger pointer" onclick="view_details(' . $isi . ')">' . $value->scan_status . '</span>',
				$no_bukti, //$job_data->NoBukti,
				substr($value->scan_date, 0, -4),
				$job_data->PartID,
				$job_data->PartName,
				number_format($job_data->QtyOrder, 0),
				number_format($isi_barcode[7], 0),
				$job_data->UnitID,
				$job_data->Tgl,
				'<span class="badge badge-warning text-white">' . $value->loc_id . '</span>',
				$job_data->Keterangan,
				$value->barcode_no,
				$value->scan_by
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

	//FUNGSI LIHAT DETAIL PRODUK NG
	public function view_product_ng()
	{
		$id = $this->input->post("scan_id");

		$second_DB  = $this->load->database('bjsmas01_db', TRUE);

		$query 			= $second_DB->query("SELECT * FROM tbl_scanbarcode_job_details 
																		 WHERE scan_id = '$id' ORDER BY id DESC");
		$cek 				= $query->num_rows();
		if ($cek > 0) {

			$header 				= $second_DB->query("SELECT * FROM tbl_scanbarcode_job WHERE scan_id = '$id'");
			$result_header 	= $header->row();
			$result 				= $query->result();

			$text 		= "";
			$no 			= 1;
			if (count($result) > 0) {

				foreach ($result as $key => $value) {
					$text .= '<tr>
		 									<td class="text-right">' . $no++ . '</td>
		 									<td class="text-left">' . $value->barcode_no . '</td>
		 									<td class="text-center"><span class="badge badge-danger">' . $value->status . '</span></td>
		 									<td class="text-right">' . number_format($value->qty) . '</td>
		 									<td class="text-left">' . $value->pic_repair . '</td>
		 									<td class="text-center">' . $value->created_date . '</td>
		 								</tr>
		 								<tr>
		 									<td colspan="6" class="text-left">PENYEBAB: ' . $value->penyebab . '</td>
		 								</tr>';
				}
			} else {
				$text .= '<tr>
	 									<td colspan="6" class="text-center">NO DATA FOUND</td>
	 								</tr>';
			}

			echo json_encode(
				array(
					"status_code" => 200,
					"status" 			=> "success",
					"message" 		=> "Barcode " . $id . " ditemukan!",
					"header" 			=> $result_header,
					"data" 				=> $result,
					"html" 				=> $text
				)
			);
		} else {
			echo json_encode(
				array(
					"status_code" => 404,
					"status" 			=> "error",
					"message" 		=> "Barcode " . $id . " tidak ditemukan!",
					"data" 				=> array()
				)
			);
		}
	}

	//FUNGSI SIMPAN JIKA ADA NG KEMBALI DI SATU PRODUK
	public function save_more_ng()
	{
		$second_DB  = $this->load->database('bjsmas01_db', TRUE);

		$data_det = array(
			'scan_id' 			=> $this->input->post('scan_id_view'),
			'barcode_no' 		=> $this->input->post('no_barcode_view'),
			'status' 				=> $this->input->post('status_view'),
			'qty' 					=> $this->input->post('qty_view'),
			'penyebab' 			=> $this->input->post('penyebab_view'),
			'pic_repair' 		=> ucwords($this->input->post('pic_repair_view')),
			'created_date' 	=> date('Y-m-d H:i:s')
		);

		$second_DB->trans_start();
		$insert_det = $second_DB->insert('tbl_scanbarcode_job_details', $data_det);
		$second_DB->trans_complete();

		if ($second_DB->trans_status() === FALSE) {
			echo json_encode(
				array(
					"status_code" => 400,
					"status" 			=> "error",
					"message" 		=> "Barcode gagal disimpan ke table detail",
					"data" 				=> $data_det
				)
			);
		} else {
			echo json_encode(
				array(
					"status_code" => 200,
					"status" 			=> "success",
					"message" 		=> "Barcode sukses di disimpan",
					"data" 				=> $data_det
				)
			);
		}

		//ADDING TO LOG
		$log_url 		= base_url() . $this->contoller_name . "/" . $this->function_name;
		$log_type 	= "UPDATE";
		$log_data 	= json_encode($data_det);

		log_helper($log_url, $log_type, $log_data);
		//END LOG
	}
}
