<?php
$menu = "";
$menu.="<div id=qm0 class=qmmc>

<a class=home href='utama.php'>Home</a>
<dl class='dropdown'>
  <dt id='one-ddheader' onmouseover=ddMenu('one',1) onmouseout=ddMenu('one',-1)>Data Nasabah <img src='../images/bawah.gif' width=5></dt>
  <dd id='one-ddcontent' onmouseover=cancelHide('one') onmouseout=ddMenu('one',-1)>
    <ul>
      <li><a href='list_nasabah.php' class='underline'>Daftar Nasabah</a></li>
    </ul>
  </dd>
</dl>
<dl class='dropdown'>
  <dt id='two-ddheader' onmouseover=ddMenu('two',1) onmouseout=ddMenu('two',-1)>Data Transaksi <img src='../images/bawah.gif' width=5></dt>
  <dd id='two-ddcontent' onmouseover=cancelHide('two') onmouseout=ddMenu('two',-1)>
    <ul>
      
       <li><a href='transaksi_kas_harian.php' class='underline'>Transaksi Kas Harian</a></li>
	   <li><a href='laporan_tabungan.php' class='underline'>Transaksi Tabungan</a></li>
	   <li><a href='penerimaan_kas.php' class='underline'>Input Penerimaan Kas</a></li>
	   <li><a href='angsuran_terlambat.php' class='underline'>Data Terlambat Angsur</a></li>
	   <li><a href='edit_admin.php' class='underline'>Edit Biaya Administrasi</a></li>
    </ul>
  </dd>
</dl>
<dl class='dropdown'>
  <dt id='three-ddheader' onmouseover=ddMenu('three',1) onmouseout=ddMenu('three',-1)>Data Laporan <img src='../images/bawah.gif' width=5></dt>
  <dd id='three-ddcontent' onmouseover=cancelHide('three') onmouseout=ddMenu('three',-1)>
    <ul>
	  <li><a href='data_peminjaman.php' class='underline'>&nbsp;Laporan Pembiayaan</a></li>
      <li><a href='anggota_nabung.php' class='underline'>&nbsp;Laporan Tabungan</a></li>
	  <li><a href='laporan_angsuran.php' class='underline'>&nbsp;Laporan Angsuran</a></li>
	  <li><a href='laporan_adpro.php' class='underline'>&nbsp;Pendapatan Administrasi</a></li>
	  <li><a href='laporan_aruskas.php' class='underline'>&nbsp;Laporan Arus Kas</a></li>
	  <li><a href='peminjam_lunas.php' class='underline'>&nbsp;Laporan Pembiayaan Lunas</a></li>
    </ul>
  </dd>
</dl>
<dl class='dropdown'>
  <dt id='four-ddheader' onmouseover=ddMenu('four',1) onmouseout=ddMenu('four',-1)>Configurasi Sistem <img src='../images/bawah.gif' width=5></dt>
  <dd id='four-ddcontent' onmouseover=cancelHide('four') onmouseout=ddMenu('four',-1)>
    <ul>
	  <li><a href='chpass.php' class='underline'>&nbsp;Ganti Password</a></li>
      <li><a href='usersystem.php' class='underline'>&nbsp;Manajemen Usersystem</a></li>
	  <li><a href='generate_tab.php' class='underline'>&nbsp;Generate Bunga Tabungan</a></li>
	    
    </ul>
  </dd>
</dl>

<a class=home href='logout.php'>Logout</a>



</div>
";
?>