<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model {


	public function get_peminjaman()
	{
		$now = DATE('Y-m-d');
		// $query = $this->db->query("SELECT b.nama, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note
		//                            FROM peminjaman_tools a 
		// 						   LEFT JOIN karyawan b ON b.id = a.id_teknisi WHERE a.aktif = 1 ORDER BY tanggal ASC")->result();

		$query = $this->db->query("SELECT *, CASE WHEN x1.jml1 = x1.jml2 THEN 'FINISH' ELSE 'PROCESS' END as sts , x1.jml2 FROM (
			SELECT b.nama, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note,
			
			( SELECT COUNT(*) FROM peminjaman_tools_detail a1 WHERE a1.id_peminjaman= a.id) as jml1 
			,
			( SELECT COUNT(*) FROM pengembalian_tools b1 LEFT JOIN peminjaman_tools_detail b2 ON b2.id = b1.id_peminjaman_detail WHERE b2.id_peminjaman= a.id AND b1.aktif = 1 ) as jml2 
											   FROM peminjaman_tools a 
											   LEFT JOIN karyawan b ON b.id = a.id_teknisi WHERE a.aktif = 1 
											   AND a.tanggal ='$now'
				) x1 ORDER BY x1.tgl_jam asc")->result();

		return $query;
	}

	public function get_peminjaman_search($tgl1, $tgl2){

		if($tgl1=='' || $tgl1==''){
			$search = '';
		}else{
			$search = " AND a.tanggal BETWEEN '$tgl1'  AND '$tgl2'";
		}

		$query = $this->db->query("SELECT *, CASE WHEN x1.jml1 = x1.jml2 THEN 'FINISH' ELSE 'PROCESS' END as sts , x1.jml2 FROM (
			SELECT b.nama, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note,
			
			( SELECT COUNT(*) FROM peminjaman_tools_detail a1 WHERE a1.id_peminjaman= a.id) as jml1 
			,
			( SELECT COUNT(*) FROM pengembalian_tools b1 LEFT JOIN peminjaman_tools_detail b2 ON b2.id = b1.id_peminjaman_detail WHERE b2.id_peminjaman= a.id AND b1.aktif = 1 ) as jml2 
											   FROM peminjaman_tools a 
											   LEFT JOIN karyawan b ON b.id = a.id_teknisi WHERE a.aktif = 1 
											   $search
				) x1 ORDER BY x1.tgl_jam asc")->result();

		return $query;

	}

	public function get_peminjaman_all()
	{

		$query = $this->db->query("SELECT * FROM (SELECT f.id as id_pengembalian, d.nama as nama_karyawan, c.nama as nama_tools, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note
		, CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, f.aktif, a.aktif as aktif_peminjaman
	   FROM peminjaman_tools a 
	   LEFT JOIN peminjaman_tools_detail b ON b.id_peminjaman = a.id 
	   LEFT JOIN tools c ON c.id = b.id_tools
	   LEFT JOIN karyawan d ON d.id = a.id_teknisi 
	   LEFT JOIN rak_tools e ON e.id = c.id_rak
	   LEFT JOIN pengembalian_tools f on f.id_peminjaman_detail = b.id AND f.aktif = 1
	   AND  a.aktif = 1 ) a1 WHERE a1.id_pengembalian IS NULL  AND  a1.aktif_peminjaman = 1 
	   ORDER BY a1.tgl_jam ASC")->result();
		return $query;
	}


	public function get_peminjaman_by_teknisi($id)
	{

		$query = $this->db->query("SELECT * FROM (SELECT b.id as id_detail, f.id as id_pengembalian, d.nama as nama_karyawan, c.nama as nama_tools, a.id, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, a.note
		, CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, f.aktif, a.aktif as aktif_peminjaman
	   FROM peminjaman_tools a 
	   LEFT JOIN peminjaman_tools_detail b ON b.id_peminjaman = a.id 
	   LEFT JOIN tools c ON c.id = b.id_tools
	   LEFT JOIN karyawan d ON d.id = a.user_input 
	   LEFT JOIN rak_tools e ON e.id = c.id_rak
	   LEFT JOIN pengembalian_tools f on f.id_peminjaman_detail = b.id AND f.aktif = 1
		  
	   AND  a.aktif = 1
	   WHERE a.id_teknisi ='$id') a1 WHERE a1.aktif_peminjaman = 1 AND a1.id_pengembalian IS NULL ORDER BY a1.tgl_jam ASC")->result();
		return $query;
	}

	public function get_pengembalian_by_teknisi($id)
	{

		$query = $this->db->query("SELECT * FROM (SELECT  b.id as id_detail, f.id as id_pengembalian, d.nama as nama_karyawan, c.nama as nama_tools, a.id
		, CONCAT(f.tanggal, ' - ', f.jam) as tgl_jam_kembali
		, CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam_pinjam
		, a.note
		, CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, f.aktif
	   FROM peminjaman_tools a 
	   LEFT JOIN peminjaman_tools_detail b ON b.id_peminjaman = a.id 
	   LEFT JOIN tools c ON c.id = b.id_tools
	   LEFT JOIN karyawan d ON d.id = a.user_input 
	   LEFT JOIN rak_tools e ON e.id = c.id_rak
	   LEFT JOIN pengembalian_tools f on f.id_peminjaman_detail = b.id AND f.aktif = 1
		  
	   AND  a.aktif = 1 
	   WHERE a.id_teknisi ='$id') a1 WHERE a1.id_pengembalian IS NOT NULL ORDER BY a1.tgl_jam_kembali ASC")->result();
		return $query;
	}


	public function get_peminjamanById($id){


		$query = $this->db->query("SELECT * FROM peminjaman_tools WHERE aktif = '1' AND id='$id' ")->row();
			
		return $query;

    }

    public function get_peminjaman_detail($id){
        $query = $this->db->query("SELECT *, CONCAT(c.kode_rak, '', c.nomor_rak) as kode_rak , a.id as idx,
									( SELECT COUNT(a1.id_tools) FROM peminjaman_tools_detail a1 
										LEFT JOIN pengembalian_tools b1 on b1.id_peminjaman_detail = a1.id 
										WHERE b1.aktif = 1 AND a1.id_tools = a.id_tools AND a1.id_peminjaman ='$id' ) as jml
									FROM peminjaman_tools_detail a 
                                    LEFT JOIN tools b ON b.id = a.id_tools
                                    LEFT JOIN rak_tools c ON c.id = b.id_rak WHERE id_peminjaman='$id'")->result();
			
		return $query;
	}
	
	public function get_peminjaman_detail_dash(){
        $query = $this->db->query("SELECT *, CONCAT(c.kode_rak, '', c.nomor_rak) as kode_rak , a.id as idx, e.nama as nama_teknisi, b.nama as nama_tools
		, CONCAT(d.tanggal, ' - ', d.jam) as tgl_jam
		,  CONCAT(f.tanggal, ' - ', f.jam) as tgl_jam_kembali,
									( SELECT COUNT(a1.id_tools) FROM peminjaman_tools_detail a1 
										LEFT JOIN pengembalian_tools b1 on b1.id_peminjaman_detail = a1.id 
										WHERE b1.aktif = 1 AND a1.id_tools = a.id_tools AND a1.id_peminjaman = a.id_peminjaman ) as jml
									FROM peminjaman_tools_detail a 
                                    LEFT JOIN tools b ON b.id = a.id_tools
									LEFT JOIN rak_tools c ON c.id = b.id_rak 
									LEFT JOIN peminjaman_tools d ON d.id = a.id_peminjaman
									LEFT JOIN karyawan e ON e.id = d.id_teknisi
									LEFT JOIN pengembalian_tools f ON f.id_peminjaman_detail = a.id")->result();
			
		return $query;
    }
    
    public function get_peminjamanTools($id){


		$query = $this->db->query("SELECT a1.id , CONCAT(a1.kode_rak, ' ', a1.nama_tools) as nama, a1.jml FROM (
            SELECT CONCAT(c.kode_rak, '', c.nomor_rak) as kode_rak , a.id_tools as id, b.nama as nama_tools,
			(SELECT COUNT(*) FROM pengembalian_tools p1 WHERE p1.id_peminjaman_detail = a.id) as jml 
			
			FROM peminjaman_tools_detail a 
            LEFT JOIN  tools b ON b.id = a.id_tools 
            LEFT JOIN rak_tools c ON c.id = b.id_rak
            WHERE id_peminjaman = '$id' ) as a1 WHERE a1.jml < 1")->result();
			
		return $query;

	}


	public function delete_peminjaman($id)
	{	
	
		$this->db->query("DELETE FROM peminjaman_tools  WHERE id='$id'");
		$this->db->query("DELETE FROM peminjaman_tools_detail  WHERE id_peminjaman='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}


	public function delete_peminjaman_detail($id)
	{	
	
		$this->db->query("DELETE FROM peminjaman_tools_detail WHERE id='$id'");

		return array ('status'=> 400, 'message' =>'Data has been deleted');
		
	}


	public function post_peminjaman($params)
	{

        	//generate kode
			$today = date("Ymd");
			$TAHUN = substr($today, 2, 2);
			$BULAN = substr($today, 4, 2);
			$TANGGAL = substr($today, 6, 2);

			$query = "SELECT MAX(id) as last FROM peminjaman_tools 
			WHERE LEFT(id,2) = 'TR' AND SUBSTRING(id,3,2) = '$TAHUN' and SUBSTRING(id,5,2) = '$BULAN' 
            AND SUBSTRING(id,7,2) = '$TANGGAL' ";
            

			$row = $this->db->query($query)->row();
			$tgl = $TAHUN.$BULAN.$TANGGAL;
			$lastNoTransaksi = $row->last;
			$lastNoUrut = substr($lastNoTransaksi, 8, 3);
			$nextNoUrut = (int)$lastNoUrut + 1;
			$nextKode = "TR".$tgl.sprintf('%03s', $nextNoUrut);
			//generate kode

            $data['id'] = $nextKode;
            $data['note'] = $params['note'];
            $data['id_teknisi'] = $params['id_teknisi'];
            $data['user_input'] = $params['user_input'];
            $data['tanggal'] = $params['tanggal'];
            $data['jam'] = $params['jam'];
            $data['date_input'] = date('Y-m-d H:i:s');
            $data['aktif'] = 1;

            $this->db->insert('peminjaman_tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been inserted', 'id_peminjaman' => $nextKode);
    }
    

	public function update_peminjaman($id, $data)
	{	
		$this->db->where('id', $id)->update('peminjaman_tools', $data);

		return array ('status'=> 200, 'message' =>'Data has been updated');
	}

}
