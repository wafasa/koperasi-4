<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Tabungan &raquo; <a href='laporan_tabungan.php'>Laporan Penabungan</a></div>";
if (isset($_POST['cari'])) {
		$dari= tgdb($_POST[dari]);
		$smpe= tgdb($_POST[sampai]);
		$dr  = $_POST[dari];
		$sp  = $_POST[sampai];	
		$tgl = "and th.tanggal between '$dari' and '$smpe'";
		$data= mysql_fetch_array(mysql_query("select sum(debet) as debet, sum(kredit) as kredit from tb_tab_history where sandi != '4' and tanggal between '$dari' and '$smpe'",$conn));
	}
	else {
		$dari= date("Y-m-d");
		$smpe= date("Y-m-d");
		$dr  = date("d/m/Y");
		$sp  = date("d/m/Y");
		$tgl = "and th.tanggal between '$dari' and '$smpe'";
		$data= mysql_fetch_array(mysql_query("select sum(debet) as debet, sum(kredit) as kredit from tb_tab_history where sandi != '4' and tanggal between '$dari' and '$smpe'",$conn));
		
	}
$isi.="
	<form action='' method=post>
	<center><table id=cari width=60%><tr><td><b>Tanggal mulai</td>
	<td><input name=dari type=text class=TextBox id=dari onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$dr' size=15 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' onclick=displayCalendar(document.forms[0].dari,'dd/mm/yyyy',this) /></td>
	<td><b>Tanggal sampai</td>
	<td><input name=sampai type=text class=TextBox id=sampai onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$sp' size=15 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' onclick=displayCalendar(document.forms[0].sampai,'dd/mm/yyyy',this) /></td>
	<td><input type=submit name=cari value='   Search   '></td></tr></table>
	</form>
<br>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>TANGGAL</h3></th>
				<th><h3>NO.REKENING</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>DEBET</h3></th>
				<th><h3>KREDIT</h3></th>
				</tr>
		</thead>
		<tbody>
";
	
	$no  = 1;
	$sql = mysql_query("select nt.nama, nt.no_rekening, th.* from tb_nasabah_tabungan nt, tb_tabungan t, tb_tab_history th where th.no_tabungan = t.no_tabungan and t.no_agt_tab = nt.no_agt_tab and sandi != '4' $tgl",$conn);
		
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tanggal]);
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td align=center>$bar[no_rekening]</td>
		<td>".strtoupper($bar[nama])."</td>
		<td>".duit($bar[debet])."</td>
		<td>".duit($bar[kredit])."</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody>
	<tr><td colspan=4 align=center>JUMLAH</td><td>".duit($data[debet])."</td><td>".duit($data[kredit])."</td></tr></table>
	<div id='controls'>
		<div id='perpage' style='padding-left:26px;'>
			<select onchange='sorter.size(this.value)'>
			<option value='5'>5</option>
			<option value='10'>10</option>
			<option value='20' selected='selected'>20</option>
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