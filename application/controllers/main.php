<?php

class Main extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_main'));
        $this->ist = 'AKN Kajen';
    }
    
    function index() {
        $data['title'] = 'Home - Akademi Komunitas Negeri Kajen';
        $data['slider'] = $this->m_main->get_list_slider()->result();
        $data['berita'] = $this->m_main->get_list_berita()->result();
        $data['prodi'] = $this->m_main->get_list_prodi()->result();
        $data['sambutan'] = $this->m_main->get_list_sambutan()->result();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['kemahasiswaan'] = $this->m_main->get_data_kegiatan_kemahasiswaan()->result();
        $this->load->view('public', $data);
    }
    
    function detailnews($id) {
        $data['title'] = 'Detail Berita - '.$this->ist;
        $data['berita'] = $this->m_main->get_list_berita($id)->row();
        $data['berita_lain'] = $this->m_main->get_list_berita()->result();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-news', $data);
    }
    
    function detailprofile($id) {
        $data['title'] = 'Profile - '.$this->ist;
        $data['profile'] = $this->m_main->get_list_profile($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-profile', $data);
    }
    
    function detailprodi($id) {
        $data['title'] = 'Program Studi - '.$this->ist;
        $data['profile'] = $this->m_main->get_list_prodi($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-prodi', $data);
    }
    
    function publikasi($id) {
        $data['title'] = 'Publikasi - '.$this->ist;
        $search = array(
            'id' => NULL,
            'kategori' => $id
        );
        $data['publikasi'] = $this->m_main->get_list_detail_publikasi($search)->result();
        $data['kategori'] = $this->m_main->get_list_kategori_jurnal_content($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['publikasi_lain'] = $this->m_main->get_list_kategori_jurnal_content()->result();
        $this->load->view('main/detail-publikasi', $data);
    }
    
    function publikasidetail($id_kategori, $id) {
        $data['title'] = 'Publikasi - '.$this->ist;
        $search = array(
            'id' => $id,
            'kategori' => $id_kategori
        );
        $data['publikasi'] = $this->m_main->get_list_detail_publikasi($search)->result();
        $data['kategori'] = $this->m_main->get_list_kategori_jurnal_content($id_kategori)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['publikasi_lain'] = $this->m_main->get_list_kategori_jurnal_content()->result();
        $data['publikasi_satu_kategori'] = $this->m_main->get_list_detail_publikasi(array('id' => NULL, 'kategori' => $id_kategori))->result();
        $this->load->view('main/detail-publikasi-2', $data);
    }
    
    function kontakkami() {
        $data['title'] = 'Kontak Kami - '.$this->ist;
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-kontak', $data);
    }
    
    function pengumuman() {
        $data['title'] = 'Pengumuman PMB - '.$this->ist;
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['info'] = $this->m_main->get_data_info_pmb()->row();
        $this->load->view('main/detail-info-pmb', $data);
    }
    
    function pendaftaran_pmdk() {
        $data['title'] = 'Pendaftaran Mahasiswa Baru Jalur <b>PMDK</b> - '.$this->ist;
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['info'] = $this->m_main->get_data_info_pmb()->row();
        $data['agama'] = array('Islam','Kristen','Protestan','Hindu','Budha');
        $data['tahun'] = $this->db->get('tb_ta_aktif')->row();
        $data['jurusan'] = $this->m_main->get_list_prodi()->result();
        $this->load->view('main/form-pendaftaran', $data);
    }
    
    function pendaftaran_sumb() {
        $data['title'] = 'Pendaftaran Mahasiswa Baru Jalur <b>SUMB</b> - '.$this->ist;
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $data['info'] = $this->m_main->get_data_info_pmb()->row();
        $data['agama'] = array('Islam','Kristen','Protestan','Hindu','Budha');
        $data['tahun'] = $this->db->get('tb_ta_aktif')->row();
        $data['jurusan'] = $this->m_main->get_list_prodi()->result();
        $this->load->view('main/form-pendaftaran-sumb', $data);
    }
    
    function detailsambutan($id) {
        $data['title'] = 'Sambutan - '.$this->ist;
        $data['profile'] = $this->m_main->get_list_sambutan($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-sambutan', $data);
    }
    
    function detailkemahasiswaan($id) {
        $data['title'] = 'Detail Kemahasiswaan';
        $data['profile'] = $this->m_main->get_data_kegiatan_kemahasiswaan($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-kemahasiswaan', $data);
    }
    
    function detailslider($id) {
        $data['title'] = 'Detail Slider';
        $data['profile'] = $this->m_main->get_list_slider($id)->row();
        $data['contact'] = $this->m_main->get_data_contact()->row();
        $this->load->view('main/detail-slider', $data);
    }
}