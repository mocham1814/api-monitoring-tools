<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rak_tools_model extends CI_Model {


	public function get_rak_tools()
	{

		$query = $this->db->query("SELECT CONCAT(kode_rak, ' ', nomor_rak) as kode_rak, id FROM rak_tools WHERE aktif = 1")->result();
		return $query;
	}

	public function get_rak_toolById($id){


		$query = $this->db->query("SELECT id, kode_rak as kode, nomor_rak as nomor FROM rak_tools WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

	}


	public function delete_rak_tools($id)
	{	
	
		$this->db->query("UPDATE rak_tools SET aktif=0 WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}

	public function post_rak_tools($params)
	{
		
		$data['kode_rak'] = $params['kode'];
		$data['nomor_rak'] = $params['nomor'];
		$data['aktif'] = 1;

		$this->db->insert('rak_tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been inserted');
	}

	public function update_rak_tools($id, $data)
	{	
		$this->db->where('id', $id)->update('rak_tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

	
}
