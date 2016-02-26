<script type="text/javascript">
    $(function() {
        $("#menu-hidden").hide();
        $(window).scroll(function() {
            if ($(window).scrollTop() >= 100 ) {
                $("#menu-hidden").fadeIn("slow");
            } else {
                $("#menu-hidden").fadeOut("fast");
            }
        });
    });
</script>
<nav>
    <ul class="cf">
        <li> <a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><a><i class="fa fa-bank"></i> Profile</a>
            <ul>
                <?php 
                $profile = $this->m_main->get_list_profile()->result();
                foreach ($profile as $data) { ?>
                <li><a href="<?= base_url('main/detailprofile/'.$data->id.'/'.  post_slug($data->judul)) ?>"><?= $data->judul ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a onclick="javascript:;;"><i class="fa fa-graduation-cap"></i> Program Studi</a>
            <ul>
                <?php 
                $prodi = $this->m_main->get_list_prodi()->result();
                foreach ($prodi as $data) { ?>
                <li><a href="<?= base_url('main/detailprodi/'.$data->id.'/'.  post_slug($data->judul)) ?>"><?= $data->link ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a href=""><i class="fa fa-globe"></i> Publikasi Ilmiah</a>
            <ul>
                <?php 
                $kategori = $this->m_main->get_list_kategori_jurnal();
                foreach ($kategori as $data) { ?>
                <li><a href="<?= base_url('main/publikasi/'.$data->id.'/'.  post_slug($data->nama)) ?>"><?= $data->nama ?> <div class="circleBase type1" style="font-size: 11px; color: #FFFFFF;"> <?= $data->jumlah ?></div></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a href="<?= base_url('main/kontakkami') ?>"><i class="fa fa-phone"></i> Kontak</a></li>
        <li class="last"><a href=""><i class="fa fa-child"></i> Info PMB</a>
            <?php $pmb = $this->m_masterdata->get_data_config()->row(); ?>
            <ul>
                <li><a href="<?= base_url('main/pengumuman') ?>">Pengumuman</a></li>
                <?php if ($pmb->form_pmdk === 'Aktif') { ?>
                <li><a href="<?= base_url('main/pendaftaran_pmdk') ?>">Formulir Pendaftaran PMDK</a></li>
                <?php } ?>
                <?php if ($pmb->form_sumb === 'Aktif') { ?>
                <li><a href="<?= base_url('main/pendaftaran_sumb') ?>">Formulir Pendaftaran SUMB</a></li>
                <?php } ?>
            </ul>
        </li>
    </ul>
</nav>
<div class="wrapper col1" id="menu-hidden">
    <div class="wrapper col0">
        <div id="topbar"></div>
    </div>
    <div id="header">
      <div id="logo">
          <h1><a href="index.html"><img src="<?= base_url('assets/img/logo-univ.png') ?>" height="40" /></a></h1>
      </div>
      <div id="topnav">
          <nav>
              <ul class="cf">
                  <li> <a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
                  <li><a><i class="fa fa-bank"></i> Profile</a>
                      <ul>
                          <?php 
                          $profile = $this->m_main->get_list_profile()->result();
                          foreach ($profile as $data) { ?>
                          <li><a href="<?= base_url('main/detailprofile/'.$data->id.'/'.  post_slug($data->judul)) ?>"><?= $data->judul ?></a></li>
                          <?php } ?>
                      </ul>
                  </li>
                  <li><a onclick="javascript:;;"><i class="fa fa-graduation-cap"></i> Program Studi</a>
                      <ul>
                          <?php 
                          $prodi = $this->m_main->get_list_prodi()->result();
                          foreach ($prodi as $data) { ?>
                          <li><a href="<?= base_url('main/detailprodi/'.$data->id.'/'.  post_slug($data->judul)) ?>"><?= $data->link ?></a></li>
                          <?php } ?>
                      </ul>
                  </li>
                  <li><a href=""><i class="fa fa-globe"></i> Publikasi Ilmiah</a>
            <ul>
                <?php 
                $kategori = $this->m_main->get_list_kategori_jurnal();
                foreach ($kategori as $data) { ?>
                <li><a href="<?= base_url('main/publikasi/'.$data->id.'/'.  post_slug($data->nama)) ?>"><?= $data->nama ?> <div class="circleBase type1" style="font-size: 11px; color: #FFFFFF;"> <?= $data->jumlah ?></div></a></li>
                <?php } ?>
            </ul>
        </li>
                  <li><a href="<?= base_url('main/kontakkami') ?>"><i class="fa fa-phone"></i> Kontak</a></li>
                  <li class="last"><a href=""><i class="fa fa-child"></i> Info PMB</a>
                      <ul>
                            <li><a href="<?= base_url('main/pengumuman') ?>">Pengumuman</a></li>
                            <li><a href="<?= base_url('main/pendaftaran_pmdk') ?>">Formulir Pendaftaran PMDK</a></li>
                            <li><a href="<?= base_url('main/pendaftaran_sumb') ?>">Formulir Pendaftaran SUMB</a></li>
                      </ul>
                  </li>
              </ul>
          </nav>
          </div>
      <br />
    </div>
</div>
<!--<ul>
    <li class="active"><a href="<?= base_url('') ?>">Home</a></li>
    <li><a href="">Profil</a></li>
    <li><a href="">Program Studi</a></li>
    <li><a href="">Publikasi Ilmiah</a></li>
    <li><a href="">Kontak</a></li>
    <li class="last"><a href="">Info PMB</a></li>
  </ul>-->