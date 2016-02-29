<?php

class Printing extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_config'));
    }
    function print_angsuran() {
        $param  = array(
            'id' => get_safe('id'),
            'awal' => '',
            'akhir' => ''
        );
        $data = $this->m_transaksi->get_list_angsurans(NULL, NULL, $param);
        $data['inst'] = $this->m_config->get_institusi_name();
        $this->load->view('transaksi/print-kwitansi-angsuran', $data);
    }
    
    function print_terlambat_angsuran() {
        
        $search= array(
            'id' => '',
            'awal' => ''
        );
        $data = $this->m_laporan->get_list_terlambat_angsuran(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $this->load->view('transaksi/print-terlambat', $data);
    }
}