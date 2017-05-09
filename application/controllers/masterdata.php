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
    
    function transaksi_lain() {
        $data['title'] = 'Data Jenis Transaksi Lain';
        $this->load->view('masterdata/transaksi-lain', $data);
    }
    
    function kategori_anggota() {
        $data['title'] = 'Kategori Anggota';
        $this->load->view('masterdata/kategori-anggota', $data);
    }
}