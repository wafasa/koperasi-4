<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Config extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_config'));
    }
    
    function app_get_key_get() {
        $data = $this->m_config->lock_my_app();
        $this->response($data, 200);
    }
    
    function groups_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_config->get_list_group($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function group_post() {
        $param = array(
            'id' => post_safe('id'),
            'nama' => post_safe('nama')
        );
        $data = $this->m_config->save_group($param);
        $this->response($data, 200);
    }
    
    function group_delete() {
        $this->db->delete('tb_user_group', array('id' => $this->get('id')));
    }

    function privileges_get() {
        $data = $this->m_config->get_list_privileges($this->get('id'));
        $this->response($data, 200);
    }

    function privileges_post() {
        $param = array(
            'id_group' => post_safe('id_group'),
            'privileges' => post_safe('privileges') // array
        );
        $data = $this->m_config->save_privileges($param);
        $this->response($data, 200);
    }
    
    /*ACCOUNT*/
    
    function accounts_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_config->get_list_account($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function account_post() {
        $param = array(
            'id_user' => post_safe('id'),
            'nama' => post_safe('nama'),
            'username' => post_safe('username'),
            'id_user_group' => post_safe('id_group')
        );
        $data = $this->m_config->save_account($param);
        $this->response($data, 200);
    }
    
    function tahun_anggarans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id')
        );
        
        $data = $this->m_config->get_list_tahun_anggaran($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function tahun_anggaran_get() {
        $data = $this->m_config->get_tahun_anggaran($this->get('id'));
        $this->response($data, 200);
    }
    
    function tahun_anggaran_post() {
        $data = $this->m_config->save_tahun_anggaran();
        $this->response($data, 200);
    }
    
    function tahun_anggaran_delete() {
        $this->db->delete('tb_tahun_anggaran', array('id' => $this->get('id')));
    }
    
    function aktivasi_tahun_anggaran_get() {
        $data = $this->m_config->aktivasi_tahun_anggaran($this->get('id'));
        $this->response($data, 200);
    }
    
    function change_password_post() {
        $data = $this->m_config->change_password();
        die(json_encode($data));
    }
    
    function institusi_get() {
        $data = $this->db->get('tb_institusi')->row();
        $this->response($data, 200);
    }
    
    function save_institusi_post() {
        $array = array(
            'id' => post_safe('id'),
            'nama' => post_safe('nama'),
            'alamat' => post_safe('alamat'),
            'provinsi' => post_safe('provinsi'),
            'kabupaten' => post_safe('kabupaten'),
            'kecamatan' => post_safe('kecamatan'),
            'kelurahan' => post_safe('kelurahan')
        );
        $data = $this->m_config->save_config_institusi($array);
        $this->response($data, 200);
    }
    
    function administrasi_get() {
        $data['adm'] = $this->db->get('tb_setting_administrasi')->row();
        $data['denda'] = $this->db->get('tb_setting_denda')->row()->persentase;
        $this->response($data, 200);
    }
    
    function save_administrasi_post() {
        $array = array(
            'administrasi' => post_safe('administrasi'),
            'persen_jasa_usaha' => post_safe('persen1'),
            'persen_simpanan' => post_safe('persen2'),
            'bunga_pinjaman' => post_safe('bunga_pinjaman'),
            'simpanan_wajib' => currencyToNumber(post_safe('simpanan_wajib')),
            'simpanan_pokok' => currencyToNumber(post_safe('simpanan_pokok')),
        );
        
        $data_denda = array(
            'persentase' => post_safe('persentase')
        );
        $data = $this->m_config->save_config_administrasi($array, $data_denda);
        $this->response($data, 200);
    }
}