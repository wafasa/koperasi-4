<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
$isi.="<div class=kepala>Data Transaksi &raquo; <a href='data_peminjaman.php'>Data Pembiayaan</a></div>";
//$isi.="<a style='text-decoration:none; font-size=13px;' href='#' onclick=showhide('div1');><input type=button value='Cari'></a>
$isi.="<center>
	
	<table align=center id=cari><tr><td><b>Cari</td><td>:</td><td><form action='' method=post name='kategori'><select name=kat class=TextBox onChange=submit(kategori)>
	<option value=''> -- Pilih Kategori -- </option>
	<option value='rek_pinjaman' "; if ($_POST[kat] == 'rek_pinjaman') { $isi.="selected"; } $isi.=">&nbsp;Rekening </option>
	<option value='nama' "; if ($_POST[kat] == 'nama') { $isi.="selected"; } $isi.=">&nbsp;Nama </option>
	<option value='tgl_pinjam' "; if ($_POST[kat] == 'tgl_pinjam') { $isi.="selected"; } $isi.=">&nbsp;Tanggal Pinjam </option>
	</select></form>
	</td><td></td>
	<td>";
	if ($_POST[kat] == '') { $sts = "disabled"; }
	$isi.="<form action='' method=post><input type=hidden name=newkat value='$_POST[kat]'><input type=hidden name=kat value='$_POST[kat]'>";
	if ($_POST[kat] == 'tgl_pinjam') { $isi.="&nbsp;Dari &nbsp;<input name=tgl type=text class=TextBox id=tgl onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[tgl]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[1].tgl,'dd/mm/yyyy',this) />
	Sampai &nbsp;<input name=smpe type=text class=TextBox id=smpe onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='$_POST[smpe]' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value=' ... ' readonly=readonly  onclick=displayCalendar(document.forms[1].smpe,'dd/mm/yyyy',this) />
	"; } else { $isi.="<input type=text name=val value='$_POST[val]' size=20 $sts>"; }
	
	$isi.="</td><td><input type=submit name=cari value='   Search   ' $sts></form></td></tr></table>
	</form></center>
<br>";
if ($_GET['msg'] == '1') {
	$isi.="<div class=ok>Berhasil menghapus data peminjam</div>";
}
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
	if ($_POST[newkat] == 'rek_pinjaman') {
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and $_POST[kat] = '$_POST[val]' and p.status_lunas = '0'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sia from tb_pinjaman where no_anggota = (select no_anggota from tb_nasabah where rek_pinjaman = '$_POST[val]') and status_lunas = '0'",$conn));
	}
	else if ($_POST[newkat] == 'nama') {
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and n.nama like ('%$_POST[val]%') and p.status_lunas = '0'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sia from tb_pinjaman p, tb_nasabah n where n.no_anggota = p.no_anggota and n.nama like ('%$_POST[val]%') and p.status_lunas = '0'",$conn));
	}
	else if ($_POST[newkat] == 'tgl_pinjam') {
	$dari = tgdb($_POST[tgl]);
	$smpe = tgdb($_POST[smpe]);
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.tgl_pinjam between '$dari' and '$smpe' and p.status_lunas = '0'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sia from tb_pinjaman where tgl_pinjam between '$dari' and '$smpe' and status_lunas = '0'",$conn));
	}
	else {
	$sql = mysql_query("select n.rek_pinjaman, n.nama, p.* from tb_nasabah n, tb_pinjaman p where p.no_anggota = n.no_anggota and p.status_lunas = '0'",$conn);
	$jml = mysql_fetch_array(mysql_query("select sum(jml_pinjaman) as total, sum(sisa_angsuran) as sia from tb_pinjaman where status_lunas = '0'",$conn));
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
		<a href='detail_peminjaman.php?no_pinj=$bar[no_pinjaman]' onmouseover= \"Tip('Klik untuk melihat detail angsuran') \" onmouseout= \"UnTip()\"><img src='../images/browse.png'></a>
		<a onclick=	\"ok=confirm('Semua data yang terkait akan ikut terhapus juga (pinjaman, angsuran, administrasi), yakin akan menghapus pinjaman ".strtoupper($bar[nama])."?');
		if (ok) {
		return } else {return false} \" href='$PHP_SELF?act=delete&no_pinj=$bar[no_pinjaman]' onmouseover= \"Tip('Klik untuk menghapus pinjaman') \" onmouseout= \"UnTip()\"><img src='../images/b_usrdrop.png'></a>
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
	if ($_GET['act'] == 'delete') {
		$sql = mysql_query("delete from tb_pinjaman where no_pinjaman = '$_GET[no_pinj]'",$conn);
		$ssq = mysql_query("delete from tb_angsuran where no_pinjaman = '$_GET[no_pinj]'",$conn);
		$ssl = mysql_query("delete from tb_adpro where no_pinjaman = '$_GET[no_pinj]'",$conn);
		$isi.="<meta http-equiv=refresh content=0;url=data_peminjaman.php?msg=1>";
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