<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Transaksi &raquo; <a href='laporan_angsuran.php'>Laporan Angsuran</a></div>";

if ((isset($_POST[tgl])) and (isset($_POST[smpe]))) {
	$dari = $_POST[tgl];
	$smpe = $_POST[smpe];
}
else {
	$dari = date("d/m/Y");
	$smpe = date("d/m/Y");
}
$isi.="<center>
	
	<table align=center id=cari><tr><td></td><td></td>
	<td>";
	$isi.="<form action='' method=post><input type=hidden name=newkat value='$_POST[kat]'><input type=hidden name=kat value='$_POST[kat]'>";
	$isi.="&nbsp;Dari &nbsp;<input name=tgl type=text class=TextBox id=tgl onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$dari' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl,'dd/mm/yyyy',this) />
	Sampai &nbsp;<input name=smpe type=text class=TextBox id=smpe onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$smpe' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].smpe,'dd/mm/yyyy',this) />
	</td><td><input type=submit name=cari value='   Search   ' $sts></td></tr></table>
	</form></center>
<br>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>TGL.BAYAR</h3></th>
				<th><h3>NO REKENING</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>TGL.TEMPO</h3></th>
				<th><h3>KE</h3></th>
				<th><h3>POKOK</h3></th>
				<th><h3>BAHAS</h3></th>
				<th><h3>SISA.POKOK</h3></th>
				<th><h3>DENDA</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	if (isset($_POST[tgl])) {
	$dari = tgdb($_POST[tgl]);
	$smpe = tgdb($_POST[smpe]);
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.*, a.* from tb_nasabah n, tb_pinjaman p, tb_angsuran a where p.no_anggota = n.no_anggota and p.no_pinjaman = a.no_pinjaman and a.status_lunas = '1' and a.tgl_bayar between '$dari' and '$smpe'",$conn);
	$data= mysql_fetch_array(mysql_query("select sum(pokok) as pokok, sum(jasa) as jasa, sum(denda) as denda from tb_angsuran where tgl_bayar between '$dari' and '$smpe'",$conn));
	}
	else {
	$tgl = date("Y-m-d");
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.*, a.* from tb_nasabah n, tb_pinjaman p, tb_angsuran a where p.no_anggota = n.no_anggota and p.no_pinjaman = a.no_pinjaman and a.status_lunas = '1' and a.tgl_bayar = '$tgl'",$conn);
	$data= mysql_fetch_array(mysql_query("select sum(pokok) as pokok, sum(jasa) as jasa, sum(denda) as denda from tb_angsuran where tgl_bayar = '$tgl'",$conn));
	}
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_bayar]);
		$tgl2= TglIndo($bar[jatuh_tempo]);
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>$tgl</td>
		<td align=center>".strtoupper($bar[rek_pinjaman])."</td>
		<td>".strtoupper($bar[nama])."</td>
		<td align=center>$tgl2</td>
		<td align=center>$bar[angsuran_ke]</td>
		<td>".duit($bar[pokok])."</td>
		<td>".duit($bar[jasa])."</td>
		
		<td>".duit($bar[sisa_pokok])."</td>
		<td>"; if ($bar[denda] == '0') { $isi.="<center>-</center>"; } else {$isi.=duit($bar[denda]); } $isi.="</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody>
	<tr><td colspan=6>JUMLAH</td><td>".duit($data[pokok])."</td><td>".duit($data[jasa])."</td><td></td><td>".duit($data[denda])."</td></tr>
	</table><div id='controls'>
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
		$sql = mysql_query("delete from tb_pinjaman where no_pinjaman = '$_GET[id]'",$conn);
		$ssq = mysql_query("delete from tb_angsuran where no_pinjaman = '$_GET[id]'",$conn);
		$isi.="<meta http-equiv=refresh content=0;url=data_peminjaman.php>";
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