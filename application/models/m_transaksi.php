<?php

class M_transaksi extends CI_Model {
    
    function get_sisa_saldo_koperasi() {
        $sql = "select SUM(masuk)-SUM(keluar) as sisa from tb_arus_kas";
        return $this->db->query($sql)->row()->sisa;
    }
    /*Pembiayaan*/
    function get_list_pembiayaans($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and d.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and p.tgl_pinjam between '".date2mysql($search['awal'])."' and '".  date2mysql($search['akhir'])."'";
        }
        if ($search['nama'] !== '') {
            $q.=" and d.nama like ('%".$search['nama']."%')";
        }
        if ($search['alamat'] !== '') {
            $q.=" and d.alamat like ('%".$search['alamat']."%')";
        }
        if ($search['norek'] !== '') {
            $q.=" and d.nomor_rekening = '".$search['norek']."'";
        }
        
        $select = "select d.*, p.*, dd.*, d.id ";
        $count  = "select count(p.id) as count ";
        $sql = "
            from tb_pinjaman p
            join tb_debitur d on (p.id_debitur = d.id)
            join tb_detail_debitur dd on (dd.id_debitur = d.id)
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
    
    function create_nomor_rek_pinjaman() {
        $sql = "select convert(SUBSTR(nomor_rekening, 3, 6),decimal) as nomor from tb_debitur order by nomor desc limit 1";
        $get = $this->db->query($sql)->row();
        if (!isset($get->nomor)) {
            $auto = '000001';
        } else {
            $auto = str_pad((string) ($get->nomor + 1), 6, "0", STR_PAD_LEFT);
        }
        return 'SB'.$auto;
    }
    
