<?php

class Laporan extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_masterdata'));
    }
    
    function kas_harian() {
        $data['title'] = 'Rekap Kas Harian';
        $this->load->view('laporan/kas-harian', $data);
    }
}