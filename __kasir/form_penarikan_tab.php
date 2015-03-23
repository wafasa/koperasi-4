<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";

$isi.="<div class=kepala>Transaksi &raquo; <a href='form_penarikan_tab.php'>Form Penarikan Tabungan</a></div>";

?>
<body onLoad="document.form1.jumlah.focus();">
<?

if (isset($_POST['cari_rek'])) {
	$data = mysql_fetch_array(mysql_query("select * from tb_nasabah_tabungan where no_rekening = '$_POST[no_rek]'",$conn));
	$tab  = mysql_query("select saldo from tb_tabungan where no_agt_tab = '$data[no_agt_tab]'",$conn);
	$jum  = mysql_num_rows($tab);
	$no_rek = $_POST[no_rek];
	if ($jum == '0') {
		$duit = 0;
	} else {
		$mon  = mysql_fetch_array($tab);
		$duit = duit($mon[saldo]);
		//echo $duit;
	}
	//$data = mysql_fetch_array(mysql_query("select t.saldo, n.* from tb_nasabah n, tb_tabungan t where n.no_anggota = t.no_anggota and n.no_rekening = '$_POST[no_rek]'",$conn));
}
else if (isset($_POST['submit'])) {
	if ((trim($_POST['jumlah']) == '') or (trim($_POST['nama']) == '')) {
		echo "<script>alert('Data tabungan masih kosong')</script>";
	}
	else if ($_POST['saldo_akhir'] == '') {
		echo "<script>alert('Saldo akhir kosong, tidak bisa melakukan penarikan tabungan!!!')</script>";
		echo "<meta http-equiv=refresh content=0;url='form_penarikan_tab.php'>";
	}
	else {
		min_tab($_POST[no_agt],$_POST[jumlah]);
	}	
}
/*else {
	$data = mysql_fetch_array(mysql_query("select * from tb_nasabah where no_rekening = '$_GET[no_rek]'",$conn));
	$tab  = mysql_query("select saldo from tb_tabungan where no_anggota = '$data[no_anggota]'",$conn);
	$jum  = mysql_num_rows($tab);
	$no_rek = $_GET[no_rek];
	if ($jum == '0') {
		$duit = 0;
	} else {
		$mon  = mysql_fetch_array($tab);
		$duit = duit($mon[saldo]);
	}
	//$sts = "disabled";
}*/
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Penarikan Tabungan </td></tr>
	<tr>
	<td width=18% height=35 valign=middle class=CellLabel>Nomor Rekening Tabungan</td>
	<td height=35 class=cellIsian><form action='' method=post><input name='no_rek' type=text value='$no_rek' size=45 maxlength=50 /> &nbsp; <input type=submit class=tmbl name='cari_rek' value=' Go ' $sts></form></td>
	<td height=25 valign=middle class=CellLabel>Saldo Terakhir (Rp. )</td>
	<td height=25 valign=middle class=cellIsian>
	<input name=saldo_akhir type=text value='$duit' size=50 maxlength=46 onKeyup=FormNum(this); disabled/></td></tr>
	</tr>
	<tr>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<input name=saldo_akhir type=hidden value='$mon[saldo]' size=50 maxlength=46/>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal Penabungan<span class=merah>*</span></td>
	<td width=33% height=25 valign=middle class=cellIsian><input disabled name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' disabled  onclick=displayCalendar(document.forms[1].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel>Jumlah Nominal Penarikan &nbsp;(Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=jumlah type=text value='' size=50 maxlength=46 onKeyup=FormNum(this); /></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nama Lengkap <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nama type=text id=nama value='$data[nama]' size=45 maxlength=50 disabled/></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td></tr>
	
	
	<tr>
	<td height=25 valign=middle class=CellLabel>No. KTP <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><input name=ktp type=text value='$data[no_ktp]' size=45 maxlength=50 onKeyup=Angka(this); disabled/></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>

	
	<tr>
	<td height=25 class=CellLabel>Alamat rumah <span class=merah>*</span></td>
	<td height=25 class=cellIsian><textarea style='overflow:scroll;' name=alamat cols=40 rows=4 disabled>$data[alamat]</textarea></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	
	<tr>
	<td height=35 colspan=4 align=left valign=middle><input type=hidden name='no_agt' value='$data[no_agt_tab]'>
	<input name=nama type=hidden id=nama value='$data[nama]' size=45 maxlength=50/>
	<input name=no_tab type=hidden id=no_tab value='$data[no_tabungan]' size=45 maxlength=50/>
	<input name=submit type=submit class=tmbl value=' Simpan ' onclick=	\"ok=confirm('Yakin data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></td>
	</tr>
	</table>
	</form>
";

include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>