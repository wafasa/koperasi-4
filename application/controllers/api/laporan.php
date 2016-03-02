<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Laporan extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_laporan'));

        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }
    
    function kas_harians_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => date2mysql(get_safe('awal'))
        );
        
        $data = $this->m_laporan->get_list_kas_harian($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function terlambat_angsurans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => date2mysql(get_safe('awal'))
        );
        
        $data = $this->m_laporan->get_list_terlambat_angsuran($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function pendapatan_administrasis_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'nama' => get_safe('nama'),
            'norek' => get_safe('norek')
        );
        
        $data = $this->m_laporan->get_list_pendapatan_administrasi($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
}