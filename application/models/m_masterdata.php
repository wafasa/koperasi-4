<?php

class M_masterdata extends CI_Model {
    
    function get_agama() {
        return array(
            'Islam', 'Kristen', 'Protestan', 'Hindu', 'Budha'
        );
    }
    
    function get_status_rumah() {
        return array(
            'Sewa','Sendiri'
        );
    }
    
    function get_lama_pembiayaan() {
        return $this->db->get('tb_durasi_kredit')->result();
    }
    
    function get_data_jenis_transaksi() {
        return $this->db->get('tb_jenis_transaksi')->result();
    }
    
    function get_list_debiturs($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_anggota
            where id is not NULL";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by nomor_rekening desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_data_anggota($data) {
        $this->db->where('id', $data['id']);
        $this->db->update('tb_anggota', $data);
        return TRUE;
    }
}