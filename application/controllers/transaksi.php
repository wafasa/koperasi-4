<?php

class Transaksi extends CI_Controller {
    
    /*PEMBAYARAN*/
    function pembiayaan() {
        $data['title'] = 'Pembiayaan Pinjaman';
        $this->load->view('transaksi/pembiayaan', $data);
    }

    function angsuran() {
        $data['title'] = 'Angsuran Pinjaman';
        $this->load->view('transaksi/angsuran', $data);
    }

    function pembayaran_beban() {
        $data['title'] = 'Pembayaran Tagihan Rutin';
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

    function koreksi_tabungan() {
        $data['title'] = 'Koreksi Tabungan';
        $this->load->view('transaksi/koreksi-tabungan', $data);
    }

}