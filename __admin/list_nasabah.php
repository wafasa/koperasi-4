<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Nasabah &raquo; <a href='list_anggota.php'>List Nasabah</a></div>";

$isi.="
	<form action='' method=post>
	<table id=cari width=100%><tr><td><b>Nama</td><td><input type=text name=nama size=30></td><td><b>Rekening Tabungan</td><td><input type=text name=rek_tab size=30></td><td><b>Rekening Pinjaman</td>
	<td><input type=text name=no_pinj size=30></td><td><input type=submit name=cari value='   Search   '></td></tr></table>
	</form>
<br>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				
				<th><h3>NO REK PINJ</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>ALAMAT</h3></th>
				<th><h3>NO KTP</h3></th>
				<th><h3>NO TELP</h3></th>
				<th><h3>TGL MASUK</h3></th>
				
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	if ((trim($_POST[nama]) != '') and (trim($_POST[rek_tab]) == '') and (trim($_POST[no_pinj]) == '')) {
	$sql = mysql_query("select * from tb_nasabah where nama like ('%$_POST[nama]%')");
	}
	else if ((trim($_POST[nama]) == '') and (trim($_POST[rek_tab]) != '') and (trim($_POST[no_pinj]) == '')) {
	$sql = mysql_query("select * from tb_nasabah where no_rekening = '$_POST[rek_tab]'");
	}
	else if ((trim($_POST[nama]) == '') and (trim($_POST[rek_tab]) == '') and (trim($_POST[no_pinj]) != '')) {
	$sql = mysql_query("select * from tb_nasabah where rek_pinjaman = '$_POST[no_pinj]'");
	}
	else {
	$sql = mysql_query("select * from tb_nasabah");
	}
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = explode("-",$bar[tgl_masuk]);
		$tgl = "$tgl[2]/$tgl[1]/$tgl[0]";
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>"; if ($bar[rek_pinjaman] == '') { $isi.="-"; } else { $isi.="".strtoupper($bar[rek_pinjaman]).""; } $isi.="</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>".ucwords($bar[alamat])."</td>
		<td>".strtoupper($bar[no_ktp])."</td>
		<td align=center>$bar[no_telp]</td>
		<td align=center>$tgl</td>
		
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody></table><div id='controls'>
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
		$sql = mysql_query("delete from tb_nasabah where no_anggota = '$_GET[id]'",$conn);
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