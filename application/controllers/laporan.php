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
    
    function tabungan() {
        $data['title'] = 'Rekap Tabungan';
        $this->load->view('laporan/rekap-tabungan', $data);
    }
    
    function angsuran() {
        $data['title'] = 'Rekap Angsuran';
        $this->load->view('laporan/rekap-angsuran', $data);
    }
    
    function administrasi() {
        $data['title'] = 'Rekap Pendapatan Administrasi & Profisi';
        $this->load->view('laporan/rekap-administrasi', $data);
    }
    
    function arus_kas() {
        $data['title'] = 'Rekap Arus Kas';
        $this->load->view('laporan/rekap-arus-kas', $data);
    }
    
    function simpanan_wajib() {
        $data['title'] = 'Rekap Simpanan Wajib';
        $this->load->view('laporan/simpanan-wajib', $data);
    }
}