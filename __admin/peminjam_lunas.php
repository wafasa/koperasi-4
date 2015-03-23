<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
$isi.="<div class=kepala>Data Transaksi &raquo; <a href='peminjam_lunas.php'>Data Pembiayaan Lunas</a></div>";
//$isi.="<a style='text-decoration:none; font-size=13px;' href='#' onclick=showhide('div1');><input type=button value='Cari'></a>
$isi.="<center>
	<form action='' method=post>
	<table align=center id=cari><tr><td><b>Cari</td><td>:</td><td>
	Dari &nbsp;<input name=tgl type=text class=TextBox id=tgl onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[tgl]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].tgl,'dd/mm/yyyy',this) />
	Sampai &nbsp;<input name=smpe type=text class=TextBox id=smpe onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[smpe]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[0].smpe,'dd/mm/yyyy',this) />";
	
	$isi.="</td><td><input type=submit name=cari value='   Search   ' $sts></form></td></tr></table>
	</form></center>
<br>";
$isi.="<table cellpadding=0 cellspacing=0 border=0 id=table class=sortable>
		<thead>
			<tr>
				<th><h3>NO</h3></th>
				<th><h3>NO REK PINJ</h3></th>
				<th><h3>NAMA</h3></th>
				<th><h3>TGL.PINJ</h3></th>
				<th><h3>TGL.TEMPO</h3></th>
				<th><h3>PINJAMAN</h3></th>
				<th><h3>ANGSURAN</h3></th>
				<th><h3>POKOK</h3></th>
				<th><h3>JASA</h3></th>
				<th><h3>SISA.ANGSUR</h3></th>
				<th><h3>STATUS</h3></th>
				<th><h3>ACTION</h3></th>
				
			</tr>
		</thead>
		<tbody>
";
	$no  = 1;
	if ((isset($_POST['cari'])) and (!empty($_POST[tgl])) and (!empty($_POST[smpe]))) {
	$dari = tgdb($_POST[tgl]);
	$smpe = tgdb($_POST[smpe]);
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.status_lunas = '1' and p.tgl_pinjam between '$dari' and '$smpe'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sisa from tb_pinjaman where tgl_pinjam between '$dari' and '$smpe' and status_lunas = '1'",$conn));
	}
	else {
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.status_lunas = '1'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sisa from tb_pinjaman where status_lunas = '1'",$conn));
	}
	while ($bar = mysql_fetch_array($sql)) {
		$tgl = TglIndo($bar[tgl_pinjam]);
		$tgl2= TglIndo($bar[tgl_tempo]);
		$isi.="
		<tr>
		<td align=center>$no</td>
		<td align=center>".strtoupper($bar[rek_pinjaman])."</td>
		<td>".strtoupper($bar[nama])."</td>
		<td align=center>$tgl</td>
		<td align=center>$tgl2</td>
		<td>".duit($bar[jml_pinjaman])."</td>
		<td>".duit($bar[bsr_angsuran])."</td>
		<td>".duit($bar[angsuran_pokok])."</td>
		<td>".duit($bar[jasa_angsuran])."</td>
		
		<td>".duit($bar[sisa_angsuran])."</td>
		<td align=center>"; if ($bar[sisa_angsuran] == '0') { $isi.="<center><img src='../images/s_success.png'></center>"; } else {$isi.="Belum Lunas"; } $isi.="</td>
		<td align=center>
		<a href='detail_peminjaman.php?no_pinj=$bar[no_pinjaman]' onmouseover= \"Tip('Klik untuk melihat detail angsuran') \" onmouseout= \"UnTip()\"><img src='../images/browse.png'></a>&nbsp;
		</td>
		</tr>
		";
	$no +=1;
	}
	$isi.="</tbody>
	<tr><td colspan=5>JUMLAH</td><td>".duit($jml[total])."</td><td colspan=3></td><td>".duit($jml[sia])."</td><td colspan=2></td></tr>
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