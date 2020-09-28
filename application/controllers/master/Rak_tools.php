<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Rak_tools extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('master/Rak_tools_model');
    
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

    public function get_rak_tools(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Rak_tools_model->get_rak_tools();

            json_output(200, $resp);

	    }

    }

    public function get_rak_toolById($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $resp = $this->Rak_tools_model->get_rak_toolById($id);

            json_output(200, $resp);

	    }

    }

    public function delete_rak_tools($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Rak_tools_model->delete_rak_tools($id);
            json_output(200, $resp);
        }
    }

    public function post_rak_tools(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['kode'] == "" || $params['nomor'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $kode = $params['kode'];
                $nomor = $params['nomor'];

                $query = $this->db->query("SELECT kode_rak FROM rak_tools WHERE kode_rak='$kode' AND nomor_rak='$nomor' AND aktif = 1");
                                                
                $num = $query->num_rows();

                if($num>0){
                    $params['duplicated'] = $num;
                }
                else{
                    $resp = $this->Rak_tools_model->post_rak_tools($params);
                    $params['duplicated'] = 0;
                }

                
            }
            
            json_output($respStatus, $params);
    }
    }

    public function update_rak_tools($id){
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST'){
        json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        
    }else{

        $this->CekToken();

        $respStatus = 200;	
        $inputJSON = file_get_contents('php://input');	
        parse_str($inputJSON, $params);
        //$params = json_decode(file_get_contents('php://input'),TRUE);
        if($params['kode'] == "" || $params['nomor'] == ""){
            $respStatus = 400;
            json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
        }else{
                
            $id = $params['id'];

            $data['kode_rak'] = $params['kode'];
            $data['nomor_rak'] = $params['nomor'];

            $resp = $this->Rak_tools_model->update_rak_tools($id, $data);


        }
        json_output($respStatus, $params);
    }
    }

    

}
