<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/ImplementJwt.php';

class Peminjaman extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->model('laporan/Peminjaman_model');
    
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




    public function get_lap_peminjaman(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST'){
            json_output(200, array('status'=> 400, 'message' => 'Bad request.', 'dsda' => $method));
        }else{

            $this->CekToken();

            $respStatus = 200;	
            $inputJSON = file_get_contents('php://input');	
            parse_str($inputJSON, $params);
            //$params = json_decode(file_get_contents('php://input'),TRUE);
            if($params['tanggal'] == "" || $params['tanggal2'] == ""){
                $respStatus = 400;
                json_output(400, array('status'=> 400, 'message' => 'Data can\'t empty', 'dsd'=>$params));						
            }else{

                $respStatus = 200;	
                $resp = $this->Peminjaman_model->get_lap_peminjaman($params);
     
                json_output($respStatus, $resp);

            }
        }
    }



}
