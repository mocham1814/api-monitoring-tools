<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengembalian_model extends CI_Model {


	public function get_pengembalian()
	{

		$query = $this->db->query("SELECT f.id as id_peminjaman, a.id,  CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, CONCAT(f.tanggal, ' - ', f.jam) as tgl_jam_pinjam,  d.nama as nama_tools
                                   , CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, c.nama as nama_teknisi 
		                           FROM pengembalian_tools a 
                                   LEFT JOIN peminjaman_tools_detail b ON b.id = a.id_peminjaman_detail
                                   LEFT JOIN peminjaman_tools f ON f.id = b.id_peminjaman
								   LEFT JOIN karyawan c ON c.id = f.id_teknisi
                                   LEFT JOIN tools d ON d.id = b.id_tools
                                   LEFT JOIN rak_tools e ON e.id = d.id_rak WHERE a.aktif = 1
                                    ORDER BY a.tanggal ASC ")->result();
		return $query;
	}

	public function get_peminjaman_all()
	{

		$query = $this->db->query("SELECT * FROM (SELECT f.id as id_pengembalian, d.nama as nama_karyawan, c.nama as nama_tools, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note
									, CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, f.aktif
		                           FROM peminjaman_tools a 
								   LEFT JOIN peminjaman_tools_detail b ON b.id_peminjaman = a.id 
								   LEFT JOIN tools c ON c.id = b.id_tools
								   LEFT JOIN karyawan d ON d.id = a.user_input 
								   LEFT JOIN rak_tools e ON e.id = c.id_rak
								   LEFT JOIN pengembalian_tools f on f.id_peminjaman_detail = b.id 
								   WHERE a.aktif = 1 ) a1 WHERE a1.id_pengembalian IS NULL AND a1.aktif = 1 ORDER BY a1.tgl_jam ASC")->result();
		return $query;
	}


	public function get_peminjamanById($id){


		$query = $this->db->query("SELECT * FROM peminjaman_tools WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

    }

    public function get_peminjaman_detail($id){
        $query = $this->db->query("SELECT *, CONCAT(c.kode_rak, '', c.nomor_rak) as kode_rak FROM peminjaman_tools_detail a 
                                    LEFT JOIN tools b ON b.id = a.id_tools
                                    LEFT JOIN rak_tools c ON c.id = b.id_rak WHERE id_peminjaman='$id'")->result();
			
		return $query;
    }
    
    public function get_peminjamanTools($id){


		$query = $this->db->query("SELECT a1.id , CONCAT(a1.kode_rak, ' ', a1.nama_tools) as nama FROM (
            SELECT CONCAT(c.kode_rak, '', c.nomor_rak) as kode_rak , a.id_tools as id, b.nama as nama_tools FROM peminjaman_tools_detail a 
            LEFT JOIN  tools b ON b.id = a.id_tools 
            LEFT JOIN rak_tools c ON c.id = b.id_rak
            WHERE id_peminjaman = '$id' ) as a1")->result();
			
		return $query;

	}


	public function delete_pengembalian($id)
	{	
	
		$this->db->query("UPDATE pengembalian_tools SET aktif=0 WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}

	public function post_pengembalian($params)
	{

            $id_peminjaman_detail = $params['id_peminjaman_detail'];

            $sql_cek = "SELECT * FROM pengembalian_tools WHERE id_peminjaman_detail ='$id_peminjaman_detail'";

            $jml = $this->db->query($sql_cek)->num_rows();

            if($jml>0){

                $data2['user_input'] = $params['user_input'];
                $data2['tanggal'] = $params['tanggal'];
                $data2['jam'] = $params['jam'];
                $data2['date_input'] = date('Y-m-d H:i:s');
                $data2['aktif'] = 1;

                $this->db->where('id_peminjaman_detail', $id_peminjaman_detail)->update('pengembalian_tools', $data2);


            }else{

                $data['user_input'] = $params['user_input'];
                $data['tanggal'] = $params['tanggal'];
                $data['jam'] = $params['jam'];
                $data['id_peminjaman_detail'] = $params['id_peminjaman_detail'];
                $data['date_input'] = date('Y-m-d H:i:s');
                $data['aktif'] = 1;

                $this->db->insert('pengembalian_tools', $data);
            }

            

		return array ('status'=> 200, 'message' =>'Data has been inserted', 'id_peminjaman' => $nextKode);
    }

    public function post_pengembalian_all($params)
	{

            $id_teknisi = $params['id_teknisi'];

            $sql_cek = $this->db->query("SELECT a.id FROM peminjaman_tools_detail a LEFT JOIN peminjaman_tools b ON b.id = a.id_peminjaman 
                        WHERE b.id_teknisi ='$id_teknisi'")->result();

            

                foreach($sql_cek as $row){
                    
                    $id_peminjaman_detail =  $row->id;

                    $sql_cek = "SELECT * FROM pengembalian_tools WHERE id_peminjaman_detail ='$id_peminjaman_detail'";

                    $jml = $this->db->query($sql_cek)->num_rows();

                    if($jml>0){

                        

                        $data2['user_input'] = $params['user_input'];
                        $data2['tanggal'] = $params['tanggal'];
                        $data2['jam'] = $params['jam'];
                        $data2['date_input'] = date('Y-m-d H:i:s');
                        $data2['aktif'] = 1;

                        $this->db->where('id_peminjaman_detail', $id_peminjaman_detail)->update('pengembalian_tools', $data2);


                    }else{

                            $data['user_input'] = $params['user_input'];
                            $data['tanggal'] = $params['tanggal'];
                            $data['jam'] = $params['jam'];
                            $data['id_peminjaman_detail'] = $row->id;
                            $data['date_input'] = date('Y-m-d H:i:s');
                            $data['aktif'] = 1;

                            $this->db->insert('pengembalian_tools', $data);

                    }


            }

		    return array ('status'=> 200, 'message' =>'Data has been inserted', 'id_peminjaman' => $nextKode);
    }
    

	public function update_peminjaman($id, $data)
	{	
		$this->db->where('id', $id)->update('peminjaman_tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

}
