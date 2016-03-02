<?php

class Printing extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_config','m_transaksi'));
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
    
    function excel_rekap_pembiayaan() {
        
        $search= array(
            'id' => get_safe('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'nama' => get_safe('nama'),
            'norek' => get_safe('norek'),
            'alamat' => get_safe('alamat')
        );
        
        $data = $this->m_transaksi->get_list_pembiayaans(NULL, NULL, $search);
        $this->load->view('laporan/excel/excel-pembiayaan', $data);
    }
    
    function excel_rekap_angsuran() {
        $search= array(
            'id' => '',
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'nama' => get_safe('nama'),
            'norek' => get_safe('norek')
        );
        
        $data = $this->m_transaksi->get_list_angsurans(NULL, NULL, $search);
        $this->load->view('laporan/excel/excel-angsuran', $data);
    }
    
    function excel_rekap_pendapatan_administrasi() {
        $search= array(
            'id' => '',
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'nama' => get_safe('nama'),
            'norek' => get_safe('norek')
        );
        
        $data = $this->m_laporan->get_list_pendapatan_administrasi(NULL, NULL, $search);
        $this->load->view('laporan/excel/excel-pendapatan-admin', $data);
    }
}