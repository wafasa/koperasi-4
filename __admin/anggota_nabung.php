<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Nasabah &raquo; <a href='anggota_nabung.php'>Data Penabungan Nasabah </a></div>";

$isi.="
	<table id=cari width=100%><tr><td>
	<form action='anggota_nabung.php' method=post>
	<table width=100%><tr><td><b>Nama Anggota</td><td><input type=text name=nama size=30></td><td><b>Rekening Tabungan</td><td><input type=text name=rek_tab size=30></td><td><input type=submit name=cari value='   Search   '></td></tr></table>
	</form></td></tr>
	<tr><td>
	".Inisial($ling)."
	</td></tr></table>
<br>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>NO REK TAB</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>NO KTP</h3></th>
				<th><h3>TGL MASUK</h3></th>
				<th><h3>SALDO</h3></th>
				<th><h3>ACTION</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	$sql = mysql_query("select n.*, t.saldo from tb_nasabah_tabungan n, tb_tabungan t where n.no_agt_tab = t.no_agt_tab");
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = explode("-",$bar[tgl_masuk]);
		$tgl = "$tgl[2]/$tgl[1]/$tgl[0]";
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>".strtoupper($bar[no_rekening])."</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>".strtoupper($bar[no_ktp])."</td>
		<td align=center>$tgl</td>
		<td>".duit($bar[saldo])."</td>
		<td align=center><a href='anggota_nabung_detail.php?id=$bar[no_agt_tab]' onmouseover= \"Tip('Klik Untuk Melihat Detail Tabungan') \" onmouseout= \"UnTip()\"><img src='../images/browse.png'></a> 
		</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody></table>";
	$tgl = date("Y-m-25");
	if (strtotime($tgl) > strtotime(date("Y-m-d"))) {
		$sts = "disabled";
	}
	$isi.="
	<div id='controls'>
		<div id='perpage'>
			<select onchange='sorter.size(this.value)'>
			<option value='5'>5</option>
			<option value='10' selected='selected'>10</option>
			<option value='20'>20</option>
			<option value='50'>50</option>
			<option value='100'>100</option>
			</select>
			<span>Baris per halaman</span>
		</div>
		<div id='navigation'>
			<img src='../images/first.gif' width='16' height='16' alt='First Page' onclick='sorter.move(-1,true)' />
			<img src='../images/previous.gif' width='16' height='16' alt='First Page' onclick='sorter.move(-1)' />
			<img src='../images/next.gif' width='16' height='16' alt='First Page' onclick='sorter.move(1)' />
			<img src='../images/last.gif' width='16' height='16' alt='Last Page' onclick='sorter.move(1,true)' />
		</div>
		<div id='text'>Halaman <span id='currentpage'></span> dari <span id='pagelimit'></span></div>
	</div>";
	if ($_GET['aksi'] == 'delete') {
		$sql = mysql_query("delete from tb_nasabah_tabungan where no_agt_tab = '$_GET[id]'",$conn);
		echo "<script>history.go(-1)</script>";
		//$isi.="<meta http-equiv=refresh content=0;url=list_anggota.php>";
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