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
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and date(waktu) between '".date2mysql($search['awal'])."' and '".date2mysql($search['akhir'])."'";
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
        $sql_awal = "select IFNULL(SUM(masuk)-SUM(keluar),0) as sisa from tb_arus_kas where date(waktu) < '".date2mysql($search['awal'])."'";
        $data['awal'] = $this->db->query($sql_awal)->row()->sisa;
        $date = explode('-', date2mysql($search['awal']));
        $varia = mktime(0, 0, 0, $date[1], $date[2]-1, $date[0]);
        $data['kemaren'] = date("d/m/Y",$varia);
        $data['today'] = date("d/m/Y");
        return $data;
    }
    
    function get_list_terlambat_angsuran($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        
        $select = "select dp.*, pj.id as id_pinjaman, pj.angsuran_pokok, pj.jasa_angsuran, db.nama, db.alamat, db.nomor_rekening ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_detail_pinjaman dp
            join tb_pinjaman pj on (dp.id_pinjaman = pj.id)
            join tb_anggota db on (pj.id_debitur = db.id)
            where dp.tgl_bayar is NULL and status_bayar = 'Belum' and dp.jatuh_tempo <= NOW()";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by id asc";
        //echo $count.$sql.$q;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function get_list_pendapatan_administrasi($limit, $start, $search) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and d.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and ap.tgl_input between '".date2mysql($search['awal'])."' and '".  date2mysql($search['akhir'])."'";
        }
        if ($search['nama'] !== '') {
            $q.=" and d.nama like ('%".$search['nama']."%')";
        }
        if ($search['norek'] !== '') {
            $q.=" and d.nomor_rekening = '".$search['norek']."'";
        }
        
        $select = "select ap.*, d.nama, d.no_rekening";
        $count  = "select count(p.id) as count ";
        $sql = "
            from tb_adpro ap 
            join tb_pinjaman p on (ap.id_pinjaman = p.id)
            join tb_anggota d on (p.id_debitur = d.id)
            join tb_detail_debitur dd on (dd.id_pinjaman = p.id)
            where p.id is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by p.id desc";
        //echo $sql . $q . $order. $limitation;
        
        $data['data'] = $this->db->query($select . $sql . $q . $order. $limitation)->result();
        $data['jumlah'] = $this->db->query($count . $sql . $q)->row()->count;
        return $data;
    }
    
    function get_detail_tabungan($id_anggota, $status = FALSE) {
        $q = NULL;
        if ($status === 'print') {
            $q =" and dt.tercetak = 'Belum'";
        }
        $sql = "select t.*, a.nama, a.no_rekening, a.tgl_masuk, a.alamat,
            dt.id as id_detail, dt.tanggal, dt.masuk, dt.keluar, dt.sandi, dt.tercetak, u.kode
            from tb_tabungan t
            join tb_detail_tabungan dt on (dt.id_tabungan = t.id)
            join tb_anggota a on (t.id_anggota = a.id)
            left join tb_usersystem u on (dt.id_user = u.id_user)
            where t.id_anggota = '".$id_anggota."' $q
                order by dt.id asc
                ";
        $result = $this->db->query($sql)->result();
        foreach($result as $key => $value) {
            $sql_child = "select IFNULL(sum(masuk)-sum(keluar),0) as sisa
                from tb_detail_tabungan 
                where id <= '".$value->id_detail."'
                    and id_tabungan = '".$value->id."'
                ";
            $result[$key]->sisa_saldo = $this->db->query($sql_child)->row()->sisa;
        }
        $data['data'] = $result;
        return $data;
    }
    
    function get_pendapatan_bunga($year) {
        $sql = "select sum(jasa) as bunga 
            from tb_detail_pinjaman
            where status_bayar = 'Sudah'
                and YEAR(tgl_bayar) = '".$year."'";
        return $this->db->query($sql)->row()->bunga;
    }
    
    function get_pendapatan_simpanan($year) {
        $sql_wajib = "select sum(masuk)-sum(keluar) as simpanan_wajib
            from tb_detail_simpanan_wajib where YEAR(waktu) = '".$year."'";
        $wajib = $this->db->query($sql_wajib)->row()->simpanan_wajib;
        $sql_pokok = "select sum(masuk)-sum(keluar) as pokok
            from tb_detail_simpanan_pokok where YEAR(waktu) = '".$year."'";
        $pokok = $this->db->query($sql_pokok)->row()->pokok;
        
        $simpanan = $wajib;
        return $simpanan;
    }
    
    function get_pengeluaran($year) {
        $sql = "select sum(o.nominal) as pengeluaran
            from tb_operasional o
            join tb_jenis_transaksi j on (o.id_jenis = j.id)
            where YEAR(o.tanggal) = '".$year."'
                and j.jenis = 'Pengeluaran'
            ";
        return $this->db->query($sql)->row()->pengeluaran;
    }
}