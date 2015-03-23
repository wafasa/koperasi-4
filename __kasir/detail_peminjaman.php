<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
$isi.="<div class=kepala>Data Transaksi &raquo; <a href='data_peminjaman.php'>Data Pembiayaan</a> &raquo; Detail Pembiayaan &nbsp;<a class=kecil href='#' target=_blank><img src='../images/cetak.png'>cetak</a></div>";
$data = mysql_fetch_array(mysql_query("select n.nama, n.alamat, n.rek_pinjaman, p.* from tb_pinjaman p, tb_nasabah n where p.no_anggota = n.no_anggota and p.no_pinjaman = '$_GET[no_pinj]'",$conn));
if ($_GET['msg'] == '1') {
	$isi.="<div class=ok>Data angsuran berhasil diubah</div>";
}
$isi.="
<table width=29% align=left class=detail>
<tr><td>Nama</td><td>:</td><td id=underline>$data[nama]</td></tr>
<tr><td valign=top>Alamat</td><td valign=top>:</td><td valign=top id=underline>$data[alamat]</td></tr>
<tr><td>No. Rekening</td><td>:</td><td id=underline>$data[rek_pinjaman]</td></tr>
<tr><td>Jangka Waktu</td><td>:</td><td id=underline>".duit($data[jml_pinjaman])."</td></tr>
<tr><td>Jumlah Pembiayaan</td><td>:</td><td id=underline>$data[lama_pinjaman] Bulan</td></tr>
<tr><td>Tanggal Realisasi</td><td>:</td><td id=underline>".TglIndo($data[tgl_pinjam])."</td></tr>
<tr><td>Angsuran</td><td>:</td><td id=underline>$data[lama_pinjaman] Bulan</td></tr>
<tr><td>Jatuh Tempo</td><td>:</td><td id=underline>".TglIndo($data[tgl_tempo])."</td></tr>
<tr><td>Angsuran Pokok</td><td>:</td><td id=underline>".duit($data[pokok])."</td></tr>
<tr><td>Bagi Hasil</td><td>:</td><td id=underline>".duit($data[jasa])."</td></tr>
<tr><td>Jumlah Angsuran / Bulan</td><td>:</td><td id=underline>".duit($data[bsr_angsuran])."</td></tr></table>
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
				<th><h3>STATUS</h3></th>";
				if ($data[jenis_pinjaman] == '1') {
					$isi.="<th><h3>ACTION</h3></th>";
				}
$isi.="</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	$sql = mysql_query("select n.rek_pinjaman, n.nama, a.* from tb_nasabah n, tb_angsuran a, tb_pinjaman p where p.no_anggota = n.no_anggota and a.no_pinjaman = p.no_pinjaman and a.no_pinjaman = '$_GET[no_pinj]' order by angsuran_ke asc",$conn);
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
		<td align=center>"; if ($bar[status_lunas] == 0) { $isi.="Belum Setor"; } else {$isi.="<center><img src='../images/s_success.png' width=13px></center>"; } $isi.="</td>";
		if ($data[jenis_pinjaman] == '1') {
		$isi.="<td align=center><a onclick=	\"ok=confirm('Anda akan mengedit data angsuran ke-$bar[angsuran_ke] ".strtoupper($bar[nama])." menjadi (Belum Setor)');
		if (ok) {
		return } else {return false} \" href='$PHP_SELF?act=koreksi&no=$bar[no_angsuran]&ke=$bar[angsuran_ke]&np=$bar[no_pinjaman]'>Edit</a></td>";
		}
		$isi.="</tr>
		";
	$no +=1;
	}
	$isi.="</tbody></table>
	";
	if ($_GET['act'] == 'koreksi') {
		$sql = mysql_query("update tb_angsuran set pokok = (select angsuran_pokok from tb_pinjaman where no_pinjaman = '$_GET[np]'), jasa = (select jasa_angsuran from tb_pinjaman where no_pinjaman = '$_GET[np]'), denda = '0', status_lunas = '0' where no_angsuran = '$_GET[no]'",$conn);
		header("location:$PHP_SELF?no_pinj=$_GET[np]&msg=1");
	}
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