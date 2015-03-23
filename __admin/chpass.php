<?php
session_start();
if ((session_is_registered(user)) and (session_is_registered(pass)) and ($_SESSION['leve'] == '2')) {
include "template.php";
?>
<script> 
function cekisian(isian) {
	if (isian.passlama.value=="") {
		alert('Password lama masih kosong');
		isian.passlama.focus();
		return false;
	}
	if (isian.passbaru.value=="") {
		alert('Password baru masih kosong');
		isian.passbaru.focus();
		return false;
	}
	if (isian.passret.value=="") {
		alert('Password retype masih kosong');
		isian.passret.focus();
		return false;
	}
}
</script>
<?
if (isset($_POST['opp'])) {
	$passlama = md5($_POST[passlama]);
	$passbaru = md5($_POST[passbaru]);
	$passret  = md5($_POST[passret]);
	$data = mysql_fetch_array(mysql_query("select * from tb_usersystem where username = '$_SESSION[user]'",$conn));
	if ($data[password] != $passlama) {
		echo "<script>alert('Password lama tidak sesuai')</script>";
	}
	else if ($passbaru != $passret) {
		echo "<script>alert('Password baru & password retype tidak sesuai')</script>";
	}
	else {
		$sql = mysql_query("update tb_usersystem set password = '$passbaru' where username = '$_SESSION[user]'",$conn);
		if ($sql) {
			echo "<script>alert('Password baru berhasil diubah')</script>";
			echo "<meta http-equiv=refresh content=0;url=chpass.php>";
		}
		else {
			echo "<script>alert('Password gagal diubah')</script>";
		}
	}
	
}
$isi.="<div class=kepala>Konfigurasi Sistem &raquo; <a href='chpass.php'> Ubah Password</a></div>";
$isi.="
	
	<table width=100% class=form border=0 cellspacing=0 cellpadding=0>
	<tr><td colspan=4 class=judulform>Form Ubah Password Administrasi </td></tr>
	<tr>
	<form name=form1 action='' method=post onSubmit='return cekisian(this);'>
	<td width=18% height=25 valign=middle class=CellLabel>Password Lama</td>
	<td width=33% height=25 valign=middle class=cellIsian><input name=passlama type=password class=TextBox onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='' size=45 /> &nbsp;
	</td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	
	<tr>
	<td height=25 valign=middle class=CellLabel>Password Baru</td>
	<td height=25 valign=middle class=cellIsian><input name=passbaru type=password class=TextBox onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='' size=45 /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td></tr>
	<tr>
	<td height=25 valign=middle class=CellLabel>Retype Password</td>
	<td height=25 valign=middle class=cellIsian><input name=passret type=password class=TextBox onfocus=this.className=TextBoxOn onblur=this.className=TextBox value='' size=45 /></td>
	<td height=25 valign=middle class=CellLabel></td>
	<td height=25 valign=middle class=cellIsian></td>
	</tr>
	<tr>
	<td colspan=4 height=25 valign=middle class=CellLabel style=color:blue></td>
	</tr>
	</table>
	<input type=submit class=tmbl name='opp' value='  Simpan  '></form>
	";
include "instansiasi.php";
}
else {
	header("location:../login.php");
}
?>