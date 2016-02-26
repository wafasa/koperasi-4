<?php

class Masterdata extends CI_Controller {
    
    function __construct() {
        parent::__construct();
    }
    
    function rka() {
        $data['title'] = 'Rencana Kegiatan dan Anggaran';
        $data['thn_anggaran'] = $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row();
        $this->load->view('masterdata/rka', $data);
    }
    
    function penerimaan(){
        $data['title'] = 'Penerimaan Sumber dana';
        $data['thn_anggaran'] = $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row();
        $this->load->view('masterdata/penerimaan', $data);
    }
}