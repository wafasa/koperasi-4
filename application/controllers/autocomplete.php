<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Autocomplete extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 20;
        $this->load->model(array('m_autocomplete'));
    }
    
    private function start($page){
        return (($page - 1) * $this->limit);
    }
    
    function get_jenis_pembayaran() {
        $jenis  = get_safe('jenis');
        $data   = $this->m_autocomplete->get_jenis_pembayaran($jenis)->result();
        die(json_encode($data));
    }
    
    function get_data_mahasiswa() {
        $q['src'] = get_safe('q');
        $start = $this->start(get_safe('page'));
        $q['jurusan'] = get_safe('jurusan');
        $data = $this->m_autocomplete->get_auto_data_mahasiswa($q, $start, $this->limit);
        if ((get_safe('page') == 1) & ($q == '')) {
            $pilih[] = array('id' => '', 'list'=>'-');
            $data['data'] = array_merge($pilih, $data['data']);
            $data['total'] += 1;
        }
        die(json_encode($data));
    }
}