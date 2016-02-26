<?php

class M_masterdata extends CI_Model {
    
    /*BERITA*/
    function get_list_rka($limit, $start, $search) {
        $limitation = null; 
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        if ($limit !== NULL) {
            $limitation = " limit $start , $limit";
        }
        
        $sql = "select * from tb_rka where id_parent is NULL $q order by id";
        $query = $this->db->query($sql.$limitation)->result();
        //echo $sql . $limitation;
        foreach ($query as $key1 => $val1) {
            $sql_child = "select * from tb_rka where id_parent = '".$val1->id."'";
            $result2 = $this->db->query($sql_child)->result();
            $query[$key1]->child1 = $result2;
            
            
            
            
            foreach ($result2 as $key2 => $val2) {
                $sql_child2 = "select * from tb_rka where id_parent = '".$val2->id."'";
                $result3 = $this->db->query($sql_child2)->result();
                $query[$key1]->child1[$key2]->child2 = $result3;
                
                $sql_total = "select sum(r5.nominal) as total
                from tb_rka r1 
                join tb_rka r2 on (r1.id = r2.id_parent)
                join tb_rka r3 on (r2.id = r3.id_parent)
                join tb_rka r4 on (r3.id = r4.id_parent)
                join tb_rka r5 on (r4.id = r5.id_parent)
                where r2.id = '".$val2->id."'";
                $query[$key1]->child1[$key2]->total = $this->db->query($sql_total)->row()->total;
            
                foreach ($result3 as $key3 => $val3) {
                    $sql_child3 = "select * from tb_rka where id_parent = '".$val3->id."'";
                    $result4 = $this->db->query($sql_child3)->result();
                    $query[$key1]->child1[$key2]->child2[$key3]->child3 = $result4;
                    foreach ($result4 as $key4 => $val4) {
                        $sql_child4 = "select * from tb_rka where id_parent = '".$val4->id."'";
                        $result5 = $this->db->query($sql_child4)->result();
                        $query[$key1]->child1[$key2]->child2[$key3]->child3[$key4]->child4 = $result5;
                    }
                }
            }
        }
        $queryAll = $this->db->query($sql);
        $data['data'] = $query;
        $data['jumlah'] = $queryAll->num_rows();
        //die(json_encode($data));
        return $data;
    }
    
