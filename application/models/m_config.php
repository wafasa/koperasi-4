<?php

class M_config extends CI_Model {
    
    function get_list_group($limit, $start, $search) {
        $q = NULL;
        if ($search['id'] !== '') {
            
        }
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = "from tb_user_group ";
        
        $limitation = " limit $start, $limit";
        
        $data['data'] = $this->db->query($select . $sql . $limitation)->result();
        $data['jumlah'] = $this->db->query($count . $sql)->row()->count;
        return $data;
    }
    
    function save_group($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_user_group', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_user_group', $data);
            $result['act'] = 'edit';
        }
        $result['status'] = TRUE;
        return $result;
    }
    
    function get_auto_user_group($search, $start, $limit) {
        $q = NULL;
        if ($search['search'] !== '') {
            $q = " and nama like ('%".$search['search']."%')";
        }
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = "from tb_user_group where id is not NULL $q";
        
        $limitation = " limit $start, $limit";
        
        $data['data'] = $this->db->query($select . $sql . $limitation)->result();
        $data['total'] = $this->db->query($count . $sql)->row()->count;
        return $data;
    }
    
    /*ACCOUNT*/
    
    function get_list_account($limit, $start, $search) {
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and u.id_user = '".$search['id']."'";
        }
        $select = "select u.*, g.nama as nama_group ";
        $count  = "select count(*) as count ";
        $sql = "from tb_usersystem u 
            join tb_user_group g on (u.id_user_group = g.id)
            where u.id_user is not NULL $q";
        
        $limitation = " limit $start, $limit";
        
        $data['data'] = $this->db->query($select . $sql . $limitation)->result();
        $data['jumlah'] = $this->db->query($count . $sql)->row()->count;
        return $data;
    }
    
    function save_account($data) {
        if ($data['id_user'] === '') {
            $data['password'] = md5(1234);
            $this->db->insert('tb_usersystem', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id_user', $data['id']);
            $this->db->update('tb_usersystem', $data);
            $result['act'] = 'edit';
        }
        $result['status'] = TRUE;
        return $result;
    }
   
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
        
        $check = $this->db->get_where('tb_usersystemystem', array('id_user' => $this->session->userdata('id_user'), 'password' => md5($data_post['passlama'])))->num_rows();
        if ($check === 0) {
            $result['status'] = FALSE;
            $result['message']= 'Password lama yang anda masukkan salah !';
        } else {
            $this->db->where('id_user', $this->session->userdata('id_user'));
            $this->db->update('tb_usersystemystem', array('password' => md5($data_post['passbaru'])));
            $result['status'] = TRUE;
            $result['message']= 'Password barhasil diubah !';
        }
        return $result;
    }
    
    function get_institusi_name() {
        return $this->db->get('tb_institusi')->row();
    }
    
    function save_config_institusi($data) {
        $check= $this->db->get('tb_institusi')->num_rows();
        if ($check === 0) {
            $this->db->insert('tb_institusi', $data);
        } else {
            $this->db->update('tb_institusi', $data);
        }
        $result['status'] = true;
        return $result;
    }
    
    function save_config_administrasi($data, $data_denda) {
        $check= $this->db->get('tb_setting_administrasi')->num_rows();
        if ($check === 0) {
            $this->db->insert('tb_setting_administrasi', $data);
        } else {
            $this->db->update('tb_setting_administrasi', $data);
        }
        
        $this->db->query("delete from tb_setting_denda");
        $this->db->insert('tb_setting_denda', $data_denda);
        $result['status'] = true;
        return $result;
    }
    
    function get_list_privileges($id_group) {
        $sql = "select p.*, m.nama as modul, p.form_nama as menu 
                from tb_privileges p
                join tb_module m on (p.id_module = m.id)
                order by m.id";
        $result = $this->db->query($sql)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select count(*) as jumlah, IF(ugp.id is NULL,'FALSE','TRUE') as id, ug.nama 
                    from tb_grant_privileges ugp
                    join tb_privileges p on (ugp.id_privileges = p.id)
                    join tb_user_group ug on (ugp.id_user_group = ug.id)
                    where ugp.id_user_group = '".$id_group."' 
                        and p.id = '".$value->id."'";
            $result[$key]->jumlah = $this->db->query($sql_child)->row()->jumlah;
            $result[$key]->check = $this->db->query($sql_child)->row()->id;
            $result[$key]->nama_group = $this->db->query($sql_child)->row()->nama;
        }
        $data['data'] = $result;
        return $data;
    }

    function save_privileges($data) {
        $this->db->delete('tb_grant_privileges', array('id_user_group' => $data['id_group']));
        if (is_array($data['privileges'])) {
            foreach ($data['privileges'] as $key => $value) {
                $this->db->insert('tb_grant_privileges', array('id_user_group' => $data['id_group'], 'id_privileges' => $data['privileges'][$key]));
            }
        }
        return TRUE;
    }
}