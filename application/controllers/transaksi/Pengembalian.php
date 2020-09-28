<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Pengembalian extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('transaksi/Pengembalian_model');
    
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

    public function get_pengembalian(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();
            

            $resp = $this->Pengembalian_model->get_pengembalian();


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

    public function delete_pengembalian($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Pengembalian_model->delete_pengembalian($id);
            json_output(200, $resp);
        }
    }

    public function post_pengembalian(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['id_teknisi'] == "" || $params['tanggal'] == "" || $params['jam'] == "" || $params['id_peminjaman_detail'] ==""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $respStatus = 200;	
                $resp = $this->Pengembalian_model->post_pengembalian($params);
     
                json_output($respStatus, $params);

            }
            
        }
    }

    public function post_pengembalian_all(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['id_teknisi'] == "" || $params['tanggal'] == "" || $params['jam'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $respStatus = 200;	
                $resp = $this->Pengembalian_model->post_pengembalian_all($params);
     
                json_output($respStatus, $params);

            }
            
            
    }
    }

    public function update_peminjaman($id){
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

            $delete = $this->db->query("DELETE FROM peminjaman_tools_detail WHERE id_peminjaman='$id'");

            foreach($tools as $row){
    
                $update_detail['id_tools'] = $row['id'];
                $update_detail['id_peminjaman'] = $id;
                
                $this->db->insert('peminjaman_tools_detail', $update_detail);
            }


        }
        json_output($respStatus, $params);
    }
    }

    

}