    function save_rka() {
        $id = post_safe('id');
        $data_array = array(
            'id_parent' => (post_safe('parent') !== '')?post_safe('parent'):NULL,
            'kode' => post_safe('judul'),
            'nama_program' => post_safe('isi'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'semester1' => (post_safe('semester1') !== '')?post_safe('semester1'):NULL,
            'semester2' => (post_safe('semester2') !== '')?post_safe('semester2'):NULL,
            'id_tahun_anggaran' => post_safe('id_tahun_anggaran')
        );
        if ($id === '') {
            $this->db->insert('tb_rka', $data_array);
            $result['act'] = 'add';
            $result['id'] = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('tb_rka', $data_array);
            $result['act'] = 'edit';
            $result['id'] = $id;
        }
        return $result;
        
    }
    
    function get_auto_rka($param, $start, $limit) {
        $q = NULL;
        
        $limitation = " limit $start, $limit";
        $select = "select *, LENGTH(kode) as panjang_kode ";
        $count = "select count(id) as count ";
        $sql = "from tb_rka
            where LENGTH(kode) <= '10' and (nama_program like ('%".$param['search']."%') or kode like ('".$param['search']."%')) $q order by kode";
        
        $data['data'] = $this->db->query($select.$sql.$limitation)->result();
        if ($this->db->query($select.$sql.$limitation)->num_rows() > 0) {
            $data['total'] = $this->db->query($count.$sql)->row()->count;
        }
        return $data;
    }
    
    function get_auto_rka_trans($param, $start, $limit) {
        $q = NULL;
        if ($param['level'] === '') {
            $q.=" and LENGTH(kode) > '8'";
        }
        if ($param['level'] !== '') {
            $q.=" and LENGTH(kode) = '".($param['level']+($param['level']-1))."'";
        }
        if ($param['parent'] !== '') {
            $q.=" and id_parent = '".$param['parent']."'";
        }
        $limitation = " limit $start, $limit";
        $select = "select *";
        $count = "select count(id) as count ";
        $sql = "from tb_rka
            where (nama_program like ('%".$param['search']."%') or kode like ('".$param['search']."%')) $q order by kode";
        
        $data['data'] = $this->db->query($select.$sql.$limitation)->result();
        if ($this->db->query($select.$sql.$limitation)->num_rows() > 0) {
            $data['total'] = $this->db->query($count.$sql)->row()->count;
        }
        return $data;
    }
    
    function get_auto_penerimaan($param, $start, $limit) {
        $q = NULL;
        
        $limitation = " limit $start, $limit";
        $select = "select *, LENGTH(kode) as panjang_kode ";
        $count = "select count(id) as count ";
        $sql = "from tb_master_penerimaan
            where id_parent is NULL and (nama_program like ('%".$param['search']."%') or kode like ('".$param['search']."%')) $q order by kode";
        
        $data['data'] = $this->db->query($select.$sql.$limitation)->result();
        $data['total'] = $this->db->query($count.$sql)->row()->count;
        
        return $data;
    }
    
    function get_rka($id_rka) {
        $sql = "select r1.*, LENGTH(r2.kode) as panjang_kode, IFNULL(r2.nama_program,'') as parent_name, IFNULL(r2.kode,'') as parent_code
            from tb_rka r1 
            left join tb_rka r2 on (r1.id_parent = r2.id)
            where r1.id = '".$id_rka."'
            ";
        $data['data'] = $this->db->query($sql)->row();
        return $data;
    }
    
    /*Penerimaan*/
    
    function get_list_penerimaan($limit, $start, $search) {
        //$limitation = null; 
        $q = NULL;
        if ($search['id'] !== '') {
            $q.=" and id = '".$search['id']."'";
        }
        $limitation =" limit $start , $limit";
        
        $sql = "select * from tb_master_penerimaan where id_parent is NULL $q order by id";
        $query = $this->db->query($sql.$limitation)->result();
        //echo $sql . $limitation;
        foreach ($query as $key1 => $val1) {
            $sql_total = "select sum(nominal) as total from tb_master_penerimaan where id_parent = '".$val1->id."'";
            $query[$key1]->total = $this->db->query($sql_total)->row()->total;
            
            $sql_child = "select * from tb_master_penerimaan where id_parent = '".$val1->id."'";
            $result2 = $this->db->query($sql_child)->result();
            $query[$key1]->child1 = $result2;
            
        }
        $queryAll = $this->db->query($sql);
        $data['data'] = $query;
        $data['jumlah'] = $queryAll->num_rows();
        //die(json_encode($data));
        return $data;
    }
    
    function save_penerimaan() {
        $id = post_safe('id');
        $data_array = array(
            'id_parent' => (post_safe('parent') !== '')?post_safe('parent'):NULL,
            'kode' => post_safe('judul'),
            'nama_program' => post_safe('isi'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'id_tahun_anggaran' => post_safe('id_tahun_anggaran')
        );
        if ($id === '') {
            $this->db->insert('tb_master_penerimaan', $data_array);
            $result['act'] = 'add';
            $result['id'] = $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            $this->db->update('tb_master_penerimaan', $data_array);
            $result['act'] = 'edit';
            $result['id'] = $id;
        }
        return $result;
        
    }
    
    function get_penerimaan($id_master_penerimaan) {
        $sql = "select r1.*, LENGTH(r2.kode) as panjang_kode, IFNULL(r2.nama_program,'') as parent_name, IFNULL(r2.kode,'') as parent_code
            from tb_master_penerimaan r1 
            left join tb_master_penerimaan r2 on (r1.id_parent = r2.id)
            where r1.id = '".$id_master_penerimaan."'
            ";
        $data['data'] = $this->db->query($sql)->row();
        return $data;
    }
    
}