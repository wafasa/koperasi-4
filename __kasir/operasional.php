<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '1')) {
include "template.php";
?>
<script> 
function cekisian(isian) {
	if (isian.jenis.value=="") {
		alert('Pilih jenis operasional yang ditentukan');
		isian.jenis.focus();
		return false;
	}
	if (isian.nom.value=="") {
		alert('Nominal tidak boleh kosong');
		isian.nom.focus();
		return false;
	}
}
</script>
<?
if (isset($_POST['opp'])) {
	$nom = gabung($_POST[nom]);
	$sql = mysql_query("insert into tb_operasional values ('',now(),(select jenis from tb_jenis_oop where kd_jenis = $_POST[jenis]),'$nom','$_POST[jenis]')",$conn);
	if ($sql) { 
		echo "<script>alert('Berhasil menambahkan operasional')</script>";
		echo "<meta http-equiv=refresh content=0;url='operasional.php'>";
	}
	else {
		echo "<script>alert('Gagal menambahkan operasional')</script>";
		echo "<meta http-equiv=refresh content=0;url='operasional.php'>";
	}
}
$isi.="<div class=kepala>Transaksi &raquo; <a href='operasional.php'> Pembayran Beban</a></div>";
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Pembayaran Beban </td></tr>
	<tr>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal</td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Jenis Operasional <span class=merah>*</span></td>
	<td height=25 valign=middle class=cellIsian><select name='jenis' class=TextBox><option value=''> --- Jenis Operasional ---</option>";
	$q = mysql_query("select * from tb_jenis_oop",$conn);
	while ($d = mysql_fetch_array($q)) {
		$isi.="<option value='$d[kd_jenis]'>$d[jenis]</option>";
	}
	$isi.="</select></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td></tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Nominal <span class=merah>(Rp. )</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nom type=text value='$data[pekerjaan]' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	</table>
	<input type=submit class=tmbl name='opp' value='  Simpan  ' onclick=	\"ok=confirm('Yakinkah data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></form>
	";
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>