<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Peminjaman extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('transaksi/Peminjaman_model');
    
        $this->objOfJwt = new ImplementJwt();
    
        header('Content-Type: application/json');

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Method: POST, GET, OPTIONS, PUT, DELETE');
        header("Access-Control-Allow-Headers: X-Custom-Header, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");  		
    }

	public function CekToken()
    {

        $method = $_SERVER['REQUEST_METHOD'];

        if($method!="OPTIONS"){

            $received_Token = $this->input->request_headers('Authorization');

            try
            {
                $jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']);
                // echo json_encode($jwtData);
            }
            catch (Exception $e)
            {
                http_response_code('401');
                echo json_encode(array( "status" => false, "message" => $e->getMessage()));exit;
            }

        }

    }

    public function get_peminjaman(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            // $inputJSON = file_get_contents('php://input');	
            // parse_str($inputJSON, $params);

            // $tgl1 = $params['tanggal'];
            // $tgl2 = $params['tanggal2'];

            $resp = $this->Peminjaman_model->get_peminjaman();


            json_output(200, $resp);

	    }

    }

    public function get_peminjaman_search(){

        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);

           $tgl1 = $params['tanggal'];
           $tgl2 = $params['tanggal2'];

           

            $resp = $this->Peminjaman_model->get_peminjaman_search($tgl1,$tgl2);


            json_output(200, $resp);

	    }


    }

    public function get_peminjaman_all(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{


            $resp = $this->Peminjaman_model->get_peminjaman_all();

            json_output(200, $resp);

	    }

    }

    public function get_peminjaman_by_teknisi($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{


            $resp = $this->Peminjaman_model->get_peminjaman_by_teknisi($id);

            json_output(200, $resp);

	    }

    }


    public function get_pengembalian_by_teknisi($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{


            $resp = $this->Peminjaman_model->get_pengembalian_by_teknisi($id);

            json_output(200, $resp);

	    }

    }

    

    public function get_peminjamanById($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $resp = $this->Peminjaman_model->get_peminjamanById($id);
            $tools = $this->Peminjaman_model->get_peminjamanTools($id);

            
            $data['row'] = $resp;
            $data['tools'] = $tools;

            json_output(200, $data);

	    }

    }

    public function get_peminjaman_detail($id){
        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $resp = $this->Peminjaman_model->get_peminjaman_detail($id);
        
            json_output(200, $resp);

	    }

    }

    public function get_peminjaman_detail_dash(){
        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $resp = $this->Peminjaman_model->get_peminjaman_detail_dash();
        
            json_output(200, $resp);

	    }

    }

    public function get_peminjaman_sum(){
        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $jml_pinjam = $this->db->query("SELECT a.id, b.id FROM peminjaman_tools_detail a 
            LEFT JOIN peminjaman_tools c on c.id = a.id_peminjaman
            LEFT JOIN pengembalian_tools b on b.id_peminjaman_detail = a.id 
            WHERE c.aktif = 1")->num_rows();

            $data['jml_pinjam'] = $jml_pinjam;


            $jml_kembali = $this->db->query("SELECT a.id FROM pengembalian_tools a WHERE aktif=1")->num_rows();

            $data['jml_kembali'] = $jml_kembali;

            if($jml_kembali==0){
                $data['persen'] = 0;
            }else{
                $data['persen'] = number_format($jml_kembali / $jml_pinjam * 100, 2);
            }

           
            

            json_output(200, $data);

	    }

    }

    public function delete_peminjaman($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Peminjaman_model->delete_peminjaman($id);
            json_output(200, $resp);
        }
    }

    public function delete_peminjaman_detail($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Peminjaman_model->delete_peminjaman_detail($id);
            json_output(200, $resp);
        }
    }

    public function post_peminjaman(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['id_teknisi'] == "" || $params['tools'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                    $resp = $this->Peminjaman_model->post_peminjaman($params);
                    
                    $tools = $params['tools'];

                    foreach($tools as $row){
            
                            $data['id_tools'] = $row['id'];
                            $data['id_peminjaman'] = $resp['id_peminjaman'];
                            
                            $this->db->insert('peminjaman_tools_detail', $data);
                    }
                
            }
            
            json_output($respStatus, $params);
    }
    }

    public function update_peminjaman(){
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST'){
        json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        
    }else{

        $this->CekToken();

        $respStatus = 200;	
        $inputJSON = file_get_contents('php://input');	
        parse_str($inputJSON, $params);
        //$params = json_decode(file_get_contents('php://input'),TRUE);
        if($params['id_teknisi'] == "" || $params['tools'] == ""){
            $respStatus = 400;
            json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
        }else{
            

            $data['note'] = $params['note'];
            $data['id_teknisi'] = $params['id_teknisi'];
            $data['user_input'] = $params['user_input'];
            $data['tanggal'] = $params['tanggal'];
            $data['jam'] = $params['jam'];
            $data['date_input'] = date('Y-m-d H:i:s');
            $data['aktif'] = 1;

            $id = $params['id'];
            
            $resp = $this->Peminjaman_model->update_peminjaman($id, $data);
                    
            $tools = $params['tools'];

            // $delete = $this->db->query("DELETE FROM peminjaman_tools_detail WHERE id_peminjaman='$id'");

            foreach($tools as $row){

                $id_tools =  $row['id'];

                $key = $this->db->query("SELECT id FROM peminjaman_tools_detail WHERE id_peminjaman='$id' AND id_tools='$id_tools'")->row();

                $id_p_detail = $key->id;

                $this->db->query("DELETE FROM pengembalian_tools WHERE id_peminjaman_detail='$id_p_detail'");

                $cek_tools = $this->db->query("SELECT * FROM peminjaman_tools_detail WHERE id_peminjaman='$id' AND id_tools='$id_tools'")->num_rows();

                if(($cek_tools==0 && $row['id']!=0) || $row['id']=='' || $row['id']==null || $row['id']=='null'){

                    $update_detail['id_tools'] = $row['id'];
                    $update_detail['id_peminjaman'] = $id;

                    $this->db->insert('peminjaman_tools_detail', $update_detail);
                }
    
                
            }


        }
        json_output($respStatus, $params);
    }
    }

    

}
