<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Laporan &raquo; <a href='laporan_adpro.php'>Laporan Administrasi </a> </div>";
$isi.="<center>
	
	<table align=center id=cari><tr><td></td><td></td>
	<td>";
	$isi.="<form action='' method=post><input type=hidden name=newkat value='$_POST[kat]'><input type=hidden name=kat value='$_POST[kat]'>";
	$isi.="&nbsp;Dari &nbsp;<input name=tgl type=text class=TextBox id=tgl onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[tgl]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl,'dd/mm/yyyy',this) />
	Sampai &nbsp;<input name=smpe type=text class=TextBox id=smpe onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[smpe]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].smpe,'dd/mm/yyyy',this) />
	</td><td><input type=submit name=cari value='   Search   ' $sts></td></tr></table>
	</form></center>
<br>";


$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>TANGGAL</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>NO.REKENING</h3></th>
				<th><h3>ADMINISTRASI</h3></th>
				<th><h3>CALON ANGGOTA</h3></th>
				<th><h3>SURVEY</h3></th>
				<th><h3>STOFMAP</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
if (isset($_POST['cari'])) {
	$dari = tgdb($_POST[tgl]);
	$smpe = tgdb($_POST[smpe]);
	$sql = mysql_query("select n.rek_pinjaman, n.nama, a.* from tb_nasabah n, tb_adpro a, tb_pinjaman p where p.no_anggota = n.no_anggota and a.no_pinjaman = p.no_pinjaman and a.tgl_input between '$dari' and '$smpe'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro where tgl_input between '$dari' and '$smpe'",$conn));
	}
else {
	$sql = mysql_query("select n.rek_pinjaman, n.nama, a.* from tb_nasabah n, tb_adpro a, tb_pinjaman p where p.no_anggota = n.no_anggota and a.no_pinjaman = p.no_pinjaman",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(biaya_adm) as adm, sum(biaya_ca) as ca, sum(survey) as survey, sum(stofmap) as stofmap from tb_adpro",$conn));
	}
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_input]);
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td>".strtoupper($bar[nama])."</td>
		<td align=center>$bar[rek_pinjaman]</td>
		<td>".duit($bar[biaya_adm])."</td>
		<td align=center>"; if ($bar[biaya_ca] == '') {$isi.="<center>-</center>"; } else {$isi.=duit($bar[biaya_ca]); } $isi.="</td>
		<td align=center>"; if ($bar[survey] == 0) {$isi.="<center>-</center>"; } else {$isi.=duit($bar[survey]); } $isi.="</td>
		<td align=center>".duit($bar[stofmap])."</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody>
	<tr><td colspan=4>JUMLAH</td><td>".duit($jml[adm])."</td><td align=center>".duit($jml[ca])."</td><td align=center>".duit($jml[survey])."</td><td align=center>".duit($jml[stofmap])."</td></tr>
	</table><div id='controls'>
		<div id='perpage'>
			<select onchange='sorter.size(this.value)'>
			<option value='3'>3</option>
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
	</div>
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