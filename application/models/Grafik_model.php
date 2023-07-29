<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grafik_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//GET DATA UTK DONUTS
	public function get_data()
	{
		$query 	= $this->db->query("SELECT A.nama_kategori AS label, 
																COUNT(B.id_kategori) AS value
																FROM table_kategori A
																LEFT JOIN table_project B ON B.id_kategori = A.id_kategori
																GROUP BY B.id_kategori, A.nama_kategori");
		$result = $query->result();

		return $result;
	}

	//GET DATA UTK PROGRESS
	public function get_data_status()
	{
		$query 	= $this->db->query("SELECT A.nama_project, A.id_pic, 
																A.project_url, A.project_progress, A.project_description, 
																B.nama_status, C.nama_kategori, D.nama
																FROM table_project A
																LEFT JOIN table_status B ON B.id_status = A.id_status
																LEFT JOIN table_kategori C ON C.id_kategori = A.id_kategori
																LEFT JOIN table_institusi D ON D.id_institusi = A.id_institusi
																ORDER BY A.project_progress DESC");
		$result = $query->result();

		return $result;
	}

	public function get_data_by_perusahaan()
	{
		$query 	= $this->db->query("SELECT COUNT(A.id_institusi) AS jlh_apps, B.nama
																FROM table_project A
																LEFT JOIN table_institusi B ON B.id_institusi = A.id_institusi
																GROUP BY A.id_institusi, B.nama 
																ORDER BY B.nama ASC");
		$result = $query->result();

		return $result;
	}
}