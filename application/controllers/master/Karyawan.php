<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Karyawan extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('master/Karyawan_model');
    
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

    public function get_karyawan($id_jbt){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Karyawan_model->get_karyawan($id_jbt);

            json_output(200, $resp);

	    }

    }

    public function get_karyawanById($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $resp = $this->Karyawan_model->get_karyawanById($id);

            json_output(200, $resp);

	    }

    }

    public function delete_karyawan($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Karyawan_model->delete_karyawan($id);
            json_output(200, $resp);
        }
    }

    public function post_karyawan(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['nama'] == "" || $params['id_jabatan'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $nama = $params['nama'];
                $id_jabatan = $params['id_jabatan'];

                $query = $this->db->query("SELECT nama FROM karyawan WHERE nama='$nama' AND id_jabatan='$id_jabatan' AND aktif = 1");
                                                
                $num = $query->num_rows();

                if($num>0){
                    $params['duplicated'] = $num;
                }
                else{
                    $resp = $this->Karyawan_model->post_karyawan($params);
                    $params['duplicated'] = 0;
                }

                
            }
            
            json_output($respStatus, $params);
    }
    }

    public function update_karyawan($id){
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST'){
        json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        
    }else{

        $this->CekToken();

        $respStatus = 200;	
        $inputJSON = file_get_contents('php://input');	
        parse_str($inputJSON, $params);
        //$params = json_decode(file_get_contents('php://input'),TRUE);
        if($params['nama'] == "" || $params['id_jabatan'] == ""){
            $respStatus = 400;
            json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
        }else{
                
            $id = $params['id'];

            $data['nama'] = $params['nama'];
            $data['id_jabatan'] = $params['id_jabatan'];
            $data['alamat'] = $params['alamat'];

            $resp = $this->Karyawan_model->update_karyawan($id, $data);


        }
        json_output($respStatus, $params);
    }
    }

    

}
