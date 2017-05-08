<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Akuntansi extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 20;
        $this->load->model(array('m_akuntansi'));

        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }
    
    private function start($page){
        return (($page - 1) * $this->limit);
    }
    
    function rekening_list_get(){
        if(!$this->get('page')){
            $this->response(NULL, 400);
        }

        $search = array(
            'pencarian' => get_safe('pencarian'),
            'nama' => get_safe('namarek')
        );

        $start = $this->start($this->get('page'));
        $data = $this->m_akuntansi->get_list_rekening($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
     
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }

    function rekening_all_get(){        
        $data = $this->m_akuntansi->get_list_rekening();
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function rekening_get(){
        if(!$this->get('id')){
            $this->response(NULL, 400);
        }
     
        $data['data'] = $this->db->where('id', $this->get('id'))->get('tb_rekening')->row();
        $data['page'] = 1;
        $data['limit'] = $this->limit;
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Tidak ada data'), 404);
        }
    }
    
    function rekening_post(){
        $id_parent = post_safe('id_parent');

        if ($id_parent !== '') {
            // generate child
            //$kode = $this->m_akuntansi->get_next_kode('tb_rekening', $id_parent, 7);
            $kode = post_safe('kode');
        }else{
            // generate parent
            //$kode = $this->m_akuntansi->generate_parent_kode('tb_rekening');
            $kode = post_safe('kode');
            $id_parent = NULL;
        }

        $add = array(
                'id' => $this->get('id'),
                'nama' => post_safe('rekening'),
                'id_parent' => $id_parent,
                'kode' => $kode
            );

        
        $this->m_akuntansi->update_data_rekening($add);
        $kode_parent = explode('.', $kode);
        $message = array('id' => $kode_parent[0]);
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function rekening_delete(){
        $this->m_akuntansi->delete_data_rekening($this->get('id'));
    }
    
    function rekening_auto_get(){
        $q = get_safe('q');
        $start = $this->start(get_safe('page'));
        $data = $this->m_akuntansi->get_auto_rekening($q, $start, $this->limit);
        if ((get_safe('page') == 1) & ($q == '')) {
            $pilih[] = array('id'=>'', 'nama' =>'', 'kode'=>'', 'id_parent' => '', 'parent' => '&nbsp;');
            $data['data'] = array_merge($pilih, $data['data']);
            $data['total'] += 1;
        }
        $this->response($data, 200);
    }
    
    /*NERACA*/
    function neraca_saldo_get() {
        $tanggal = date2mysql(get_safe('tanggal'));
        $data['aktiva'] = $this->m_akuntansi->neraca_load_data(1, $tanggal); // Kepala 1, ASSET
        $data['pasiva'] = $this->m_akuntansi->neraca_load_data(2, $tanggal);
        $data['ekuitas'] = $this->m_akuntansi->neraca_load_data(3, $tanggal);
        $data['pendapatan_operasional'] = $this->m_akuntansi->neraca_load_data(4, $tanggal);
        $data['beban_operasional'] = $this->m_akuntansi->neraca_load_data(5, $tanggal);
        
        $data['lajur_kanan'] = array_merge($data['pasiva'], $data['ekuitas'], $data['pendapatan_operasional'], $data['beban_operasional']);
        $this->response($data, 200);
    }
    
    function laba_rugi_get() {
        $tanggal = date2mysql(get_safe('tanggal'));
        $data['pendapatan'] = $this->m_akuntansi->labarugi_load_data(4, $tanggal);
        $data['beban'] = $this->m_akuntansi->labarugi_load_data(5, $tanggal);
        $this->response($data, 200);
    }
    
    function bukubesars_get() {
        if(!$this->get('page')){
            $this->response(NULL, 400);
        }

        $search = array(
            'awal' => get_safe('awal'),
            'rekening' => get_safe('rekening')
        );

        $start = ($this->get('page') - 1) * $this->limit;

        $data = $this->m_akuntansi->get_list_bukubesar($search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function jurnals_get() {
        if(!$this->get('page')){
            $this->response(NULL, 400);
        }

        $search = array(
            'awal' => date2mysql(get_safe('awal')),
            'akhir' => date2mysql(get_safe('akhir')),
            'rekening' => get_safe('rekening'),
            'jenis' => get_safe('jenis'),
        );

        $start = ($this->get('page') - 1) * $this->limit;

        $data = $this->m_akuntansi->get_list_jurnal($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function jurnal_get() {
        $param = array(
            'no_transaksi' => get_safe('no_transaksi')
        );
        $data = $this->m_akuntansi->get_data_jurnal($param);
        $this->response($data, 200);
    }
    
    function jurnal_penyesuaians_get() {
        if(!$this->get('page')){
            $this->response(NULL, 400);
        }

        $search = array(
            'awal' => date2mysql(get_safe('awal')),
            'akhir' => date2mysql(get_safe('akhir')),
            'rekening' => get_safe('rekening'),
            'penyesuaian' => 'Ya'
        );

        $start = ($this->get('page') - 1) * $this->limit;

        $data = $this->m_akuntansi->get_list_jurnal($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function jurnal_del_get() {
        $this->db->delete('tb_jurnal', array('no_transaksi' => get_safe('no_transaksi')));
    }
    
    function bukubesar_delete() {
        $this->m_akuntansi->delete_bukubesar($this->get('id'));
    }
    
    function jurnal_post() {
        $param['jenis'] = (post_safe('jenis') !== '')?post_safe('jenis'):NULL;
        $data = $this->m_akuntansi->save_jurnal($param);
        $this->response($data, 200);
    }
    
    function arus_kas_get() {
        $param2 = array(
            'kode' => '1.1.',
            'tahun' => get_safe('tahun')
        );
        $data = $this->m_akuntansi->arus_kas_load_data($param2);
        $this->response($data, 200);
    }
    
}