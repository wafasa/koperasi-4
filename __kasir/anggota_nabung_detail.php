<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
$isi.="<div class=kepala>Data Nasabah &raquo; <a href='anggota_nabung_detail.php?id=$_GET[id]'>Detail Tabungan Nasabah </a><a href='cetak_tab.php?id=$_GET[id]' target=_blank><img src=../images/cetak.png> <span class=kecil>cetak</span></a></span></div>

";

$isi.="<table style='width:700px;' cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>TANGGAL</h3></th>
				<th><h3>SANDI</h3></th>
				<th><h3>DEBET</h3></th>
				<th><h3>KREDIT</h3></th>
				<th><h3>SALDO</h3></th>
				<th><h3>TELLER</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	//$sql = mysql_query("select n.*, t.saldo from tb_nasabah n, tb_tabungan t where n.no_anggota = t.no_anggota and n.no_anggota = '$_GET[id]'");
	$sql = mysql_query("select n.*, t.saldo, th.* from tb_nasabah_tabungan n, tb_tabungan t, tb_tab_history th where n.no_agt_tab = t.no_agt_tab and t.no_tabungan = th.no_tabungan and n.no_agt_tab = '$_GET[id]' order by tanggal");
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = explode("-",$bar[tgl_masuk]);
		$tgl = "$tgl[2]/$tgl[1]/$tgl[0]";
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>".TglIndo($bar[tanggal])."</td>
		<td align=center>$bar[sandi]</td>
		<td>".duit($bar[kredit])."</td>
		<td>".duit($bar[debet])."</td>
		<td>".duit($bar[saldo])."</td>
		<td align=center>Kasir</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody></table>";
	$data = mysql_fetch_array(mysql_query("select n.*, t.saldo from tb_nasabah_tabungan n, tb_tabungan t where n.no_agt_tab = t.no_agt_tab and n.no_agt_tab = '$_GET[id]'",$conn));
	$isi.="
	<table align=left class=detail width=350px style='line-height:25px; font-weight:bold;'>
	<tr><td>No. Rekening</td><td>:</td><td width=400px id=underline align=left>$data[no_rekening]</td></tr>
	<tr><td>Nama </td><td>:</td><td id=underline>$data[nama]</td></tr>
	<tr><td valign=top>Alamat</td><td valign=top>:</td><td id=underline valign=top width=400px>$data[alamat]</td></tr>
	<tr><td>No. Identitas</td><td>:</td><td id=underline>$data[no_ktp]</td></tr>
	</table>
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