<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_all()
	{
		$now 				= date("Ymd");
		$second_DB  = $this->load->database('bjsmas01_db', TRUE);

		/*$sql 				= " SELECT TOP 8 C.Namaoperator, A.Nomesin, D.Namamesin, A.qty, 
										A.Statusmesin, A.Durasi, A.DurasiOff, B.Shift
										FROM tbl_MesinDetail A
										LEFT JOIN tbl_operatormesin B ON a.Kode = B.kode
										LEFT JOIN tbl_msoperator C ON B.Nik = C.Idoperator
										LEFT JOIN tbl_msmesin D ON D.Idmesin = A.Nomesin
										WHERE CONVERT(VARCHAR(8), A.Createdate, 112) LIKE '$now'
										ORDER BY A.Createdate DESC";*/
		$sql 	= " SELECT a.Namamesin, 
							isnull(b.Nik,'') Operator, 
							isnull(c.Namamesin,'') Namamesin, 
							isnull(d.Namamold,'') Namamold, 
						  isnull(b.Job,'') Job, 
						  isnull(b.Shift,'') Shift, 
						  isnull(d.Kapasitasmold,'') Kapasitasmold, 
						  isnull(e.qty,0) qty,
						  isnull(e.Durasi,0) Durasi,
							isnull(e.DurasiOff,0) DurasiOff,
						  isnull(e.Statusmesin,'') Statusmesin,
						  isnull(f.Namaoperator,'') Namaoperator
							FROM tbl_msmesin a
							LEFT JOIN (SELECT nik, Idmesin, Idmold, Job, Shift 
													FROM tbl_operatormesin WHERE convert(VARCHAR(8),created_at,112) like '$now' AND status = 'Proses'
												) b ON a.Idmesin = b.Idmesin
							LEFT JOIN tbl_msmesin c ON b.Idmesin = c.Idmesin 
							LEFT JOIN tbl_msmold d ON b.Idmold = d.Idmold 
							LEFT JOIN tbl_msoperator f ON f.Idoperator = b.Nik
							LEFT JOIN (SELECT Kode, Nomesin, Durasi, DurasiOff, Mold, Qty, Statusmesin 
												FROM tbl_MesinDetail ) e ON a.Idmesin = e.Nomesin
							GROUP BY a.Namamesin, b.Nik, c.Namamesin, d.Namamold, 
							b.Job, b.Shift, d.Kapasitasmold, e.qty, e.Statusmesin, 
							e.Durasi, e.DurasiOff, f.Namaoperator";
		$query      = $second_DB->query($sql);
		$result 		= $query->result();

		return $result;
	}

	public function get_all_old()
	{
		$now 				= date("Ymd");
		$second_DB  = $this->load->database('bjsmas01_db', TRUE);
		$query      = $second_DB->query("SELECT TOP 8 * FROM tbl_MesinDetail 
																		 WHERE convert(char(8),Createdate,112)  = '$now'
																		 ORDER BY Createdate DESC");
		$result = $query->result();

		return $result;
	}
}