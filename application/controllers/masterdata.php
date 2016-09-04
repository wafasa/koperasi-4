<?php

class Masterdata extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('m_masterdata');
    }
    
    function anggota() {
        $data['title'] = 'Data Utama Anggota';
        $data['agama'] = $this->m_masterdata->get_agama();
        $this->load->view('masterdata/anggota', $data);
    }
    
    function debitur() {
        $data['title'] = 'Data Utama Debitur';
        $this->load->view('masterdata/debitur', $data);
    }
}