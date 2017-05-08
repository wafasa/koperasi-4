<?php

class Transaksi extends CI_Controller {
    
    /*PEMBAYARAN*/
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_masterdata'));
    }
    function pembiayaan() {
        $data['title'] = 'Pembiayaan Pinjaman';
        $data['agama'] = $this->m_masterdata->get_agama();
        $data['status_rumah'] = $this->m_masterdata->get_status_rumah();
        $data['lama_pembiayaan'] = $this->m_masterdata->get_lama_pembiayaan();
        $this->load->view('transaksi/pembiayaan', $data);
    }

    function angsuran() {
        $data['title'] = 'Pembayaran Angsuran';
        $this->load->view('transaksi/angsuran', $data);
    }

    function pembayaran_beban() {
        $data['title'] = 'Pemasukkan & Pengeluaran';
        $data['jenis'] = $this->m_masterdata->get_data_jenis_transaksi();
        $this->load->view('transaksi/pembayaran-beban', $data);
    }

    function tabungan() {
        $data['title'] = 'Data Tabungan';
        $this->load->view('transaksi/tabungan', $data);
    }

    function setoran_tabungan() {
        $data['title'] = 'Setor Tabungan';
        $this->load->view('transaksi/setoran-tabungan', $data);
    }

    function penarikan_tabungan() {
        $data['title'] = 'Penarikan Tabungan';
        $this->load->view('transaksi/penarikan-tabungan', $data);
    }
    
    function simpanan_wajib() {
        $data['title'] = 'Simpanan Wajib';
        $this->load->view('transaksi/simpanan-wajib', $data);
    }
    
    function penarikan_simpanan_wajib() {
        $data['title'] = 'Penarikan Simpanan Wajib';
        $this->load->view('transaksi/penarikan-simpanan-wajib', $data);
    }
    
    function penarikan_simpanan_pokok() {
        $data['title'] = 'Penarikan Simpanan Pokok';
        $this->load->view('transaksi/penarikan-simpanan-pokok', $data);
    }

    function koreksi_saldo() {
        $data['title'] = 'Koreksi Saldo';
        $this->load->view('transaksi/koreksi-saldo', $data);
    }

}