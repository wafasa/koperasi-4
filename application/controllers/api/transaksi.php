<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Transaksi extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_transaksi'));
        $this->id_tahun_anggaran = $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row()->id;
    
        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }
    
    function penerimaan_banks_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'tanggal' => get_safe('tanggal'),
            'kode' => get_safe('nokode'),
            'nobukti' => get_safe('nobukti'),
            'keterangan' => get_safe('uraian'),
            'nominal' => currencyToNumber(get_safe('nominal')),
            'jenis' => get_safe('jenis_transaksi'),
        );
        
        $data = $this->m_transaksi->get_list_penerimaan_banks($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function penerimaan_bank_post() {
        $data_array = array(
            'id' => post_safe('id'),
            'tanggal' => date2mysql(post_safe('tanggal')),
            'kode' => post_safe('nokode'),
            'nobukti' => post_safe('nobukti'),
            'keterangan' => post_safe('uraian'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'jenis' => post_safe('jenis_transaksi'),
            'id_tahun_anggaran' => $this->id_tahun_anggaran
        );
        $data = $this->m_transaksi->save_penerimaan_bank($data_array);
        $this->response($data, 200);
    }
    
    function penerimaan_bank_delete() {
        $this->db->delete('tb_trans_bank', array('id' => $this->get('id')));
    }
    
    function penerimaan_pajaks_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'tanggal' => get_safe('tanggal'),
            'kode' => get_safe('nokode'),
            'nobukti' => get_safe('nobukti'),
            'keterangan' => get_safe('uraian'),
            'jenis' => get_safe('jenis_transaksi'),
            'jenis_pajak' => get_safe('jenis_pajak')
        );
        
        $data = $this->m_transaksi->get_list_penerimaan_pajaks($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function penerimaan_pajak_post() {
        $data_array = array(
            'id' => post_safe('id'),
            'tanggal' => date2mysql(post_safe('tanggal')),
            'kode_akun_pajak' => post_safe('nokode'),
            'uraian' => post_safe('uraian'),
            'no_bukti' => post_safe('nobukti'),
            'jenis_transaksi' => post_safe('jenis_transaksi'),
            'jenis_pajak' => post_safe('jenis_pajak'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'hasil_pajak' => currencyToNumber(post_safe('perhitungan')),
            'id_tahun_anggaran' => $this->id_tahun_anggaran
        );
        $data = $this->m_transaksi->save_penerimaan_pajak($data_array);
        $this->response($data, 200);
    }
    
    function penerimaan_pajak_delete() {
        $this->db->delete('tb_trans_pajak', array('id' => $this->get('id')));
    }
    
    function pencairans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => date2mysql(get_safe('awal')),
            'akhir' => date2mysql(get_safe('akhir')),
            'nobukti' => get_safe('nobukti'),
            'nokode' => get_safe('nokode'),
            'nourut' => get_safe('nourut'),
            'uraian' => get_safe('uraian')
        );
        
        $data = $this->m_transaksi->get_list_pencairans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function pencairan_post() {
        $data_array = array(
            'id' => post_safe('id'),
            'tanggal' => date2mysql(post_safe('tanggal')),
            'tanggal_kegiatan' => date2mysql(post_safe('tanggal_kegiatan')),
            //'kode' => post_safe('nokode'),
//            'nourut' => post_safe('nourut'),
            'id_rka' => post_safe('nokode'),
            'no_bukti' => post_safe('nobukti'),
            'uraian' => post_safe('uraian'),
            'satuan' => post_safe('satuan'),
            'volume' => post_safe('volume'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'penerima' => post_safe('penerima'),
            'id_tahun_anggaran' => $this->id_tahun_anggaran
        );
        $data = $this->m_transaksi->save_pencairan($data_array);
        $this->response($data, 200);
    }
    
    function pencairan_delete() {
        $this->db->delete('tb_trans_pencairan', array('id' => $this->get('id')));
    }
    
}