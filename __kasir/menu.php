<?php
$menu.="<div id=qm0 class=qmmc>

<a class=home href='utama.php'>Home</a>
<dl class='dropdown'>
  <dt id='one-ddheader' onmouseover=ddMenu('one',1) onmouseout=ddMenu('one',-1)>Data Nasabah <img src='../images/bawah.gif' width=5></dt>
  <dd id='one-ddcontent' onmouseover=cancelHide('one') onmouseout=ddMenu('one',-1)>
    <ul>
      <li><a href='list_nasabah.php' class='underline'>List Nasabah</a></li>
      <li><a href='anggota_form.php'>Pendaftaran Nasabah</a></li>
    </ul>
  </dd>
</dl>

<dl class='dropdown'>
  <dt id='three-ddheader' onmouseover=ddMenu('three',1) onmouseout=ddMenu('three',-1)>Data Transaksi <img src='../images/bawah.gif' width=5></dt>
  <dd id='three-ddcontent' onmouseover=cancelHide('three') onmouseout=ddMenu('three',-1)>
    <ul>
	  <li><a href='data_peminjaman.php' class='underline'>Data Pembiayaan</a></li>
	  <li><a href='laporan_angsuran.php' class='underline'>Data Pengangsuran</a></li>
      <li><a href='form_peminjaman.php' class='underline'>Formulir Pembiayaan</a></li>
      <li><a href='form_angsuran.php' class='underline'>Form Angsuran Pembiayaan</a></li>
	  <li><a href='operasional.php'>Pembayaran Beban </a></li>
	  <li><a href='transaksi_kas_harian.php'>Data Kas Harian </a></li>
	  
    </ul>
  </dd>
</dl>
<dl class='dropdown'>
  <dt id='four-ddheader' onmouseover=ddMenu('four',1) onmouseout=ddMenu('four',-1)>Data Tabungan <img src='../images/bawah.gif' width=5></dt>
  <dd id='four-ddcontent' onmouseover=cancelHide('four') onmouseout=ddMenu('four',-1)>
    <ul>
      <li><a href='tambah_tabungan.php' class='underline'>Tambah Penabung</a></li>
      <li><a href='form_input_tab.php' class='underline'>Setoran Tabungan</a></li>
	  <li><a href='form_penarikan_tab.php'>Penarikan Tabungan</a></li>
	  <li><a href='anggota_nabung.php' class='underline'>Data Penabungan</a></li>
    </ul>
  </dd>
</dl>
<a class=home href='logout.php'>Logout</a>



</div>
";
?>