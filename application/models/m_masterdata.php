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
    
    function get_list_anggota($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and a.id = '".$search['id']."'";
        }
        if ($search['nama'] !== '') {
            $q.=" and a.nama like '%".$search['nama']."%'";
        }
        if ($search['no_rekening'] !== '') {
            $q.=" and a.no_rekening like '%".$search['no_rekening']."%'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and a.tgl_masuk between '".date2mysql($search['awal'])."' and '".date2mysql($search['akhir'])."'";
        }
        
        $select = "select a.*, IFNULL(ka.nama,'') as kategori ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_anggota a 
            left join tb_kategori_anggota ka on (a.id_kategori = ka.id)
            where a.id is not NULL $q 
                order by a.id desc
                ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $result = $this->db->query($select.$sql.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_wajib = "select count(*), IFNULL(masuk,0) as masuk from tb_detail_simpanan_wajib where id_anggota = '".$value->id."' and is_opening = 'Ya'";
            $result[$key]->simpanan_wajib = $this->db->query($sql_wajib)->row()->masuk;
            
            $sql_pokok = "select count(*), IFNULL(masuk,0) as masuk from tb_detail_simpanan_pokok where id_anggota = '".$value->id."' and is_opening = 'Ya'";
            $result[$key]->simpanan_pokok = $this->db->query($sql_pokok)->row()->masuk;
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql)->row()->count;
        return $data;
    }
    
    function save_data_anggota() {
        $this->load->model('m_transaksi');
        $this->db->trans_begin();
        $data_anggota = array(
            'id' => post_safe('id'),
            'id_kategori' => (post_safe('kategori_nasabah') !== '')?post_safe('kategori_nasabah'):NULL,
            'no_ktp' => post_safe('noktp'),
            'nama' => post_safe('nama'),
            'alamat' => post_safe('alamat'),
            'pekerjaan' => post_safe('pekerjaan'),
            'agama' => post_safe('agama'),
            'tgl_masuk' => date2mysql(post_safe('tanggal'))
        );
        if ($data_anggota['id'] === '') {
            $data_anggota['no_rekening'] = $this->create_nomor_rek_tabungan();
            $this->db->insert('tb_anggota', $data_anggota);
            $id_anggota = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        } else {
            $this->db->where('id', $data_anggota['id']);
            $this->db->update('tb_anggota', $data_anggota);
            $id_anggota = $data_anggota['id'];
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            $this->db->delete('tb_detail_simpanan_wajib', array('id_anggota' => $id_anggota, 'is_opening' => 'Ya'));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            $this->db->delete('tb_detail_simpanan_pokok', array('id_anggota' => $id_anggota, 'is_opening' => 'Ya'));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        }
        
        // SIMPANAN POKOK
        $data_simpanan_pokok = array(
            'id_anggota' => $id_anggota,
            'masuk' => currencyToNumber(post_safe('jumlah')),
            'is_opening' => 'Ya',
            'id_user' => $this->session->userdata('id_user')
        );
        $this->db->insert('tb_detail_simpanan_pokok', $data_simpanan_pokok);
        $id_detail_simpanan_pokok = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        }

        //SIMPANAN WAJIB
        $data_simpanan_wajib = array(
            'id_anggota' => $id_anggota,
            'masuk' => currencyToNumber(post_safe('jumlah_simpanan_wajib')),
            'is_opening' => 'Ya',
            'id_user' => $this->session->userdata('id_user')
        );
        $this->db->insert('tb_detail_simpanan_wajib', $data_simpanan_wajib);
        $id_detail_simpanan_wajib = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        }

        // POSTING KE ARUS KAS 
        $arus_kas = array(
            'transaksi' => 'Simpanan Pokok',
            'id_transaksi' => $id_detail_simpanan_pokok,
            'id_detail_simpanan_pokok' => $id_detail_simpanan_pokok,
            'masuk' => currencyToNumber(post_safe('jumlah')),
            'keterangan' => 'Simpanan pokok '.$data_anggota['nama'],
            'id_user' => $this->session->userdata('id_user')
        );
        $this->m_transaksi->save_arus_kas($arus_kas);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        }

        $arus_kas2 = array(
            'transaksi' => 'Simpanan Wajib',
            'id_transaksi' => $id_detail_simpanan_wajib,
            'id_detail_simpanan_wajib' => $id_detail_simpanan_wajib,
            'masuk' => currencyToNumber(post_safe('jumlah_simpanan_wajib')),
            'keterangan' => 'Simpanan wajib '.$data_anggota['nama'],
            'id_user' => $this->session->userdata('id_user')
        );
        $this->m_transaksi->save_arus_kas($arus_kas2);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
            $result['id'] = $id_anggota;
        }
        return $result;
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
    
    function get_list_transaksi_lain($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_jenis_transaksi
            where id is not NULL";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by jenis desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_transaksi_lain($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_jenis_transaksi', $data);
            $result['id'] = $this->db->insert_id();
            $result['status'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_jenis_transaksi', $data);
            $result['id'] = $data['id'];
            $result['status'] = 'edit';
        }
        return $result;
    }
    
    function get_auto_transaksi_lain($search, $start, $limit) {
        $q = NULL;
        if ($search['jenis'] !== '') {
            $q.=" and jenis = '".$search['jenis']."'";
        }
        $limitation = " limit $start, $limit";
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = "
            from tb_jenis_transaksi
            where nama like '%".$search['search']."%'
                $q
                ";
        $result = $this->db->query($select.$sql.$limitation)->result();
        $data['data'] = $result;
        $data['total'] = $this->db->query($count.$sql)->row()->count;
        
        return $data;
    }
    
    function get_list_kategori_anggota($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        
        if ($search['nama'] !== '') {
            $q.=" and nama like '%".$search['nama']."%'";
        }
        
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_kategori_anggota
            where id is not NULL";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by nama desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_kategori_anggota($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_kategori_anggota', $data);
            $result['id'] = $this->db->insert_id();
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_kategori_anggota', $data);
            $result['id'] = $data['id'];
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    function get_auto_kategori_anggota($search, $start, $limit) {
        $q = NULL;
        $limitation = " limit $start, $limit";
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = "
            from tb_kategori_anggota
            where nama like '%".$search['search']."%'
                $q
                ";
        $result = $this->db->query($select.$sql.$limitation)->result();
        $data['data'] = $result;
        $data['total'] = $this->db->query($count.$sql)->row()->count;
        
        return $data;
    }
}