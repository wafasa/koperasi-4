<?php

class M_transaksi extends CI_Model {
    
    function id_tahun_anggaran() {
        return $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row()->id;
    }
    function get_list_penerimaan_banks($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        if ($search['tanggal'] !== '') {
            $q.=" and tanggal like ('%".$search['tanggal']."%')";
            $r.=" and tanggal < '".$search['tanggal']."-01'";
        }
        if ($search['kode'] !== '') {
            $q.=" and kode = '".$search['kode']."'";
        }
        if ($search['nobukti'] !== '') {
            $q.=" and nobukti = '".$search['nobukti']."'";
        }
        if ($search['keterangan'] !== '') {
            $q.=" and keterangan like ('%".$search['keterangan']."%')";
        }
        if ($search['nominal'] !== '') {
            $q.=" and nominal = '".$search['nominal']."'";
        }
        if ($search['jenis'] !== '') {
            $q.=" and jenis = '".$search['jenis']."'";
        }
        $sql = "select * from tb_trans_bank where id_tahun_anggaran = '".$this->id_tahun_anggaran()."'";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by id, tanggal asc";
        $query = $this->db->query($sql . $q . $order. $limitation);
        //echo $sql . $q . $order. $limitation;
        $queryAll = $this->db->query($sql . $q);
        
        $sql2 = "select 
            (select SUM(nominal) from tb_trans_bank where id_tahun_anggaran = '".$this->id_tahun_anggaran()."' and jenis = 'Penerimaan' $r) - 
            (select SUM(nominal) from tb_trans_bank where id_tahun_anggaran = '".$this->id_tahun_anggaran()."' and jenis = 'Penarikan' $r) as sisa
            ";
        
        $data['last_saldo'] = $this->db->query($sql2)->row();
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_penerimaan_bank($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_trans_bank', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_trans_bank', $data);
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    function get_list_penerimaan_pajaks($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        if ($search['tanggal'] !== '') {
            $q.=" and tanggal like ('%".$search['tanggal']."%')";
            $r.=" and tanggal < '".$search['tanggal']."-01'";
        }
        if ($search['kode'] !== '') {
            $q.=" and kode = '".$search['kode']."'";
        }
        if ($search['nobukti'] !== '') {
            $q.=" and nobukti = '".$search['nobukti']."'";
        }
        if ($search['keterangan'] !== '') {
            $q.=" and keterangan like ('%".$search['keterangan']."%')";
        }
        if ($search['jenis_pajak'] !== '') {
            $q.=" and jenis_pajak = '".$search['jenis_pajak']."'";
        }
        if ($search['jenis'] !== '') {
            $q.=" and jenis_transaksi = '".$search['jenis']."'";
        }
        $sql = "select * from tb_trans_pajak where id is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['last_saldo'] = '';
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        return $data;
    }
    
    function save_penerimaan_pajak($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_trans_pajak', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_trans_pajak', $data);
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    function get_list_pencairans($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and p.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and (p.tanggal_kegiatan between '".$search['awal']."' and '".$search['akhir']."')";
        }
        if ($search['nobukti'] !== '') {
            $q.=" and p.no_bukti = '".$search['nobukti']."'";
        }
        if ($search['nokode'] !== '') {
            $q.=" and p.id_rka = '".$search['nokode']."'";
        }
        if ($search['nourut'] !== '') {
            $q.=" and r2.id = '".$search['nourut']."'";
        }
        $sql = "select p.*, r.kode, r.nama_program, r2.kode as kode_urut, r2.nama_program as parent_program, r2.id as id_parent
            from tb_trans_pencairan p
            join tb_rka r on (p.id_rka = r.id) 
            left join tb_rka r2 on (r.id_parent = r2.id)
            where p.id is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $query = $this->db->query($sql . $q . $limitation);
        //echo $sql . $q . $limitation;
        $queryAll = $this->db->query($sql . $q);
        $data['data'] = $query->result();
        $data['jumlah'] = $queryAll->num_rows();
        //die(json_encode($data));
        return $data;
    }
    
    function save_pencairan($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_trans_pencairan', $data);
            $result['act'] = 'add';
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_trans_pencairan', $data);
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    
}
?>
