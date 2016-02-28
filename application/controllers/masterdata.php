<?php

class Masterdata extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function anggota() {
        $data['title'] = 'Data Utama Anggota';
        $this->load->view('masterdata/anggota', $data);
    }
    
    function debitur() {
        $data['title'] = 'Data Utama Debitur';
        $this->load->view('masterdata/debitur', $data);
    }
}