    function save_debitur($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_debitur', $data);
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('tb_debitur', $data);
        }
    }
    
    function save_detail_debitur($data) {
        $get_check = $this->db->get_where('tb_detail_debitur', array('id_debitur' => $data['id_debitur']))->row();
        if (isset($get_check->id)) {
            $this->db->where('id_debitur', $data['id_debitur']);
            $this->db->update('tb_detail_debitur', $data);
        } else {
            $this->db->insert('tb_detail_debitur', $data);
        }
    }
    
    function pembiayaan_flat($data) {
        $this->db->insert('tb_pinjaman', $data);
    }
    
    function save_arus_kas($data) {
        $this->db->insert('tb_arus_kas', $data);
    }
    
    function save_detail_pinjaman($data) {
        $this->db->insert('tb_detail_pinjaman', $data);
    }
    
    function inuitas($agt,$jml,$lama) {
	$jum  = gabung($jml);
	$data = mysql_fetch_array(mysql_query("select * from tb_jasa where jenis = '2'"));
	$jasa = $data[jasa]/100;
	$var1 = $jum * $jasa;
	$pmbg1= pow((1+$jasa),$lama);
	$pmbg2= 1-(1/$pmbg1);
	$jml_angsuran = floor($var1 / $pmbg2); // total angsuran
	$pokok= $jml_angsuran - $var1;
	$sisa1= $jum - $pokok;
	$janji= $jml_angsuran * $lama;
	
	$y    = mktime(0, 0, 0, date("m")+$lama, date("d"), date("Y"));
	$tgg  = date("Y-m-d",$y);
	
	$x    = mktime(0, 0, 0, date("m")+1, date("d"), date("Y"));
	$tgl  = date("Y-m-d",$x);
	if ($jml <= 2000000) {
		$que = mysql_query("insert into tb_pinjaman values ('',now(),'$tgg','$jum','$janji','$jml','$pokok','$var1','2','12','0')");
	}
	else if ($jml >= 2500000) {
		$que = mysql_query("insert into tb_pinjaman values ('',now(),'$tempo','$jum','$janji','$angs','$pokok','$jasa','1','$lama','0')");
	}
	$sql  = mysql_query("insert into tb_angsuran values ('','$tgl','','$pokok','$var1','$jml_angsuran','$sisa1')");
	for ($i=2;$i<=$lama;$i++) {
	$x    = mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
	$tgl  = date("Y-m-d",$x);
	//$dt	  = mysql_fetch_array(mysql_query("select sisa_pokok from tb_angsuran 
	//$sql  = mysql_query("insert into tb_angsuran values ('','$tgl','',''
	}
    }
    
    /*END OF PEMBIAYAAN*/
    
    function get_list_angsurans($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and dp.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and dp.tgl_bayar between '".date2mysql($search['awal'])."' and '".date2mysql($search['akhir'])."'";
        }
        if ($search['norek'] !== '') {
            $q.=" and d.nomor_rekening = '".$search['norek']."'";
        }
        if ($search['nama'] !== '') {
            $q.=" and d.nama like ('%".$search['nama']."%')";
        }
        
        $select = "select p.*, d.nama, d.nomor_rekening, dp.tgl_bayar, dp.angsuran_ke ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_detail_pinjaman dp
            join tb_pinjaman p on (dp.id_pinjaman = p.id)
            join tb_debitur d on (p.id_debitur = d.id)
            where dp.tgl_bayar is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by dp.tgl_bayar desc";
        //echo $sql . $q . $order. $limitation;
        
        $data['data'] = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_angsuran() {
        $this->db->trans_begin();
        $data = array(
            'id_pinjaman' => post_safe('norek'),
            'tanggal' => date2mysql(post_safe('tanggal')),
            'nominal_angsuran' => currencyToNumber(post_safe('nominal_angsuran')),
            'kali_angsur' => post_safe('jml_kali_angsur')
        );
        $sql = "select dp.*, d.nama, d.nomor_rekening 
            from tb_detail_pinjaman dp
            join tb_pinjaman p on (dp.id_pinjaman = p.id)
            join tb_debitur d on (p.id_debitur = d.id)
            where id_pinjaman = '".$data['id_pinjaman']."' 
                and tgl_bayar is NULL 
                order by angsuran_ke asc 
                limit ".$data['kali_angsur']."";
        $get = $this->db->query($sql)->result();
        
        foreach ($get as $value) {
            $this->db->where('id', $value->id);
            $this->db->update('tb_detail_pinjaman', array('tgl_bayar' => date("Y-m-d")));
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            $arus_kas = array(
                'transaksi' => 'Angsuran',
                'id_transaksi' => $value->id,
                'masuk' => currencyToNumber($value->jml_angsuran),
                'keterangan' => 'Angsuran ke '.$value->angsuran_ke.' '.$value->nomor_rekening.' '.$value->nama,
                'id_user' => $this->session->userdata('id_user')
            );
            $this->save_arus_kas($arus_kas);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
            $result['id'] = $data['id_pinjaman'];
        }
        return $result;
    }
    
    function get_auto_rekening_pinjaman($search, $start, $limit) {
        $q = NULL;
        
        $limitation = " limit $start, $limit";
        $select = "select p.*, d.nama, d.alamat, d.nomor_rekening ";
        $count = "select count(*) as count ";
        $sql = "from tb_pinjaman p
            join tb_debitur d on (p.id_debitur = d.id)
            where (d.nama like ('%".$search['search']."%') or d.nomor_rekening like ('%".$search['search']."%')) 
            and p.sisa_angsuran != '0'
                $q 
            order by d.nomor_rekening";
        $result = $this->db->query($select.$sql.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select * from tb_detail_pinjaman where id_pinjaman = '".$value->id."' and tgl_bayar is NULL";
            $result[$key]->sisa_kali_angsuran = $this->db->query($sql_child)->result();
        }
        $data['data'] = $result;
        $data['total'] = $this->db->query($count.$sql)->row()->count;
        
        return $data;
    }
    
    /*Penerimaan Pengeluaran*/
    
    function get_list_penerimaan_pengeluarans($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and o.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            //$q.=" and dp.tgl_bayar between '".$search['awal']."' and '".$search['akhir']."'";
        }
        
        $select = "select o.*, jt.nama as jenis";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_operasional o
            join tb_jenis_transaksi jt on (o.id_jenis = jt.id)
            where o.id is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by o.id desc";
        //echo $sql . $q . $order. $limitation;
        
        $data['data'] = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_penerimaan_pengeluaran($data) {
        if ($data['id'] === '') {
            $this->db->insert('tb_operasional', $data);
            $id_operasional = $this->db->insert_id();
            
            $get_trans = $this->db->get_where('tb_jenis_transaksi', array('id' => $data['id_jenis']))->row();
            if ($get_trans->jenis === 'Pengeluaran') {
                $arus_kas = array(
                    'transaksi' => 'Lain-lain',
                    'id_transaksi' => $id_operasional,
                    'keluar' => currencyToNumber($data['nominal']),
                    'keterangan' => 'Pengeluaran '.  $data['keterangan'],
                    'id_user' => $this->session->userdata('id_user')
                );
            } else {
                $arus_kas = array(
                    'transaksi' => 'Lain-lain',
                    'id_transaksi' => $id_operasional,
                    'masuk' => currencyToNumber($data['nominal']),
                    'keterangan' => 'Pemasukkan '.  $data['keterangan'],
                    'id_user' => $this->session->userdata('id_user')
                );
            }
            $this->m_transaksi->save_arus_kas($arus_kas);
            $result['act'] = 'add';
        } else {
            $this->db->delete('tb_arus_kas', array('transaksi' => 'Lain-lain', 'id_transaksi' => $data['id']));
            $this->db->where('id', $data['id']);
            $this->db->update('tb_operasional', $data);
            $get_trans = $this->db->get_where('tb_jenis_transaksi', array('id' => $data['id_jenis']))->row();
            if ($get_trans->jenis === 'Pengeluaran') {
                $arus_kas = array(
                    'transaksi' => 'Lain-lain',
                    'id_transaksi' => $data['id'],
                    'keluar' => currencyToNumber($data['nominal']),
                    'keterangan' => 'Pengeluaran '.  $data['keterangan'],
                    'id_user' => $this->session->userdata('id_user')
                );
            } else {
                $arus_kas = array(
                    'transaksi' => 'Lain-lain',
                    'id_transaksi' => $data['id'],
                    'masuk' => currencyToNumber($data['nominal']),
                    'keterangan' => 'Pemasukkan '.  $data['keterangan'],
                    'id_user' => $this->session->userdata('id_user')
                );
            }
            $this->save_arus_kas($arus_kas);
            $result['act'] = 'edit';
        }
        return $result;
    }
    
    /*Tabungan*/
    
    function get_list_tabungans($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and t.id = '".$search['id']."'";
        }
        if ($search['id_anggota'] !== '') {
            $q.=" and a.id = '".$search['id_anggota']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            //$q.=" and dp.tgl_bayar between '".$search['awal']."' and '".$search['akhir']."'";
        }
        
        $select = "select a.*, t.saldo ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_tabungan t
            join tb_anggota a on (t.id_anggota = a.id)
            where a.id is not NULL ";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by a.id desc";
        //echo $select . $sql . $q . $order. $limitation;
        
        $data['data'] = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function get_auto_rekening_tabungan($search, $start, $limit) {
        $q = NULL;
        
        $limitation = " limit $start, $limit";
        $select = "select t.*, a.nama, a.alamat, a.no_rekening ";
        $count = "select count(*) as count ";
        $sql = "from tb_tabungan t
            join tb_anggota a on (t.id_anggota = a.id)
            where (a.nama like ('%".$search['search']."%') or a.no_rekening like ('%".$search['search']."%')) 
            $q 
            order by a.no_rekening";
        $result = $this->db->query($select.$sql.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select IFNULL(sum(masuk)-sum(keluar),0) as saldo 
                from tb_detail_tabungan 
                where id_tabungan = '".$value->id."'";
            $result[$key]->saldo = $this->db->query($sql_child)->row()->saldo;
        }
        $data['data'] = $result;
        $data['total'] = $this->db->query($count.$sql)->row()->count;
        
        return $data;
    }
    
    function save_setoran_tabungan() {
        $this->db->trans_begin();
        $param = array(
            'id_tabungan' => post_safe('norek'),
            'tanggal' => date("Y-m-d"),
            'masuk' => currencyToNumber(post_safe('nominal_tabungan')),
            'sandi' => '1'
        );
        $this->db->insert('tb_detail_tabungan', $param);
        $id_detail_tabungan = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        }
        
        $sql = "select a.nama, a.no_rekening 
            from tb_anggota a
            join tb_tabungan t on (t.id_anggota = a.id)
            where t.id = '".  post_safe('norek')."'
            ";
        $data_anggota = $this->db->query($sql)->row();
        $arus_kas = array(
            'transaksi' => 'Tabungan',
            'id_transaksi' => $id_detail_tabungan,
            'masuk' => currencyToNumber(post_safe('nominal_tabungan')),
            'keterangan' => 'Tabungan '.$data_anggota->no_rekening.' '.$data_anggota->nama,
            'id_user' => $this->session->userdata('id_user')
        );
        $this->m_transaksi->save_arus_kas($arus_kas);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
            $result['id'] = $id_detail_tabungan;
        }
        return $result;
    }
    
    function create_nomor_rek_tabungan() {
        $sql = "select convert(SUBSTR(no_rekening, 1, 5),decimal) as nomor from tb_anggota where tgl_masuk like ('%".date("Y-m")."%') order by nomor desc limit 1";
        $get = $this->db->query($sql)->row();
        if (!isset($get->nomor)) {
            $auto = '00001';
        } else {
            $auto = str_pad((string) ($get->nomor + 1), 5, "0", STR_PAD_LEFT);
        }
        return $auto.date("my").'TMN';
    }
    
    function save_pembukaan_tabungan() {
        $this->db->trans_begin();
        $data_anggota = array(
            'id' => post_safe('id'),
            'no_ktp' => post_safe('noktp'),
            'nama' => post_safe('nama'),
            'alamat' => post_safe('alamat'),
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
        }
        
        if ($data_anggota['id'] === '') {
            $data_tabungan = array(
                'id_anggota' => $id_anggota,
                'tanggal_update' => date("Y-m-d"),
                'status_bunga' => '0',
                'saldo' => currencyToNumber(post_safe('jumlah')),
                'aktif' => 'Ya'
            );
            $this->db->insert('tb_tabungan', $data_tabungan);
            $id_tabungan = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            
            $data_detail_tabungan = array(
                'id_tabungan' => $id_tabungan,
                'tanggal' => date("Y-m-d"),
                'masuk' => currencyToNumber(post_safe('jumlah')),
                'sandi' => '1'
            );
            $this->db->insert('tb_detail_tabungan', $data_detail_tabungan);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            
            $arus_kas = array(
                'transaksi' => 'Tabungan',
                'id_transaksi' => $id_tabungan,
                'masuk' => currencyToNumber(post_safe('jumlah')),
                'keterangan' => 'Pembukaan tabungan '.$data_anggota['no_rekening'].' '.$data_anggota['nama'],
                'id_user' => $this->session->userdata('id_user')
            );
            $this->save_arus_kas($arus_kas);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
            $result['id'] = $id_tabungan;
        }
        return $result;
    }
    
    function get_list_setoran_tabungans($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and p.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            //$q.=" and dp.tgl_bayar between '".$search['awal']."' and '".$search['akhir']."'";
        }
        
        $select = "select a.*, t.id as id_tabungan, dt.id as id_dt, dt.tanggal, dt.masuk, dt.keluar ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_detail_tabungan dt
            join tb_tabungan t on (dt.id_tabungan = t.id)
            join tb_anggota a on (t.id_anggota = a.id)
            where dt.keluar = '0'";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by dt.id desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select IFNULL(sum(masuk)-sum(keluar),0) as awal 
                from tb_detail_tabungan 
                where id < '".$value->id_dt ."'
                and id_tabungan = '".$value->id_tabungan."'";
            $result[$key]->awal = $this->db->query($sql_child)->row()->awal;
            
            $sql_child2 = "select IFNULL(sum(masuk)-sum(keluar),0) as saldo 
                from tb_detail_tabungan 
                where id_tabungan = '".$value->id_tabungan."'";
            $result[$key]->saldo = $this->db->query($sql_child2)->row()->saldo;
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function get_list_penarikan_tabungans($limit = null, $start = null, $search = null) {
        $q = null; $r = NULL;
        if ($search['id'] !== '') {
            $q.=" and p.id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            //$q.=" and dp.tgl_bayar between '".$search['awal']."' and '".$search['akhir']."'";
        }
        
        $select = "select a.*, t.id as id_tabungan, dt.id as id_dt, dt.tanggal, dt.masuk, dt.keluar ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_detail_tabungan dt
            join tb_tabungan t on (dt.id_tabungan = t.id)
            join tb_anggota a on (t.id_anggota = a.id)
            where dt.masuk = '0'";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by dt.id desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select IFNULL(sum(masuk)-sum(keluar),0) as awal 
                from tb_detail_tabungan 
                where id < '".$value->id_dt ."'
                and id_tabungan = '".$value->id_tabungan."'";
            $result[$key]->awal = $this->db->query($sql_child)->row()->awal;
            
            $sql_child2 = "select IFNULL(sum(masuk)-sum(keluar),0) as saldo 
                from tb_detail_tabungan 
                where id_tabungan = '".$value->id_tabungan."'";
            $result[$key]->saldo = $this->db->query($sql_child2)->row()->saldo;
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_penarikan_tabungan() {
        $this->db->trans_begin();
        $param = array(
            'id_tabungan' => post_safe('norek'),
            'tanggal' => date("Y-m-d"),
            'keluar' => currencyToNumber(post_safe('nominal_tabungan')),
            'sandi' => '1'
        );
        $this->db->insert('tb_detail_tabungan', $param);
        $id_detail_tabungan = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        }
        
        $sql = "select a.nama, a.no_rekening 
            from tb_anggota a
            join tb_tabungan t on (t.id_anggota = a.id)
            where t.id = '".  post_safe('norek')."'
            ";
        $data_anggota = $this->db->query($sql)->row();
        $arus_kas = array(
            'transaksi' => 'Tabungan',
            'id_transaksi' => $id_detail_tabungan,
            'keluar' => currencyToNumber(post_safe('nominal_tabungan')),
            'keterangan' => 'Tabungan '.$data_anggota->no_rekening.' '.$data_anggota->nama,
            'id_user' => $this->session->userdata('id_user')
        );
        $this->save_arus_kas($arus_kas);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
            $result['id'] = $id_detail_tabungan;
        }
        return $result;
    }
    
    function get_list_koreksi_saldos($limit = null, $start = null, $search = null) {
        $q = null;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        if ($search['awal'] !== '' and $search['akhir'] !== '') {
            $q.=" and date(waktu) between '".$search['awal']."' and '".$search['akhir']."'";
        }
        
        $select = "select * ";
        $count  = "select count(*) as count ";
        $sql = " 
            from tb_arus_kas
            where transaksi = 'Koreksi'";
        $limitation = null;
        if ($limit !== NULL) {
            $limitation.=" limit $start , $limit";
        }
        $order=" order by id desc";
        //echo $sql . $q . $order. $limitation;
        $result = $this->db->query($select.$sql.$q.$order.$limitation)->result();
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count.$sql.$q)->row()->count;
        return $data;
    }
    
    function save_koreksi_saldo() {
        if (post_safe('penyesuaian') < 0) {
            $arus_kas = array(
                'transaksi' => 'Koreksi',
                'id_transaksi' => NULL,
                'keluar' => currencyToNumber(abs(post_safe('penyesuaian'))),
                'keterangan' => post_safe('keterangan'),
                'id_user' => $this->session->userdata('id_user')
            );
        } else {
            $arus_kas = array(
                'transaksi' => 'Koreksi',
                'id_transaksi' => NULL,
                'masuk' => currencyToNumber(abs(post_safe('penyesuaian'))),
                'keterangan' => post_safe('keterangan'),
                'id_user' => $this->session->userdata('id_user')
            );
        }
        $this->save_arus_kas($arus_kas);
        return TRUE;
    }
    
    
}
?>
