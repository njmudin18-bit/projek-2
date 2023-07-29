<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adminx extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 *        http://example.com/index.php/welcome
	 *    - or -
	 *        http://example.com/index.php/welcome/index
	 *    - or -
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
		if ($this->auth->isNotLogin()) ;

		//START ADD THIS FOR USER ROLE MANAGMENT
		$this->contoller_name = $this->router->class;
    $this->function_name 	= $this->router->method;
    $this->load->model('Rolespermissions_model');
    //END

    $this->load->model('Dashboard_model');
		$this->load->model('perusahaan_model', 'perusahaan');
		$this->load->model('grafik_model', 'grafik');
		$this->load->model('users_model', 'users');
	}

	//DASHBOARD
	public function index()
	{
		//CHECK FOR ACCESS FOR EACH FUNCTION
		$user_level 			= $this->session->userdata('user_level');
		$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);
		if($check_permission->num_rows() == 1){

			$data['group_halaman'] 	= "Dashboard";
			$data['nama_halaman'] 	= "Dashboard";
			$data['icon_halaman'] 	= "icon-home";
			$data['perusahaan'] 		= $this->perusahaan->get_details();

			//ADDING TO LOG
			$log_url 		= base_url().$this->contoller_name."/".$this->function_name;
			$log_type 	= "VIEW";
			$log_data 	= "";
			
			log_helper($log_url, $log_type, $log_data);
			//END LOG

			$this->load->view('adminx/dashboard', $data);
		} else {
			redirect('errorpage/error403');
		}
	}

	public function show_data_grafik()
	{
		$data_grafik = $this->grafik->get_data();
		$data_grafik_status = $this->grafik->get_data_status();
		$data_grafik_perusahaan = $this->grafik->get_data_by_perusahaan();

		echo json_encode(
			array(
				"status_code" => 200,
				"status" => "success",
				"message" => "sukses menampilkan data grafik",
				"chart_data" => $data_grafik,
				"chart_data_status" => $data_grafik_status,
				"chart_data_perusahaan" => $data_grafik_perusahaan
			)
		);
	}

	public function logout()
	{
		$id 				= $this->session->userdata('user_code');
		$data 			= $this->users->get_by_id($id);
		//ADDING TO LOG
		$log_url 		= base_url().$this->contoller_name."/".$this->function_name;
		$log_type 	= "LOGOUT";
		$log_data 	= json_encode($data);
		
		log_helper($log_url, $log_type, $log_data);
		//END LOG

		$this->session->sess_destroy();
		redirect(base_url());
	}
}
