<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Transaksi extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->limit = 10;
        $this->load->model(array('m_transaksi'));
    
        $id_user = $this->session->userdata('id_user');
        if (empty($id_user)) {
            $this->response(array('error' => 'Anda belum login'), 401);
        }
    }
    
    function sisa_saldo_get() {
        $data = $this->m_transaksi->get_sisa_saldo_koperasi();
        $this->response($data, 200);
    }
    /*Pembiayaan*/
    
    function pembiayaans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'norek' => get_safe('koderekening'),
        );
        
        $data = $this->m_transaksi->get_list_pembiayaans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function pembiayaan_post() {
        $this->db->trans_begin();
        $data_debitur = array(
            'id' => post_safe('id')
        );
        
        if ($data_debitur['id'] === '') {
            // insert pembiayaan
            
            $jum  = currencyToNumber(post_safe('jumlah'));
            $data = $this->db->get_where('tb_setting_administrasi')->row();
            $jasa = $jum * ($data->bunga_pinjaman/100);
            $pokok= ceil($jum / post_safe('lama'));
            $janji= ($pokok * post_safe('lama')) + ($jasa * post_safe('lama'));
            $angs = $jasa + $pokok;
            $varia = mktime(0, 0, 0, date("m")+post_safe('lama'), date("d"), date("Y"));
            $tempo= date("Y-m-d",$varia);
            $data_pinjaman = array(
                'id_debitur' => post_safe('nama'),
                'tgl_pinjam' => date2mysql(post_safe('tanggal')),
                'tgl_tempo' => $tempo,
                'jml_pinjaman' => currencyToNumber(post_safe('jumlah')),
                'ttl_pengembalian' => $janji,
                'bsr_angsuran' => $angs,
                'angsuran_pokok' => $pokok,
                'jasa_angsuran' => $jasa,
                'sisa_angsuran' => $janji,
                'jenis_pinjaman' => post_safe('jenis_pinjaman'),
                'lama_pinjaman' => post_safe('lama')
            );
            $this->m_transaksi->pembiayaan_flat($data_pinjaman);
            $id_pinjaman = $this->db->insert_id();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            
            $data_pelengkap = array(
                'id_pinjaman' => $id_pinjaman,
                'nama_psg' => post_safe('nama_psg'),
                'pekerjaan_psg' => post_safe('pekerjaan_psg'),
                'status_rumah' => post_safe('status_rumah'),
                'penghasilan_bln' => currencyToNumber(post_safe('penghasilan')),
                'pengeluaran_bln' => currencyToNumber(post_safe('pengeluaran')),
                'jaminan' => post_safe('jaminan'),
                'rencana_pembiayaan' => post_safe('rencana_pembiayaan')
            );
            $this->db->insert('tb_detail_debitur',$data_pelengkap);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            
            for ($i = 1; $i <= post_safe('lama'); $i++) {
                $x= mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
                $jatuh_tempo_perbulan = date("Y-m-d",$x);
                $newsisa = $pokok * (post_safe('lama') - $i);
                $detail_pinjaman = array(
                    'id_pinjaman' => $id_pinjaman,
                    'angsuran_ke' => $i,
                    'jatuh_tempo' => $jatuh_tempo_perbulan,
                    'pokok' => $pokok,
                    'jasa' => $jasa,
                    'jml_angsuran' => $angs,
                    'sisa_pokok' => $newsisa,
                    'status_bayar' => 'Belum'
                );
                $this->m_transaksi->save_detail_pinjaman($detail_pinjaman);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }
            }

            $arus_kas = array(
                'transaksi' => 'Pembiayaan',
                'id_transaksi' => $id_pinjaman,
                'keluar' => currencyToNumber(post_safe('jumlah')),
                'keterangan' => 'Pembiayaan '. post_safe('norekening').' '.post_safe('nama_anggota'),
                'id_user' => $this->session->userdata('id_user')
            );
            $this->m_transaksi->save_arus_kas($arus_kas);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
            
            $get_adm = $this->db->get_where('tb_setting_administrasi')->row();
            $bea_adm = currencyToNumber(post_safe('jumlah'))*($get_adm->administrasi/100);
            $data_adpro = array(
                'id_pinjaman' => $id_pinjaman,
                'tgl_input' => date2mysql(post_safe('tanggal')),
                'biaya_adm' => $bea_adm,
                'biaya_ca' => $get_adm->calon_agt,
                'survey' => $get_adm->survey,
                'stofmap' => $get_adm->stofmap
            );
            $this->db->insert('tb_adpro', $data_adpro);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            }
        } else {
            
            $check_angsuran = $this->db->query("select count(*) as count from tb_detail_pinjaman where id_pinjaman = '".post_safe('id')."' and tgl_bayar is not NULL")->row()->count;
            
            $jum  = currencyToNumber(post_safe('jumlah'));
            $data = $this->db->get_where('tb_setting_administrasi')->row();
            $jasa = $jum * ($data->bunga_pinjaman/100);
            $pokok= ceil($jum / post_safe('lama'));
            $janji= ($pokok * post_safe('lama')) + ($jasa * post_safe('lama'));
            $angs = $jasa + $pokok;
            $varia = mktime(0, 0, 0, date("m")+post_safe('lama'), date("d"), date("Y"));
            $tempo= date("Y-m-d",$varia);
            $data_pinjaman = array(
                'id_debitur' => post_safe('nama'),
                'tgl_pinjam' => date2mysql(post_safe('tanggal')),
                'tgl_tempo' => $tempo,
                'jml_pinjaman' => currencyToNumber(post_safe('jumlah')),
                'ttl_pengembalian' => $janji,
                'bsr_angsuran' => $angs,
                'angsuran_pokok' => $pokok,
                'jasa_angsuran' => $jasa,
                'sisa_angsuran' => $janji,
                'jenis_pinjaman' => post_safe('jenis_pinjaman'),
                'lama_pinjaman' => post_safe('lama')
            );
            if ($check_angsuran > 0) {
                $data_pinjaman = array(
                    'id_debitur' => post_safe('nama'),
                    'tgl_pinjam' => date2mysql(post_safe('tanggal_disetujui'))
                );
            }
            $this->db->where('id', post_safe('id'));
            $this->db->update('tb_pinjaman',$data_pinjaman);
            $data_pelengkap = array(
                'id_pinjaman' => post_safe('id'),
                'nama_psg' => post_safe('nama_psg'),
                'pekerjaan_psg' => post_safe('pekerjaan_psg'),
                'status_rumah' => post_safe('status_rumah'),
                'penghasilan_bln' => currencyToNumber(post_safe('penghasilan')),
                'pengeluaran_bln' => currencyToNumber(post_safe('pengeluaran')),
                'jaminan' => post_safe('jaminan'),
                'rencana_pembiayaan' => post_safe('rencana_pembiayaan')
            );
            $this->db->where('id_pinjaman', post_safe('id'));
            $this->db->update('tb_detail_debitur',$data_pelengkap);
            $id_pinjaman = post_safe('id');
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $result['status'] = FALSE;
            } 
            
            if ($check_angsuran === 0) {
            
                $this->db->delete('tb_detail_pinjaman', array('id_pinjaman' => $id_pinjaman));
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                } 

                for ($i = 1; $i <= post_safe('lama'); $i++) {
                    $x= mktime(0, 0, 0, date("m")+$i, date("d"), date("Y"));
                    $jatuh_tempo_perbulan = date("Y-m-d",$x);
                    $newsisa = $pokok * (post_safe('lama') - $i);
                    $detail_pinjaman = array(
                        'id_pinjaman' => $id_pinjaman,
                        'angsuran_ke' => $i,
                        'jatuh_tempo' => $jatuh_tempo_perbulan,
                        'pokok' => $pokok,
                        'jasa' => $jasa,
                        'jml_angsuran' => $angs,
                        'sisa_pokok' => $newsisa,
                        'status_bayar' => 'Belum'
                    );
                    $this->m_transaksi->save_detail_pinjaman($detail_pinjaman);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $result['status'] = FALSE;
                    }
                }

                $this->db->delete('tb_arus_kas', array('transaksi' => 'Pembiayaan', 'id_transaksi' => $id_pinjaman));
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }

                $arus_kas = array(
                    'transaksi' => 'Pembiayaan',
                    'id_transaksi' => $id_pinjaman,
                    'keluar' => currencyToNumber(post_safe('jumlah')),
                    'keterangan' => 'Pembiayaan '. post_safe('norekening').' '.post_safe('nama_anggota'),
                    'id_user' => $this->session->userdata('id_user')
                );
                $this->m_transaksi->save_arus_kas($arus_kas);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $result['status'] = FALSE;
                }

                $get_adm = $this->db->get_where('tb_setting_administrasi')->row();
                $bea_adm = currencyToNumber(post_safe('jumlah'))*($get_adm->administrasi/100);
                $data_adpro = array(
                    'id_pinjaman' => $id_pinjaman,
                    'tgl_input' => date2mysql(post_safe('tanggal')),
                    'biaya_adm' => $bea_adm,
                    'biaya_ca' => $get_adm->calon_agt,
                    'survey' => $get_adm->survey,
                    'stofmap' => $get_adm->stofmap
                );
                $this->db->insert('tb_adpro', $data_adpro);
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
            $result['id'] = $id_pinjaman;
        }
        $this->response($result, 200);
    }
    
    /*Angsuran*/
    
    function angsurans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'nama' => get_safe('nama'),
            'norek' => get_safe('norek')
        );
        
        $data = $this->m_transaksi->get_list_angsurans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function angsuran_post() {
        $data = $this->m_transaksi->save_angsuran();
        $this->response($data, 200);
    }
    
    /*Penerimaan & Pengeluaran*/
    
    function penerimaan_pengeluarans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => date("Y-m-01"),
            'akhir' => date("Y-m-d")
        );
        
        $data = $this->m_transaksi->get_list_penerimaan_pengeluarans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function penerimaan_pengeluaran_post() {
        $param = array(
            'id' => post_safe('id'),
            'jenis' => post_safe('jenis'),
            'id_jenis' => post_safe('nama_transaksi'),
            'nominal' => currencyToNumber(post_safe('nominal')),
            'tanggal' => date2mysql(post_safe('tanggal')),
            'keterangan' => post_safe('keterangan')
        );
        $data = $this->m_transaksi->save_penerimaan_pengeluaran($param);
        $this->response($data, 200);
    }
    
    function penerimaan_pengeluaran_delete() {
        $this->db->delete('tb_operasional', array('id' => $this->get('id')));
    }
    
    /*Tabungan*/
    
    function tabungans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota'),
            'nama' => get_safe('nama'),
            'no_rekening' => get_safe('norek')
        );
        
        $data = $this->m_transaksi->get_list_tabungans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function saldo_simpanan_bebas_get() {
        $param = array(
            'id_anggota' => $this->get('id')
        );
        $data = $this->m_transaksi->sisa_saldo_simpanan_bebas($param);
        $this->response($data, 200);
    }
    
    function tabungan_post() {
        $data = $this->m_transaksi->save_pembukaan_tabungan();
        $this->response($data, 200);
    }
    
    function anggota_delete() {
        $this->db->delete('tb_anggota', array('id' => $this->get('id')));
    }
    
    function tabungan_delete() {
        $this->db->delete('tb_tabungan', array('id' => $this->get('id')));
    }
    
    /*Setoran Tabungan*/
    
    function setoran_tabungan_post() {
        $data = $this->m_transaksi->save_setoran_tabungan();
        $this->response($data, 200);
    }
    
    function setoran_tabungans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'norek' => get_safe('id_anggota')
        );
        
        $data = $this->m_transaksi->get_list_setoran_tabungans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function setoran_tabungan_delete() {
        $this->db->delete('tb_detail_tabungan', array('id' => $this->get('id')));
        $this->db->delete('tb_arus_kas', array('transaksi' => 'Tabungan', 'id_transaksi' => $this->get('id')));
    }
    
    /*Penarikan Tabungan*/
    
    function penarikan_tabungans_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'norek' => get_safe('id_anggota')
        );
        
        $data = $this->m_transaksi->get_list_penarikan_tabungans($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function penarikan_tabungan_post() {
        $data = $this->m_transaksi->save_penarikan_tabungan();
        $this->response($data, 200);
    }
    
    /*Koreksi saldo*/
    
    function koreksi_saldos_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => date("Y-m-01"),
            'akhir' => date("Y-m-d")
        );
        
        $data = $this->m_transaksi->get_list_koreksi_saldos($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function koreksi_saldo_post() {
        $data = $this->m_transaksi->save_koreksi_saldo();
        $this->response($data, 200);
    }
    
    function koreksi_saldo_delete() {
        $this->db->delete('tb_arus_kas', array('id' => $this->get('id')));
    }
    
    function simpanan_wajibs_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        
        $data = $this->m_transaksi->get_list_simpanan_wajib($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function saldo_simpanan_wajib_get() {
        $param = array(
            'id_anggota' => $this->get('id')
        );
        $data = $this->m_transaksi->sisa_saldo_simpanan_wajib($param);
        $this->response($data, 200);
    }
    
    function simpanan_wajib_post() {
        $param = array(
            'id' => post_safe('id'),
            'id_anggota' => post_safe('norek'),
            'masuk' => currencyToNumber(post_safe('nominal_tabungan')),
            'id_user' => $this->session->userdata('id_user')
        );
        $data = $this->m_transaksi->save_simpanan_wajib($param);
        $this->response($data, 200);
    }
    
    function simpanan_wajib_delete() {
        $this->db->delete('tb_detail_simpanan_wajib', array('id' => $this->get('id')));
    }
    
    function penarikan_simpanan_wajibs_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        
        $data = $this->m_transaksi->get_list_penarikan_simpanan_wajib($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function saldo_penarikan_simpanan_wajib_get() {
        $param = array(
            'id_anggota' => $this->get('id')
        );
        $data = $this->m_transaksi->sisa_saldo_penarikan_simpanan_wajib($param);
        $this->response($data, 200);
    }
    
    function penarikan_simpanan_wajib_post() {
        $param = array(
            'id' => post_safe('id'),
            'id_anggota' => post_safe('norek'),
            'keluar' => currencyToNumber(post_safe('nominal_tabungan')),
            'id_user' => $this->session->userdata('id_user')
        );
        $data = $this->m_transaksi->save_penarikan_simpanan_wajib($param);
        $this->response($data, 200);
    }
    
    function penarikan_simpanan_wajib_delete() {
        $this->db->delete('tb_detail_penarikan_simpanan_wajib', array('id' => $this->get('id')));
    }
    
    function penarikan_simpanan_pokoks_get() {
        if (!$this->get('page')) {
            $this->response(NULL, 400);
        }
        
        $start = ($this->get('page') - 1) * $this->limit;
        
        $search= array(
            'id' => $this->get('id'),
            'awal' => get_safe('awal'),
            'akhir' => get_safe('akhir'),
            'id_anggota' => get_safe('id_anggota')
        );
        
        $data = $this->m_transaksi->get_list_penarikan_simpanan_pokok($this->limit, $start, $search);
        $data['page'] = (int)$this->get('page');
        $data['limit'] = $this->limit;
        
        if($data){
            $this->response($data, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'Data tidak ditemukan'), 404);
        }
    }
    
    function saldo_simpanan_pokok_get() {
        $param = array(
            'id_anggota' => $this->get('id')
        );
        $data = $this->m_transaksi->sisa_saldo_simpanan_pokok($param);
        $this->response($data, 200);
    }
    
    function penarikan_simpanan_pokok_post() {
        $param = array(
            'id' => post_safe('id'),
            'id_anggota' => post_safe('norek'),
            'keluar' => currencyToNumber(post_safe('nominal_tabungan')),
            'id_user' => $this->session->userdata('id_user')
        );
        $data = $this->m_transaksi->save_penarikan_simpanan_pokok($param);
        $this->response($data, 200);
    }
    
    function penarikan_simpanan_pokok_delete() {
        $this->db->delete('tb_detail_penarikan_simpanan_pokok', array('id' => $this->get('id')));
    }
    
}