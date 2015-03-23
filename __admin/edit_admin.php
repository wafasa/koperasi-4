<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
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
if ((isset($_POST['opp'])) and  (trim($_POST[ca]) != '') and  (trim($_POST[survey]) != '') and  (trim($_POST[stofmap]) != '')) {
	$ca = gabung($_POST[ca]);
	$su = gabung($_POST[survey]);
	$st = gabung($_POST[stofmap]);
	$sql = mysql_query("update tb_potongan_admin set calon_agt = '$ca', survey = '$su', stofmap = '$st'",$conn);
	if ($sql) { 
		echo "<script>alert('Berhasil mengubah data')</script>";
		echo "<meta http-equiv=refresh content=0;url='edit_admin.php'>";
	}
	else {
		echo "<script>alert('Gagal mengubah data')</script>";
		echo "<meta http-equiv=refresh content=0;url='edit_admin.php'>";
	}
}
$data = mysql_fetch_array(mysql_query("select * from tb_potongan_admin",$conn));
$isi.="<div class=kepala>Transaksi &raquo; <a href='edit_admin.php'> Edit Biaya Administrasi</a></div>";
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Edit Biaya Administrasi </td></tr>
	<tr>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal</td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button disabled class=verd11 value='...' onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Biaya Calon Anggota (Rp. )</td>
	<td height=25 valign=middle class=cellIsian><input name=ca type=text value='".number_format($data[calon_agt], 0, '','.')."' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td></tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Biaya Survey <span class=merah>(Rp. )</span></td>
	<td height=25 valign=middle class=cellIsian><input name=survey type=text value='".number_format($data[survey], 0, '','.')."' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Biaya Stofmap <span class=merah>(Rp. )</span></td>
	<td height=25 valign=middle class=cellIsian><input name=stofmap type=text value='".number_format($data[stofmap], 0, '','.')."' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	</table>
	<input type=submit class=tmbl name='opp' value='  Simpan Perubahan  ' onclick=	\"ok=confirm('Yakinkah data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></form>
	";
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>