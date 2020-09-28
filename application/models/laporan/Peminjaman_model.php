<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model {


    public function get_lap_peminjaman($params)
	{

            $id_teknisi = $params['id_teknisi'];
            $tanggal = $params['tanggal'];
            $tanggal2 = $params['tanggal2'];


            if($id_teknisi==null){
                $where = "";
            }else{
                $where = " AND f.id_teknisi='$id_teknisi'";
            }

            $query = $this->db->query("SELECT f.id as id_peminjaman, a.id,  CONCAT(a.tanggal, ' - ', a.jam) as tgl_jam, CONCAT(f.tanggal, ' - ', f.jam) as tgl_jam_pinjam,  d.nama as nama_tools
                                        , CONCAT(e.kode_rak, '', e.nomor_rak) as kode_rak, c.nama as nama_teknisi 
                                        FROM pengembalian_tools a 
                                        LEFT JOIN peminjaman_tools_detail b ON b.id = a.id_peminjaman_detail
                                        LEFT JOIN peminjaman_tools f ON f.id = b.id_peminjaman
                                        LEFT JOIN karyawan c ON c.id = f.id_teknisi
                                        LEFT JOIN tools d ON d.id = b.id_tools
                                        LEFT JOIN rak_tools e ON e.id = d.id_rak WHERE f.aktif = 1 AND  f.tanggal BETWEEN '$tanggal' AND '$tanggal2' $where
                                        ORDER BY a.tanggal ASC ")->result();

           return $query;
    }
    



}
