<?php
defined('BASEPATH') OR exit('No direct script access allowed');


	function Json_output($statusHeader,$response){
		$ci =& get_instance();
		$ci->output->set_content_type('application/json');
		$ci->output->set_status_header($statusHeader);
		$ci->output->set_output(json_encode($response, JSON_NUMERIC_CHECK));
	}