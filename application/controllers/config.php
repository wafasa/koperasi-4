<?php

class Config extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_config'));
    }
    
    function tahun_anggaran() {
        $data['title'] = 'Tahun Anggaran';
        $this->load->view('config/tahun-anggaran', $data);
    }
    
    function changepassword() {
        $data['title'] = 'Ubah Password';
        $this->load->view('config/changepass', $data);
    }
    
    function institusi() {
        $data['title'] = 'Institusi';
        $this->load->view('config/institusi', $data);
    }
}