<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {


	public function get_karyawan($id_jbt)
	{

		if($id_jbt=='empty'){
			$where = "";
		}else{
			$where = " AND id_jabatan = '$id_jbt'";
		}

		$query = $this->db->query("SELECT *, CASE WHEN id_jabatan = 1 THEN 'Admin' ELSE 'Teknisi' END AS jabatan FROM karyawan WHERE aktif = '1' $where ORDER BY nama ASC")->result();
		return $query;
	}

	public function get_karyawanById($id){


		$query = $this->db->query("SELECT * FROM karyawan WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

	}


	public function delete_karyawan($id)
	{	
	
		$this->db->query("UPDATE karyawan SET aktif=0 WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}

	public function post_karyawan($params)
	{
		
		$data['nama'] = $params['nama'];
		$data['id_jabatan'] = $params['id_jabatan'];
		$data['alamat'] = $params['alamat'];
		$data['aktif'] = 1;

		$this->db->insert('karyawan', $data);

		return array ('status'=> 200, 'message' =>'Data has been inserted');
	}

	public function update_karyawan($id, $data)
	{	
		$this->db->where('id', $id)->update('karyawan', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

	
}
