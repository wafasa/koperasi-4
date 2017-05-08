<?php

class M_akuntansi extends CI_Model {
    
    function get_list_rekening($limit = null, $start=null, $search=null){
         $q = '';
         /*
        if ($search['pencarian'] !== '') {
            $q = " and .nama like '%".$search['pencarian']."%' ";
        }
    */
        if (isset($search['id'])) {
            $q.= " and id = '".$search['id']."' ";
        }
        
        if (isset($search['nama']) and $search['nama'] !== '') {
            $q.=" and nama = '".$search['nama']."'";
        }
        if($limit != null){
            $limit = " limit $start , $limit";
        }else{
            $limit = '';
        }

        $sql_root = "select *, null as child from tb_rekening 
                    where id_parent is null $q"; 
        //echo $sql_root;
        $root = $this->db->query($sql_root. $limit)->result();

        foreach ($root as $key => $val) {
            $sql_child = "select *, null as child,
                    replace(kode, '.', '') as coding from tb_rekening 
                    where id_parent ='".$val->id."' order by coding asc";

            $child = $this->db->query($sql_child)->result();
            // child
            if (sizeof($child) > 0) {
                $root[$key]->child = $child;             

            
                foreach ($child as $key2 => $val2) {
                    $sql_child2 = "select *, null as child,
                        replace(kode, '.', '') as coding from tb_rekening 
                        where id_parent ='".$val2->id."' order by coding asc";

                    $child2 = $this->db->query($sql_child2)->result();
                    // child
                    if (sizeof($child2) > 0) {
                        $root[$key]->child[$key2]->child = $child2; 

                        foreach ($child2 as $key3 => $val3) {
                            $sql_child3 = "select *, null as child,
                                replace(kode, '.', '') as coding from tb_rekening 
                                where id_parent ='".$val3->id."' order by coding asc";

                            $child3 = $this->db->query($sql_child3)->result();
                            if (sizeof($child3) > 0) {
                                $root[$key]->child[$key2]->child[$key3]->child = $child3; 
                            
                                foreach ($child3 as $key4 => $val4) {
                                    $sql_child4 = "select *, null as child,
                                        replace(kode, '.', '') as coding  from tb_rekening 
                                        where id_parent ='".$val4->id."' order by coding asc";

                                    $child4 = $this->db->query($sql_child4)->result();
                                    if (sizeof($child4) > 0) {
                                        $root[$key]->child[$key2]->child[$key3]->child[$key4]->child = $child4;

                                        foreach($child4 as $key5 => $val5) {
                                            $sql_child5 = "select *, null as child,
                                                replace(kode, '.', '') as coding  from tb_rekening 
                                                where id_parent ='".$val5->id."' order by coding asc";
                                            $child5 = $this->db->query($sql_child5)->result();
                                            if (sizeof($child5) > 0) {
                                                $root[$key]->child[$key2]->child[$key3]->child[$key4]->child[$key5]->child = $child5;

                                                foreach($child5 as $key6 => $val6) {
                                                    $sql_child6 = "select *, null as child,
                                                        replace(kode, '.', '') as coding  from tb_rekening 
                                                        where id_parent ='".$val6->id."' order by coding asc";

                                                    $child6 = $this->db->query($sql_child6)->result();
                                                    if (sizeof($child6) > 0) {
                                                        $root[$key]->child[$key2]->child[$key3]->child[$key4]->child[$key5]->child[$key6]->child = $child6;
                                                        
                                                        foreach($child6 as $key7 => $val7) {
                                                            $sql_child7 = "select *, null as child,
                                                                replace(kode, '.', '') as coding  from tb_rekening 
                                                                where id_parent ='".$val7->id."' order by coding asc";
                                                            
                                                            $child7 = $this->db->query($sql_child7)->result();
                                                            if (sizeof($child7) > 0) {
                                                                $root[$key]->child[$key2]->child[$key3]->child[$key4]->child[$key5]->child[$key6]->child[$key7]->child = $child7;
                                                                
                                                                foreach($child7 as $key8 => $val8) {
                                                                    $sql_child8 = "select *, null as child,
                                                                        replace(kode, '.', '') as coding  from tb_rekening 
                                                                        where id_parent ='".$val8->id."' order by coding asc";

                                                                    $child8 = $this->db->query($sql_child8)->result();
                                                                    if (sizeof($child8) > 0) {
                                                                        $root[$key]->child[$key2]->child[$key3]->child[$key4]->child[$key5]->child[$key6]->child[$key7]->child[$key8]->child = $child8;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                        }

                                    }
                                }
                            }
                        }

                    }
                }
            }

        }
        $result['data'] = $root;
        $result['jumlah'] = $this->db->query($sql_root)->num_rows();
        return $result;
    }

    function update_data_rekening($data){
        if ($data['id'] === false) {
            // insert
            $this->db->insert('tb_rekening', $data);
            $id = $this->db->insert_id();
        }else{
            // Update
            $id = $data['id'];
            $this->db->where('id', $data['id'])->update('tb_rekening', $data);
        }

        return $id;
    }

    function delete_data_rekening($id){
        $this->db->where('id', $id)->delete('tb_rekening');
    }

    function get_auto_rekening($q, $start, $limit){
        $limitation = " limit $start, $limit";
        $w = "nama like ('%$q%') or kode like ('$q%')";
        $sql = "select * from tb_rekening 
                where $w 
                order by kode";
        $result = $this->db->query($sql.$limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select count(*), IFNULL(nama,'') as nama from tb_rekening where id = '".$value->id_parent."'";
            $result[$key]->parent = $this->db->query($sql_child)->row()->nama;
        }
        $data['data'] = $result;
        $data['total'] = $this->db->query(sql_count_auto("tb_rekening", $w))->row()->count;
        return $data;
    }
    
    function get_auto_rekening_jurnal($q, $start, $limit){
        $select = "select r.* ";
        $count  = "select count(*) as count from (select r.* ";
        $sql = "from tb_rekening r 
                join tb_jurnal j on (r.id = j.id_rekening)
                where r.nama like ('%$q%') or r.kode like ('$q%')
                group by r.id order by r.kode";
        
        $limitation = " limit $start, $limit";
        $data['data'] = $this->db->query($select . $sql . $limitation)->result();
        $data['total'] = $this->db->query($count . $sql . ') as jml')->row()->count;
        return $data;
    }
    
    function neraca_load_data($jenis, $tanggal) {
        $sql = "select *, ROUND((LENGTH(kode) - LENGTH( REPLACE (kode, '.', ''))) / LENGTH('.')) AS count 
            from tb_rekening
             having COUNT = 2 
             and SUBSTR(kode,1,1) = '".$jenis."'";
        $result = $this->db->query($sql)->result();
        foreach ($result as $key => $value) {
            $sql_subtotal = "select IFNULL(sum(j.debet)-sum(j.kredit),0) as subtotal
                from tb_jurnal j
                join tb_rekening r on (j.id_rekening = r.id)
                where r.kode like '".$value->kode."%'
                    and j.tanggal <= '".$tanggal."'
                ";
            $result[$key]->subtotal = $this->db->query($sql_subtotal)->row()->subtotal;
        }
        return $result;
    }
    
    function labarugi_load_data($jenis, $tanggal) {
        $sql = "select *, ROUND((LENGTH(kode) - LENGTH( REPLACE (kode, '.', ''))) / LENGTH('.')) AS count 
            from tb_rekening
             having COUNT = 1 
             and SUBSTR(kode,1,1) = '".$jenis."'";
        $result = $this->db->query($sql)->result();
        foreach ($result as $key => $value) {
            if ($jenis === '4') {
                $sql_subtotal = "select IFNULL(sum(j.debet)-sum(j.kredit),0) as subtotal
                    from tb_jurnal j
                    join tb_rekening r on (j.id_rekening = r.id)
                    where r.kode like '".$value->kode."%'
                        and j.tanggal <= '".$tanggal."'
                    ";
            } else {
                $sql_subtotal = "select IFNULL(sum(j.kredit)-sum(j.debet),0) as subtotal
                    from tb_jurnal j
                    join tb_rekening r on (j.id_rekening = r.id)
                    where r.kode like '".$value->kode."%'
                        and j.tanggal <= '".$tanggal."'
                    ";
            }
            $result[$key]->subtotal = $this->db->query($sql_subtotal)->row()->subtotal;
        }
        return $result;
    }
    
    function get_list_bukubesar($search) {
        $q = NULL;
        if ($search['awal'] !== '') {
            //$q.=" and date(j.waktu) like '".$search['awal']."%'";
        }
        if ($search['rekening'] !== '') {
            $q.=" and r.id = '".$search['rekening']."'";
        }
        $select = "select r.id, r.kode, r.nama ";
        $count  = "select count(*) as count from (select j.* ";
        $sql = "
            from tb_jurnal j
            join tb_rekening r on (j.id_rekening = r.id)
            where j.id is not NULL $q 
                group by r.id order by r.kode asc";
        //echo $sql;
        $limitation = null;
        //$limitation.=" limit $start , $limit";
        $result = $this->db->query($select . $sql . $limitation)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select j.*, (j.no_transaksi) as no_kwitansi, r.nama as rekening, IFNULL(j.transaksi,'-') as transaksi, 
                date(j.waktu) as tanggal, r.kode 
                from tb_jurnal j
                join tb_rekening r on (j.id_rekening = r.id)
                where j.id_rekening = '".$value->id."' 
                and date(j.waktu) like '".$search['awal']."%'";
            $result[$key]->detail = $this->db->query($sql_child)->result();
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count . $sql .') as jml')->row()->count;
        return $data;
    }
    
    function get_list_rincian_bukubesar($id_rekening) {
        
        $sql = "select j.*, r.nama as rekening, IFNULL(j.transaksi,'-') as transaksi, date(j.waktu) as tanggal, r.kode 
            from tb_jurnal j
            join tb_rekening r on (j.id_rekening = r.id)
            where j.id_rekening = '$id_rekening' ";
        //echo "<pre>".$sql."</pre>";
        $query = $this->db->query($sql);
        return $query;
    }
    
    function get_list_jurnal($limit, $start, $search) {
        $q = NULL;
        if ($search['awal'] !== '') {
            $q.=" and j.tanggal between '".$search['awal']."' and '".$search['akhir']."'";
        }
        if ($search['rekening'] !== '') {
            $q.=" and r.id = '".$search['rekening']."'";
        }
        if ($search['jenis'] !== '') {
            $q.=" and j.kelompok_jurnal = '".$search['jenis']."'";
        }
        $select = "select j.* ";
        $count  = "select count(*) as count from (select j.* ";
        $sql = "
            from tb_jurnal j
            join tb_rekening r on (j.id_rekening = r.id)
            where j.id is not NULL 
                 $q 
                group by j.waktu, j.id_history_pembayaran, j.chain, j.no_transaksi 
                order by j.tanggal asc";
        
        $limitation = null;
        if ($limit !== NULL) {
            $limitation = " limit $start , $limit";
        }
        //echo $select . $sql . $limitation; die;
        $result = $this->db->query($select . $sql . $limitation)->result();
        foreach ($result as $key => $value) {
            $r = " and j.id_history_pembayaran = '".$value->id_history_pembayaran."'";
            if ($value->id_history_pembayaran === NULL) {
                $r = " and j.id_history_pembayaran is NULL";
            }
            $sql_child = "select j.*, r.nama as rekening, IFNULL(j.transaksi,'-') as transaksi, r.kode
                from tb_jurnal j
                join tb_rekening r on (j.id_rekening = r.id)
                where j.waktu = '".$value->waktu."'
                    $r 
                    and j.chain = '".$value->chain."'
                    and j.no_transaksi = '".$value->no_transaksi."'
                    
                ";
            //echo $sql_child;
            $result[$key]->detail = $this->db->query($sql_child)->result();
        }
        $data['data'] = $result;
        $data['jumlah'] = $this->db->query($count . $sql . ') as jml')->row()->count;
        return $data;
    }
    
    function save_jurnal($param) {
        $this->db->trans_begin();
        $id_hidden_transaksi = post_safe('hidden_kode_transaksi');
        $id_trans = post_safe('kode_transaksi');
        $nilai = post_safe('nilai');
        $rek   = post_safe('rekening');
        $tanggal = date2mysql(post_safe('tanggal'));
        
        $nilaik= post_safe('nilai_kredit');
        $rekk  = post_safe('rekening_kredit');
        
        $keterangan = post_safe('keterangan');
        if ($id_hidden_transaksi !== '') {
            $this->db->delete('tb_jurnal', array('no_transaksi' => $id_hidden_transaksi));
        }
        if (is_array($rek)) {
            foreach ($rek as $key => $data) {
                $data_jurnal_debet = array(
                    'tanggal' => $tanggal,
                    'chain' => 1,
                    'no_transaksi' => $id_trans,
                    'ket_transaksi' => $keterangan,
                    'id_rekening' => $data,
                    'debet' => currencyToNumber($nilai[$key]),
                    'kelompok_jurnal' => $param['jenis']
                );
                $this->db->insert('tb_jurnal', $data_jurnal_debet);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }
            }
        }
        if (is_array($rekk)) {
            foreach ($rekk as $key => $data2) {
                $data_jurnal_kredit = array(
                    'tanggal' => $tanggal,
                    'chain' => 1,
                    'no_transaksi' => $id_trans,
                    'ket_transaksi' => $keterangan,
                    'id_rekening' => $data2,
                    'kredit' => currencyToNumber($nilaik[$key]),
                    'kelompok_jurnal' => $param['jenis']
                );
                $this->db->insert('tb_jurnal', $data_jurnal_kredit);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $result['status'] = FALSE;
        } else {
            $this->db->trans_commit();
            $result['status'] = TRUE;
        }
        return $result;
    }
    
    function get_data_jurnal($param) {
        $q = NULL;
        if ($param['no_transaksi'] !== '') {
            $q.=" and j.no_transaksi = '".$param['no_transaksi']."'";
        }
        $select = "select j.id, waktu, tanggal, no_transaksi, id_history_pembayaran, chain, ket_transaksi ";
        $count  = "select count(*) as count from (select j.* ";
        $sql = "
            from tb_jurnal j
            join tb_rekening r on (j.id_rekening = r.id)
            where j.id is not NULL 
                 $q 
                group by j.waktu, j.id_history_pembayaran, j.chain, j.no_transaksi 
                order by j.tanggal asc";
        
        
        //echo $select . $sql . $limitation; die;
        $result = $this->db->query($select . $sql)->result();
        foreach ($result as $key => $value) {
            $r = " and j.id_history_pembayaran = '".$value->id_history_pembayaran."'";
            if ($value->id_history_pembayaran === NULL) {
                $r = " and j.id_history_pembayaran is NULL";
            }
            $sql_child = "select j.id_rekening, j.debet, j.kredit, r.nama as rekening, IFNULL(j.transaksi,'-') as transaksi, r.kode
                from tb_jurnal j
                join tb_rekening r on (j.id_rekening = r.id)
                where j.waktu = '".$value->waktu."'
                    $r 
                    and j.chain = '".$value->chain."'
                    and j.no_transaksi = '".$value->no_transaksi."'
                    
                ";
            //echo $sql_child;
            $result[$key]->detail = $this->db->query($sql_child)->result();
        }
        $data['data'] = $result;   
        return $data;   
    }
    
    function arus_kas_load_data($param) {
        $sql = "select *, ROUND((LENGTH(kode) - LENGTH( REPLACE (kode, '.', ''))) / LENGTH('.')) AS count 
            from tb_rekening
             having COUNT = 2
             and SUBSTR(kode,1,4) = '".$param['kode']."'";
        $result = $this->db->query($sql)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select * from tb_rekening where id_parent = '".$value->id."'";
            $child = $this->db->query($sql_child)->result();
            $result[$key]->child = $child;
            foreach ($child as $key2 => $value2) {
                $sql_subtotal = "select IFNULL(sum(j.debet)-sum(j.kredit),0) as subtotal
                    from tb_jurnal j
                    join tb_rekening r on (j.id_rekening = r.id)
                    where r.kode like '".$value2->kode."%' 
                        ";
                $this_year = " and YEAR(j.tanggal) = '".$param['tahun']."'";
                $last_year = " and YEAR(j.tanggal) = '".($param['tahun']-1)."'";
                //echo $sql_subtotal . $this_year;
                $result[$key]->child[$key2]->this_year = $this->db->query($sql_subtotal . $this_year)->row()->subtotal;
                $result[$key]->child[$key2]->last_year = $this->db->query($sql_subtotal . $last_year)->row()->subtotal;
            }
        }
        $data['data'] = $result;
        $data['this_year'] = $param['tahun'];
        $data['last_year'] = ($param['tahun']-1);
        return $data;
    }
}