<?php

class M_laporan extends CI_Model {
    
    function id_tahun_anggaran() {
        return $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row()->id;
    }
    
    function data_header() {
        return $this->db->get('tb_sekolah')->row();
    }
    
    function get_list_kas_harian($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        if ($search['awal'] !== '') {
            $q.=" and date(waktu) = '".$search['awal']."'";
        }
        
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_arus_kas
            where id is not NULL";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by id asc";
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select IFNULL(SUM(masuk)-SUM(keluar),0) as awal from tb_arus_kas where id < '".$value->id."'";
            $result[$key]->awal = $this->db->query($sql_child)->row()->awal;
            
            $sql_child = "select IFNULL(SUM(masuk)-SUM(keluar),0) as sisa_saldo from tb_arus_kas where id <= '".$value->id."'";
            $result[$key]->sisa_saldo = $this->db->query($sql_child)->row()->sisa_saldo;
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        $sql_awal = "select IFNULL(SUM(masuk)-SUM(keluar),0) as sisa from tb_arus_kas where date(waktu) < '".$search['awal']."'";
        $data['awal'] = $this->db->query($sql_awal)->row()->sisa;
        $date = explode('-', $search['awal']);
        $varia = mktime(0, 0, 0, $date[1], $date[2]-1, $date[0]);
        $data['kemaren'] = date("d/m/Y",$varia);
        $data['today'] = date("d/m/Y");
        return $data;
    }
}