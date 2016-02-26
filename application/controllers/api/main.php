<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Main extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_main'));
    }
    
    function save_pendaftaran_pmdk_post() {
        $data = $this->m_main->save_pendaftaran_pmdk();
        die(json_encode($data));
    }
    
    function save_pendaftaran_sumb_post() {
        $data = $this->m_main->save_pendaftaran_sumb();
        die(json_encode($data));
    }
}