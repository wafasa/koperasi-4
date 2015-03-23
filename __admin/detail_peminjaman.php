<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Transaksi &raquo; <a href='data_peminjaman.php'>Data Pembiayaan</a> &raquo; Detail Pembiayaan</div>";
$data = mysql_fetch_array(mysql_query("select n.nama, n.alamat, n.rek_pinjaman, p.*, at.* from tb_pinjaman p, tb_nasabah n, tb_atribut_peminjaman at where p.no_anggota = n.no_anggota and at.kd_atribut = n.kd_atribut and p.no_pinjaman = '$_GET[no_pinj]'",$conn));
$isi.="
<table width=29% align=left class=detail>
<tr><td>Nama</td><td>:</td><td id=underline>$data[nama]</td></tr>
<tr><td valign=top>Alamat</td><td valign=top>:</td><td valign=top id=underline>$data[alamat]</td></tr>
<tr><td>No. Rekening</td><td>:</td><td id=underline>$data[rek_pinjaman]</td></tr>
<tr><td>Jangka Waktu</td><td>:</td><td id=underline>$data[lama_pinjaman] Bulan</td></tr>
<tr><td>Jumlah Pembiayaan</td><td>:</td><td id=underline>".duit($data[jml_pinjaman])."</td></tr>
<tr><td>Tanggal Realisasi</td><td>:</td><td id=underline>".TglIndo($data[tgl_pinjam])."</td></tr>
<tr><td>Angsuran</td><td>:</td><td id=underline>$data[lama_pinjaman] Bulan</td></tr>
<tr><td>Jatuh Tempo</td><td>:</td><td id=underline>".TglIndo($data[tgl_tempo])."</td></tr>
<tr><td>Angsuran Pokok</td><td>:</td><td id=underline>".duit($data[pokok])."</td></tr>
<tr><td>Bagi Hasil</td><td>:</td><td id=underline>".duit($data[jasa])."</td></tr>
<tr><td>Jumlah Angsuran / Bulan</td><td>:</td><td id=underline>".duit($data[bsr_angsuran])."</td></tr>
<tr><td>Agama</td><td>:</td><td id=underline>$data[agama]</td></tr>
<tr><td>Nama Suami / Istri</td><td>:</td><td id=underline>$data[nama_psg]</td></tr>
<tr><td>Pekerjaan Suami / Istri</td><td>:</td><td id=underline>$data[pekerjaan_psg]</td></tr>
<tr><td>Penghasilan per Bulan</td><td>:</td><td id=underline>$data[status_rumah]</td></tr>
<tr><td>Jaminan</td><td>:</td><td id=underline>$data[jaminan]</td></tr>
<tr><td>Rencana Pembiayaan</td><td>:</td><td id=underline>$data[rencana_pembiayaan]</td></tr>
<tr><td>Info Dari</td><td>:</td><td id=underline>$data[info_dari]</td></tr></table>
";
$isi.="<table style='width:70%;' cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>ANGS.KE</h3></th>
				<th><h3>JTH.TEMPO</h3></th>
				<th><h3>TGL BAYAR</h3></th>
				<th><h3>POKOK</h3></th>
				<th><h3>BAGI HASIL</h3></th>
				<th><h3>SISA POKOK PINJ</h3></th>
				<th><h3>STATUS</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	$sql = mysql_query("select n.rek_pinjaman, n.nama, a.* from tb_nasabah n, tb_angsuran a, tb_pinjaman p where p.no_anggota = n.no_anggota and a.no_pinjaman = p.no_pinjaman and a.no_pinjaman = '$_GET[no_pinj]'",$conn);
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_bayar]);
		$tgl2= TglIndo($bar[jatuh_tempo]);
		$isi.="
		<tr>
		<td align=center>$bar[angsuran_ke]</td>
		<td align=center>$tgl2</td>
		<td align=center>$tgl</td>
		<td align=center>".duit($bar[pokok])."</td>
		<td align=center>".duit($bar[jasa])."</td>
		<td>"; if ($bar[sisa_pokok] < 0) {$isi.="Rp. 0"; } else {$isi.=duit($bar[sisa_pokok]); } $isi.="</td>
		<td align=center>"; if ($bar[status_lunas] == 0) { $isi.="Belum Setor"; } else {$isi.="<center><img src='../images/s_success.png' width=13px></center>"; } $isi.="</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody></table>
	<div align=right><a href='cetak_pinjaman.php?id_pinj=$_GET[no_pinj]' target=_blank><img src='../images/cetak.png'><br>cetak</a></div>
	";
	include_once "instansiasi.php";
	?>
    <script type="text/javascript" src="../__js/script.js"></script>
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",0);
  </script>
    <?

include_once "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>