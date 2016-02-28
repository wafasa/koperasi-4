<?php

class M_config extends CI_Model {
   
    function get_list_tahun_anggaran($limit, $start, $search) {
        //$limitation = null; 
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        $limitation =" limit $start , $limit";
        
        $sql = "select * from tb_tahun_anggaran where id is not NULL $q order by id";
        $queryAll = $this->db->query($sql.$limitation);
        $data['data'] = $queryAll->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function aktivasi_tahun_anggaran($id) {
        $this->db->update('tb_tahun_anggaran', array('aktifasi' => 'Tidak'));
        
        $this->db->where('id', $id);
        $this->db->update('tb_tahun_anggaran', array('aktifasi' => 'Ya'));
    }
    
    function lock_my_app() {
        $create_file = fopen('assets/fonts/app.txt', 'w');
        fwrite($create_file, get_mac_address());
        fclose($create_file);
        $output = 'Oke, Kunci Berhasil Dibuat';
        
        $check = $this->db->get('tb_smart_card')->num_rows();
        if ($check === 0) {
            $this->db->insert('tb_smart_card', array('nama' => get_mac_address()));
        } else {
            $this->db->update('tb_smart_card', array('nama' => get_mac_address()));
        }
        return $output;
    }
    
    function save_tahun_anggaran() {
        $id     = post_safe('id');
        $tahun  = post_safe('tahun');
        $aktifasi = post_safe('aktivasi');
        $semester = post_safe('semester');
        $jml_siswa= post_safe('jml_siswa');
        $data = array(
            'id' => $id,
            'tahun_anggaran' => $tahun,
            'semester' => $semester,
            'aktifasi' => $aktifasi,
            'jumlah_siswa' => $jml_siswa
        );
        if ($data['id'] === '') {
            if ($data['aktifasi'] === 'Ya') {
                $this->db->update('tb_tahun_anggaran', array('aktifasi' => 'Tidak'));
            }
            $this->db->insert('tb_tahun_anggaran', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_tahun_anggaran', $data);
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    function change_password() {
        $data_post = array(
            'passlama' => post_safe('passlama'),
            'passbaru' => post_safe('passbaru'),
            'ulangipass' => post_safe('ulangipass')
        );
        
        $check = $this->db->get_where('tb_users', array('id' => $this->session->userdata('id_user'), 'password' => md5($data_post['passlama'])))->num_rows();
        if ($check === 0) {
            $result['status'] = FALSE;
            $result['message']= 'Password lama yang anda masukkan salah !';
        } else {
            $this->db->where('id', $this->session->userdata('id_user'));
            $this->db->update('tb_users', array('password' => md5($data_post['passbaru'])));
            $result['status'] = TRUE;
            $result['message']= 'Password barhasil diubah !';
        }
        return $result;
    }
    
    function get_institusi_name() {
        return $this->db->get('tb_institusi')->row();
    }
    
    function save_config_institusi($data) {
        $check= $this->db->get('tb_sekolah')->num_rows();
        if ($check === 0) {
            $this->db->insert('tb_sekolah', $data);
        } else {
            $this->db->update('tb_sekolah', $data);
        }
        $result['status'] = true;
        return $result;
    }
}