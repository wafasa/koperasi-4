<?php

class M_laporan extends CI_Model {
    
    function id_tahun_anggaran() {
        return $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row()->id;
    }
    
    function data_header() {
        return $this->db->get('tb_sekolah')->row();
    }
    
    function data_tahun_anggaran_aktif() {
        return $this->db->get_where('tb_tahun_anggaran', array('aktifasi' => 'Ya'))->row();
    }
    
    function load_data_rincian_rka() {
        $aktif = $this->data_tahun_anggaran_aktif();
        $sql = "select r2.*, r1.kode as kode_parent, r1.semester1, r1.semester2 from tb_rka r1 join tb_rka r2 on (r1.id = r2.id_parent) where LENGTH(r2.kode) > 7 and r2.id_tahun_anggaran = '".$aktif->id."' order by r2.kode asc";
        return $this->db->query($sql)->result();
    }
    
    function get_list_kas_umum($limit, $start, $search) {
        $limitation = null; 
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        //$limitation =" limit $start , $limit";
        
        $sql = "select * from (
                    select p.id, p.tanggal, r.kode, p.no_bukti, p.uraian, p.nominal, 'Ya' as keluar 
                    from tb_trans_pencairan p
                    join tb_rka r on (p.id_rka = r.id) where p.id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    UNION 
                    select id, date(tanggal) as tanggal, kode, '-' as no_bukti, nama_program as uraian, nominal, 'Tidak' as keluar
                    from tb_master_penerimaan where id_parent is not NULL
                    and id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    UNION
                    select id, tanggal, kode, nobukti as no_bukti, keterangan as nama_program, nominal, IF(jenis='Penerimaan', 'Tidak', 'Ya') as keluar
                    from tb_trans_bank where id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    UNION
                    select id, tanggal, kode_akun_pajak as kode, no_bukti, uraian as nama_program, hasil_pajak as nominal, IF(jenis_transaksi='Penerimaan','Tidak','Ya') as keluar
                    from tb_trans_pajak where id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    ) a
                order by tanggal, id
                ";
        $queryAll = $this->db->query($sql.$limitation);
        $data['data'] = $queryAll->result();
        $data['jumlah'] = $this->db->query($sql)->num_rows();
        return $data;
    }
    
    function get_list_pembantu_kas($limit, $start, $search) {
        $limitation = null; 
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        //$limitation =" limit $start , $limit";
        
        $sql = "select * from (
                    select p.id, p.tanggal, r.kode, p.no_bukti, p.uraian, p.nominal, 'Ya' as keluar 
                    from tb_trans_pencairan p
                    join tb_rka r on (p.id_rka = r.id) where p.id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    UNION 
                    select id, date(tanggal) as tanggal, kode, '-' as no_bukti, nama_program as uraian, nominal, 'Tidak' as keluar
                    from tb_master_penerimaan where id_parent is not NULL
                    and id_tahun_anggaran = '".$this->id_tahun_anggaran()."'
                    ) a
                order by tanggal, id
                ";
        $queryAll = $this->db->query($sql.$limitation);
        $data['data'] = $queryAll->result();
        $data['jumlah'] = $this->db->query($sql)->num_rows();
        return $data;
    }
}