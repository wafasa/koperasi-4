<?php

class Akuntansi extends CI_Controller {
    
    function rekening(){
        $data['title'] = "Rekening";
        $this->load->view('akuntansi/rekening', $data); 
    }
    
    function neraca() {
        $data['title'] = 'Neraca';
        $this->load->view('akuntansi/neraca', $data);
    }
    
    function laba_rugi() {
        $data['title'] = 'Laba Rugi';
        $this->load->view('akuntansi/laba-rugi', $data);
    }
    
    function bukubesar() {
        $data['title'] = 'Buku Besar';
        $this->load->view('akuntansi/bukubesar', $data);
    }
    
    function jurnal_penyesuaian() {
        $data['title'] = 'Jurnal Penyesuaian';
        $this->load->view('akuntansi/jurnal-penyesuaian', $data);
    }
    
    function jurnal_umum() {
        $data['title'] = 'Jurnal Umum';
        $this->load->view('akuntansi/jurnal-umum', $data);
    }
    
    function jurnal_pengeluaran() {
        $data['title'] = 'Jurnal Pengeluaran';
        $this->load->view('akuntansi/jurnal-pengeluaran', $data);
    }
    
    function arus_kas() {
        $data['title'] = 'Laporan Arus Kas';
        $this->load->view('akuntansi/arus-kas', $data);
    }
    
    function jurnal_penerimaan() {
        $data['title'] = 'Jurnal Penerimaan';
        $this->load->view('akuntansi/jurnal-penerimaan', $data);
    }
}