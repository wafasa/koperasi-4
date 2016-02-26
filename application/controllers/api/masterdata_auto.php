<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Masterdata_auto extends REST_Controller{
    function __construct(){
        parent::__construct();
        $this->limit = 20;

        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }

    private function start($page){
        return (($page - 1) * $this->limit);
    }
    
    function rka_auto_get() {
        $param['search']    = get_safe('q');
        $start = $this->start(get_safe('page'));
        $data = $this->m_masterdata->get_auto_rka($param, $start, $this->limit);
        if ((get_safe('page') == 1) & (get_safe('q') == '')) {
            $pilih[] = array('id'=>'', 'kode' => '', 'nama_program' =>'');
            $data['data'] = array_merge($pilih, $data['data']);
            $data['total'] += 1;
        }
        $this->response($data, 200);
    }
    
    function rka_trans_auto_get() {
        $param['search']    = get_safe('q');
        $param['level']     = get_safe('level');
        $param['parent']    = get_safe('parent');
        $start = $this->start(get_safe('page'));
        $data = $this->m_masterdata->get_auto_rka_trans($param, $start, $this->limit);
        if ((get_safe('page') == 1) & (get_safe('q') == '')) {
            $pilih[] = array('id'=>'', 'kode' => '', 'nama_program' =>'');
            $data['data'] = array_merge($pilih, $data['data']);
            $data['total'] += 1;
        }
        $this->response($data, 200);
    }
    
    function penerimaan_auto_get() {
        $param['search']    = get_safe('q');
        $start = $this->start(get_safe('page'));
        $data = $this->m_masterdata->get_auto_penerimaan($param, $start, $this->limit);
        if ((get_safe('page') == 1) & (get_safe('q') == '')) {
            $pilih[] = array('id'=>'', 'kode' => '', 'nama_program' =>'');
            $data['data'] = array_merge($pilih, $data['data']);
            $data['total'] += 1;
        }
        $this->response($data, 200);
    }
    
}