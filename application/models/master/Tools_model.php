<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools_model extends CI_Model {


	public function get_tools()
	{

		$query = $this->db->query("SELECT   a.id_kondisi,  b.id as id_rak, a.id, a.nama
		                          , CASE WHEN a.id_kondisi = 1 THEN 'Good' ELSE 'No Good' END as kondisi
								  , CONCAT(kode_rak, ' ', nomor_rak) as kode_rak
		                           FROM tools a LEFT JOIN rak_tools b ON b.id = a.id_rak WHERE a.aktif = '1' ORDER BY a.nama ASC")->result();
		return $query;
	}

	public function get_tools_2()
	{

		// $sql = "SELECT x1.id, CONCAT(x1.kode_rak, ' ', x1.nama_tools) as nama FROM
		//         (SELECT b1.id, b1.nama as nama_tools, CONCAT(c1.kode_rak, '', c1.nomor_rak) as kode_rak 
		//         FROM tools b1 LEFT JOIN rak_tools c1 ON c1.id = b1.id_rak
		
		//         WHERE NOT EXISTS (
		//   	    SELECT a.id_tools as id, b.id as id_peminjaman, b.aktif FROM peminjaman_tools_detail a 
		// 						LEFT JOIN pengembalian_tools b on b.id_peminjaman_detail = a.id
		// 						WHERE b.aktif = 0 OR b.id IS NULL AND a.id_tools = b1.id
		 
		// 	    ) ) x1";

		$sql = "SELECT x1.id, CONCAT(x1.kode_rak, ' ', x1.nama_tools) as nama FROM
		(SELECT b1.id, b1.nama as nama_tools, CONCAT(c1.kode_rak, '', c1.nomor_rak) as kode_rak 
		FROM tools b1 LEFT JOIN rak_tools c1 ON c1.id = b1.id_rak

		WHERE B1.id NOT IN (
			SELECT a.id_tools FROM peminjaman_tools_detail a 
						LEFT JOIN pengembalian_tools b on b.id_peminjaman_detail = a.id
                        LEFT JOIN peminjaman_tools c on c.id = a.id_peminjaman
						WHERE c.aktif = 1 AND b.aktif = 0 OR b.id IS NULL
 
		) ) x1";



		$query = $this->db->query($sql)->result();
		return $query;
	}

	public function get_toolById($id){


		$query = $this->db->query("SELECT * FROM tools WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

	}


	public function delete_tools($id)
	{	
	
		$this->db->query("UPDATE tools SET aktif=0 WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}

	public function post_tools($params)
	{
		
		$data['id_rak'] = $params['id_rak'];
		$data['nama'] = $params['nama'];
		$data['id_kondisi'] = $params['id_kondisi'];
		$data['aktif'] = 1;

		$this->db->insert('tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been inserted');
	}

	public function update_tools($id, $data)
	{	
		$this->db->where('id', $id)->update('tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

}
