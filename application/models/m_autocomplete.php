<?php

class M_autocomplete extends CI_Model {
    
    function get_auto_data_mahasiswa($q, $start, $limit) {
        $param = NULL;
        if ($q['jurusan'] !== '') {
            $param = " and k_prs = '".$q['jurusan']."'";
        }
        $limit = " limit $start, $limit";
        $sql    = "select * from tb_mahasiswa_view where (nama like ('%".$q['src']."%') or nim like ('%".$q['src']."%')) and tgl_lulus = '0000-00-00' $param order by locate('".$q['src']."',nim)";
        $count  = "select count(n_mhs) as count from tb_mahasiswa where (nama like ('%".$q['src']."%') or nim like ('%".$q['src']."%')) and tgl_lulus = '0000-00-00' $param";
        
        $data['data'] = $this->db->query($sql.$limit)->result();
        $data['total'] = $this->db->query($count)->row()->count;
        return $data;
    }
    
    function get_jenis_pembayaran($jenis) {
        $sql = "select * from tb_jenis_penerimaan where jenis = '$jenis'";
        return $this->db->query($sql);
    }
}