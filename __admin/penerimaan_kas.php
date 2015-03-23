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
if (isset($_POST['kas'])) {
	$nom = gabung($_POST[nom]);
	$sr  = mysql_fetch_array(mysql_query("select * from tb_kas_koperasi order by no_kas desc limit 0, 1",$conn));
	$penerimaan_kas = $sr[penerimaan_kas] + $nom;
	$sql = mysql_query("update tb_kas_koperasi set penambahan_kas = '$penerimaan_kas' where no_kas = '$sr[no_kas]'",$conn);
	if ($sql) { 
		echo "<script>alert('Berhasil menambahkan kas koperasi')</script>";
		echo "<meta http-equiv=refresh content=0;url='penerimaan_kas.php'>";
	}
	else {
		echo "<script>alert('Gagal menambahkan kas koperasi')</script>";
		echo "<meta http-equiv=refresh content=0;url='penerimaan_kas.php'>";
	}
}
$isi.="<div class=kepala>Transaksi &raquo; <a href='penerimaan_kas.php'> Form Penerimaan Kas</a></div>";
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Penerimaan Kas Koperasi </td></tr>
	<tr>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<td width=18% height=25 valign=middle class=CellLabel>Tanggal</td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=tgl_msk type=text class=TextBox id=tgl_msk onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='".date("d/m/Y")."' size=11 maxlength=10 readonly /> &nbsp;
	<input name=tomb id=tomb type=button class=verd11 value='...' onclick=displayCalendar(document.forms[0].tgl_msk,'dd/mm/yyyy',this) /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Nominal <span class=merah>(Rp. )</span></td>
	<td height=25 valign=middle class=cellIsian><input name=nom type=text value='$_POST[nom]' size=45 maxlength=50 onKeyup=FormNum(this); /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	</table>
	<input type=submit class=tmbl name='kas' value='  Simpan  ' onclick=	\"ok=confirm('Yakinkah data yang dimasukkan sudah benar?');
		if (ok) {
		return } else {return false} \"></form>
	";
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>