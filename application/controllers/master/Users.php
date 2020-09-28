<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Users extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('master/Users_model');
    
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

    public function get_users(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Users_model->get_users();

            json_output(200, $resp);

	    }

    }

    public function get_userById($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $resp = $this->Users_model->get_userById($id);

            json_output(200, $resp);

	    }

    }

    public function delete_user($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Users_model->delete_user($id);
            json_output(200, $resp);
        }
    }

    public function post_user(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['username'] == "" || $params['id_karyawan'] == "" || $params['role'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $id_karyawan = $params['id_karyawan'];

                $query = $this->db->query("SELECT username FROM users WHERE id_karyawan='$id_karyawan' AND aktif = 1");
                                                
                $num = $query->num_rows();

                if($num>0){
                    $params['duplicated'] = $num;
                }
                else{
                    $resp = $this->Users_model->post_user($params);
                    $params['duplicated'] = 0;
                }

                
            }
            
            json_output($respStatus, $params);
    }
    }

    public function update_user($id){
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST'){
        json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        
    }else{

        $this->CekToken();

        $respStatus = 200;	
        $inputJSON = file_get_contents('php://input');	
        parse_str($inputJSON, $params);
        //$params = json_decode(file_get_contents('php://input'),TRUE);
        if($params['username'] == "" || $params['id_karyawan'] == "" || $params['role'] == ""){
            $respStatus = 400;
            json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
        }else{
                
            $id = $params['id'];

            $data['username'] = $params['username'];
            $data['id_karyawan'] = $params['id_karyawan'];
            $data['role'] = $params['role'];

            $resp = $this->Users_model->update_user($id, $data);


        }
        json_output($respStatus, $params);
    }
    }

    

}
