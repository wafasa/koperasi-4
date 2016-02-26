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
    
    function rkas_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_masterdata->get_list_rka($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function rka_get() {
        $data = $this->m_masterdata->get_rka($this->get('id'));
        $this->response($data, 200);
    }
    
    function rka_post() {
        $data = $this->m_masterdata->save_rka();
        $this->response($data, 200);
    }
    
    function rka_delete() {
        $this->db->delete('tb_rka', array('id' => $this->get('id')));
    }
    
    /*Penerimaan*/
    function penerimaans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_masterdata->get_list_penerimaan($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function penerimaan_get() {
        $data = $this->m_masterdata->get_penerimaan($this->get('id'));
        $this->response($data, 200);
    }
    
    function penerimaan_post() {
        $data = $this->m_masterdata->save_penerimaan();
        $this->response($data, 200);
    }
    
    function penerimaan_delete() {
        $this->db->delete('tb_penerimaan', array('id' => $this->get('id')));
    }
}