<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Masterdata extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_masterdata'));

        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }
    
    private function start($page){
        return (($page - 1) * $this->limit);
    }
    
    function anggotas_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota'),
            'nama' => get_safe('nama'),
            'no_rekening' => get_safe('norek')
        );
        
        $data = $this->m_masterdata->get_list_anggota($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function anggota_post() {
        $data = $this->m_masterdata->save_data_anggota();
        $this->response($data, 200);
    }
    
    function debiturs_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_masterdata->get_list_debiturs($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function transaksi_lains_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_masterdata->get_list_transaksi_lain($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function transaksi_lain_post() {
        $param = array(
            'id' => post_safe('id'),
            'nama' => post_safe('nama'),
            'jenis' => post_safe('jenis')
        );
        $data = $this->m_masterdata->save_transaksi_lain($param);
        $this->response($data, 200);
    }
    
    function transaksi_lain_delete() {
        $this->db->delete('tb_jenis_transaksi', array('id' => $this->get('id')));
    }
    
    function kategori_anggotas_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'nama' => get_safe('nama')
        );
        
        $data = $this->m_masterdata->get_list_kategori_anggota($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function kategori_anggota_post() {
        $param = array(
            'id' => post_safe('id'),
            'nama' => post_safe('nama'),
            'keterangan' => post_safe('keterangan')
        );
        $data = $this->m_masterdata->save_kategori_anggota($param);
        $this->response($data, 200);
    }
    
    function kategori_anggota_delete() {
        $this->db->delete('tb_kategori_anggota', array('id' => $this->get('id')));
    }
}