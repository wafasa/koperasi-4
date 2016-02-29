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
    
    function terlambat_angsur() {
        $data['title'] = 'Rekap Terlambat Angsur';
        $this->load->view('laporan/terlambat-angsur', $data);
    }
    
    function pembiayaan() {
        $data['title'] = 'Rekap Pembiayaan Pinjaman';
        $data['agama'] = $this->m_masterdata->get_agama();
        $data['status_rumah'] = $this->m_masterdata->get_status_rumah();
        $data['lama_pembiayaan'] = $this->m_masterdata->get_lama_pembiayaan();
        $this->load->view('laporan/rekap-pembiayaan', $data);
    }
}