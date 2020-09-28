<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function post_login($username, $password){

       $sql = "SELECT a.id, b.nama, a.username FROM users a LEFT JOIN karyawan b ON b.id = a.id_karyawan WHERE a.username='$username' AND a.password='$password'";

       $query = $this->db->query($sql)->row();

       return $query;
    }

 
}