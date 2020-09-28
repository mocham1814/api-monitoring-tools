<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {


	public function get_users()
	{

		$query = $this->db->query("SELECT a.id, b.nama, a.username, a.password, CASE WHEN a.role = 1 THEN 'Full Access' ELSE 'Not Full Access' END as role
		                           FROM users a LEFT JOIN karyawan b ON b.id = a.id_karyawan WHERE a.aktif = '1' ORDER BY b.nama ASC")->result();
		return $query;
	}

	public function get_userById($id){


		$query = $this->db->query("SELECT * FROM users WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

	}


	public function delete_user($id)
	{	
	
		$this->db->query("UPDATE users SET aktif=0 WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}

	public function post_user($params)
	{
		
		$data['username'] = $params['username'];
		$data['password'] = md5($params['username']);
		$data['id_karyawan'] = $params['id_karyawan'];
		$data['role'] = $params['role'];
		$data['aktif'] = 1;

		$this->db->insert('users', $data);

		return array ('status'=> 200, 'message' =>'Data has been inserted');
	}

	public function update_user($id, $data)
	{	
		$this->db->where('id', $id)->update('users', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}


	public function update_password($id, $data)
	{	
		$this->db->where('id', $id)
				 ->update('users', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

	
}
