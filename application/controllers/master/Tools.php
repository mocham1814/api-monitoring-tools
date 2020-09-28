<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Tools extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('master/Tools_model');
    
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

    public function get_tools(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Tools_model->get_tools();

            json_output(200, $resp);

	    }

    }

    public function get_tools_2(){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Tools_model->get_tools_2();

            json_output(200, $resp);

	    }

    }

    public function get_toolById($id){
	
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('message' => 'Bad request.'));
        }else{
            
            $resp = $this->Tools_model->get_toolById($id);

            json_output(200, $resp);

	    }

    }

    public function delete_tools($id){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'GET'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.'));
        }else{

            $this->CekToken();

            $resp = $this->Tools_model->delete_tools($id);
            json_output(200, $resp);
        }
    }

    public function post_tools(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['nama'] == "" || $params['id_rak'] == "" || $params['id_kondisi'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $nama = $params['nama'];
                $id_rak = $params['id_rak'];

                $query = $this->db->query("SELECT nama FROM tools WHERE nama='$nama' AND id_rak='$id_rak' AND aktif = 1");
                                                
                $num = $query->num_rows();

                if($num>0){
                    $params['duplicated'] = $num;
                }
                else{
                    $resp = $this->Tools_model->post_tools($params);
                    $params['duplicated'] = 0;
                }

                
            }
            
            json_output($respStatus, $params);
    }
    }

    public function update_tools($id){
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST'){
        json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        
    }else{

        $this->CekToken();

        $respStatus = 200;	
        $inputJSON = file_get_contents('php://input');	
        parse_str($inputJSON, $params);
        //$params = json_decode(file_get_contents('php://input'),TRUE);
        if($params['nama'] == "" || $params['id_rak'] == "" || $params['id_kondisi'] == ""){
            $respStatus = 400;
            json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
        }else{
                
            $id = $params['id'];

            $data['nama'] = $params['nama'];
            $data['id_rak'] = $params['id_rak'];
            $data['id_kondisi'] = $params['id_kondisi'];

            $resp = $this->Tools_model->update_tools($id, $data);


        }
        json_output($respStatus, $params);
    }
    }

    

}
