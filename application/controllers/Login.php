<?php


defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/jwt/JWT.php';
require APPPATH . '/libraries/jwt/BeforeValidException.php';
require APPPATH . '/libraries/jwt/ExpiredException.php';
require APPPATH . '/libraries/jwt/SignatureInvalidException.php';
use \Firebase\JWT\JWT;

class Login extends CI_Controller {

	private $secretkey = 'vuesucodepahoawebapi';

	function __construct(){
        parent::__construct();
        
		$this->load->model('Login_model');

		header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Method: POST, GET, OPTIONS, PUT, DELETE');
        header("Access-Control-Allow-Headers: X-Custom-Header, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");  		
	}


	public function post_login(){

		$method = $_SERVER['REQUEST_METHOD'];

		if($method != 'POST'){
			json_output(200, array('status'=> 400, 'message' => 'Bad request.'));			
		}else{

            $inputJSON = file_get_contents('php://input');	
			parse_str($inputJSON, $params);

                if($params['username'] == "" || $params['password'] == ""){
                    
                    json_output(400, array('status'=> 400, 'message' => 'Login gagal !'));		
                        
                }else{

                    

                    $resp_log = $this->Login_model->post_login($params['username'], md5($params['password']));
                    
                        if($resp_log){

                            $date = new DateTime();

                            $params['iat'] = $date->getTimestamp(); //waktu di buat
                            // $params['exp'] = $date->getTimestamp() + 3600; //satu jam

                            $resp['auth'] = JWT::encode($params,$this->secretkey);
                            $resp['id_user'] = $resp_log->id;
                            $resp['nama_user'] = $resp_log->nama;
                            
                            $respStatus = 200;

                        }else{

                            $respStatus = 200;
                            
                            $resp['auth'] = '';
                            $resp['id_user'] = '';
                            $resp['nama_user'] = '';
                        }

                }
				
			json_output($respStatus, $resp);
			
        }

    }


}