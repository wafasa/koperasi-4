<?php

class Printing extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_config','m_transaksi'));
    }
    
    function print_setoran_tabungan() {
        $search= array(
            'id' => get_safe('id'),
            'awal' => '',
            'akhir' => '',
            'norek' => ''
        );
        $data = $this->m_transaksi->get_list_setoran_tabungans(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Simpanan Bebas';
        $data['subtitle'] = 'Bukti Transaksi Simpanan Bebas';
        $this->load->view('transaksi/print-setoran-tabungan', $data);
    }
    
    function print_penarikan_tabungan() {
        $search= array(
            'id' => get_safe('id'),
            'awal' => '',
            'akhir' => '',
            'norek' => ''
        );
        $data = $this->m_transaksi->get_list_penarikan_tabungans(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Simpanan Bebas';
        $data['subtitle'] = 'Bukti Transaksi Penarikan Simpanan Bebas';
        $this->load->view('transaksi/print-penarikan-tabungan', $data);
    }
    
    function print_simpanan_wajib() {
        $search= array(
            'id' => get_safe('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        $data = $this->m_transaksi->get_list_simpanan_wajib(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Simpanan Wajib';
        $data['subtitle'] = 'Bukti Transaksi Simpanan Wajib';
        $this->load->view('transaksi/print-simpanan-wajib', $data);
    }
    
    function print_penarikan_simpanan_wajib() {
        $search= array(
            'id' => get_safe('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        $data = $this->m_transaksi->get_list_penarikan_simpanan_wajib(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Penarikan Simpanan Wajib';
        $data['subtitle'] = 'Bukti Transaksi Penarikan Simpanan Wajib';
        $this->load->view('transaksi/print-penarikan-simpanan-wajib', $data);
    }
    
    function print_penarikan_simpanan_pokok() {
        $search= array(
            'id' => get_safe('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        $data = $this->m_transaksi->get_list_penarikan_simpanan_pokok(NULL, NULL, $search);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Penarikan Simpanan Pokok';
        $data['subtitle'] = 'Bukti Transaksi Penarikan Simpanan Pokok';
        $this->load->view('transaksi/print-penarikan-simpanan-pokok', $data);
    }
    
    function print_angsuran() {
        
        $param  = array(
            'id' => get_safe('id'),
            'awal' => '',
            'akhir' => '',
            'norek' => '',
            'nama' => ''
        );
        $data = $this->m_transaksi->get_list_angsurans(NULL, NULL, $param);
        $data['inst'] = $this->m_config->get_institusi_name();
        $data['title'] = 'Angsuran';
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
    
    function cetak_transaksi_tabungan_terakhir() {
        $data = $this->m_laporan->get_detail_tabungan(get_safe('id'), 'print');
        $this->m_transaksi->update_tabungan_status(get_safe('id'));
        if (count($data['data']) > 0) {
            $this->load->view('laporan/print-tabungan', $data);
        } else {
            echo "Tidak ada data !";
        }
    }
    
    function export_rekap_tabungan() {
        $search= array(
            'id' => '',
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        
        $data = $this->m_laporan->get_list_simpanan_bebas(NULL, NULL, $search);
        $this->load->view('laporan/excel/excel-rekap-tabungan', $data);
    }
}